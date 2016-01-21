<?php
require_once 'StreamReader.php';

class cfb {
	protected $data = '';

	protected $sectorShift = 9;
	protected $miniSectorShift = 6;
	protected $miniSectorCutoff = 4096;

	protected $fatChains = array();
	protected $fatEntries = array();

	protected $miniFATChains = array();
	protected $miniFAT = "";

	private $version = 3;
	private $isLittleEndian = true;

	private $cDir = 0;
	private $fDir = 0;

	private $cFAT = 0;

	private $cMiniFAT = 0;
	private $fMiniFAT = 0;

	private $DIFAT = array();
	private $cDIFAT = 0;
	private $fDIFAT = 0;
	protected $reader;

	const ENDOFCHAIN = 0xFFFFFFFE;
	const FREESECT   = 0xFFFFFFFF;

	public function read($filename) {
		$this->data = file_get_contents($filename);
		$this->reader = new StreamReader($this->data);
	}

	public function parse() {
		$abSig = strtoupper(bin2hex(substr($this->data, 0, 8)));
		if ($abSig != "D0CF11E0A1B11AE1" && $abSig != "0E11FC0DD0CF11E0") { return false; }
		echo '<b>parse</b>';
		$this->readHeader();
		$this->readDIFAT();
		$this->readFATChains();
		$this->readMiniFATChains();
		$this->readDirectoryStructure();

		$reStreamID = $this->getStreamIdByName("Root Entry");
		if ($reStreamID === false) { return false; }
		$this->miniFAT = $this->getStreamById($reStreamID, true);

		unset($this->DIFAT);
	}

	public function getStreamIdByName($name, $from = 0) {
		for($i = $from; $i < count($this->fatEntries); $i++) {
			if ($this->fatEntries[$i]["name"] == $name) return $i;
		}

		return false;
	}

	// Функция получает на вход номер потока ($id) и, в качестве исключения для корневого
	// вхождения, второй параметр. Возвращает бинарное содержимое данного потока.
	public function getStreamById($id, $isRoot = false) {
		$entry = $this->fatEntries[$id];
		// Получаем размер и позицию смещения на содержимое "текущего" файла.
		$from = $entry["start"];
		$size = $entry["size"];

		// Дальше варианта два - если размер меньше 4096 байт, то нам стоит читать данные
		// из MiniFAT'а, если больше так будем читать из общего FAT'а. Исключение RootEntry,
		// для которого мы должны прочитать содержимое из FAT'а - ведь там как раз таки
		// хранится MiniFAT.

		$stream = "";
		// Итак, перед нами вариант №1 - маленький размер и не корень
		if ($size < $this->miniSectorCutoff && !$isRoot) {
			// Получаем размер сектора miniFAT - 64 байта
			$ssize = 1 << $this->miniSectorShift;

			do {
				// Получаем смещение в miniFAT'е
				$start = $from << $this->miniSectorShift;
				// Читаем miniFAT-сектор
				$stream .= substr($this->miniFAT, $start, $ssize);
				// Находим следующий кусок miniFAT'а в массиве последовательностей
				$from = isset($this->miniFATChains[$from]) ? $this->miniFATChains[$from] : self::ENDOFCHAIN;
				// Пока не наткнёмся на флаг конца последовательности.
			} while ($from != self::ENDOFCHAIN);
		} else {
			// Вариант №2 - кусок большой - читаем из FAT.
			// Находим размер сектора - 512 (или 4096 для новых версий)
			$ssize = 1 << $this->sectorShift;
			
			do {
				// Находим смещение в файле (учитывая, что вначале файла заголовок на 512 байт)
				$start = ($from + 1) << $this->sectorShift;
				// Читаем сектор
				$stream .= substr($this->data, $start, $ssize);
				// Находим следующий сектор в массиве FAT-последовательностей
				#if (!isset($this->fatChains[$from]))
				#	$from = self::ENDOFCHAIN;
				#elseif ($from != self::ENDOFCHAIN && $from != self::FREESECT)
				#	$from = $this->fatChains[$from];
				$from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN;
				// Пока не наткнёмся на конец последовательности.
			} while ($from != self::ENDOFCHAIN);
		}
		// Возвращаем содержимое потока с учётом его размера.
		return substr($stream, 0, $size);
	}

	private function readHeader() {
		$uByteOrder = strtoupper(bin2hex(substr($this->data, 0x1C, 2)));
		$this->isLittleEndian = $uByteOrder == "FEFF";
		$this->version 			= $this->reader->get(0x1A, 2);
		$this->sectorShift 		= $this->reader->get(0x1E, 2);
		$this->miniSectorShift  = $this->reader->get(0x20, 2);
		$this->miniSectorCutoff = $this->reader->get(0x38, 4);
		if ($this->version == 4) 
					$this->cDir = $this->reader->get(0x28, 4);
		$this->fDir 			= $this->reader->get(0x30, 4);
		$this->cFAT 			= $this->reader->get(0x2C, 4);
		$this->cMiniFAT 		= $this->reader->get(0x40, 4);
		$this->fMiniFAT 		= $this->reader->get(0x3C, 4);
		$this->cDIFAT 			= $this->reader->get(0x48, 4);
		$this->fDIFAT 			= $this->reader->get(0x44, 4);
	}

	private function readDIFAT() {
		$this->DIFAT = array();
		for ($i = 0; $i < 109; $i++) $this->DIFAT[$i] = $this->reader->get(0x4C + $i * 4, 4);
		if ($this->fDIFAT != self::ENDOFCHAIN) {
			$size = 1 << $this->sectorShift;
			$from = $this->fDIFAT;
			$j = 0;

			do {
				// Получаем позицию в файле с учётом заголовка
				$start = ($from + 1) << $this->sectorShift;
				// Читаем ссылки на сектора цепочек
				for ($i = 0; $i < ($size - 4); $i += 4)
					$this->DIFAT[] = $this->reader->get($start + $i, 4);
				// Находим следующий DIFAT-сектор - ссылка на него
				// записана последним "словом" в текущем DIFAT-секторе
				$from = $this->reader->get($start + $i, 4);
				// Если сектор существует, то метнёмся к нему.
			} while ($from != self::ENDOFCHAIN && ++$j < $this->cDIFAT);
		}

		// Для экономии удаляем конечные неиспользуемые ссылки.
		while($this->DIFAT[count($this->DIFAT) - 1] == self::FREESECT)
			array_pop($this->DIFAT);
	}

	private function readFATChains() {
		$size = 1 << $this->sectorShift;
		$this->fatChains = array();
		for ($i = 0; $i < count($this->DIFAT); $i++) {
			$from = ($this->DIFAT[$i] + 1) << $this->sectorShift;
			for ($j = 0; $j < $size; $j += 4) {
				$this->fatChains[] = $this->reader->get($from + $j, 4);
			}
			
		}
	}

	private function readMiniFATChains() {
		$size = 1 << $this->sectorShift;
		$this->miniFATChains = array();
		$from = $this->fMiniFAT;
		while ($from != self::ENDOFCHAIN) {
			$start = ($from + 1) << $this->sectorShift;
			for ($i = 0; $i < $size; $i += 4)
				$this->miniFATChains[] = $this->reader->get($start + $i, 4);
			$from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN;
		}
	}

	private function readDirectoryStructure() {
		$from = $this->fDir;
		$size = 1 << $this->sectorShift;
		$this->fatEntries = array();
		do {
			$start = ($from + 1) << $this->sectorShift;
			for ($i = 0; $i < $size; $i += 128) {
				$entry = substr($this->data, $start + $i, 128);
				$this->fatEntries[] = array(
					'name'  => $this->utf16_to_ansi(substr($entry, 0, $this->reader->get(0x40, 2, $entry))),
					'type'  => $this->reader->get(0x42, 1, $entry),
					'color' => $this->reader->get(0x43, 1, $entry),
					'left'  => $this->reader->get(0x44, 4, $entry),
					'right' => $this->reader->get(0x48, 4, $entry),
					'child' => $this->reader->get(0x4C, 4, $entry),
					'start' => $this->reader->get(0x74, 4, $entry),
					'size'  => $this->reader->get(0x78, 8, $entry),
				);
				
			}

			$from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN;
		} while ($from != self::ENDOFCHAIN);
		
		while($this->fatEntries[count($this->fatEntries) - 1]["type"] == 0)
			array_pop($this->fatEntries);
	}

	private function utf16_to_ansi($in) {
		$out = "";
		for ($i = 0; $i < strlen($in); $i += 2)
			$out .= chr($this->reader->get($i, 2, $in));
		return trim($out);
	}
}