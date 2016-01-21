<?php
class StreamReader 
{
	private $data;
	private $isLittleEndian;

	public function __construct($data = '', $isLittleEndian = true)
	{
		$this->data = $data;
		$this->isLittleEndian = $isLittleEndian;
	}

	public function get($from, $count, $data = null) {
		if (is_null($data)) $data = $this->data;
		$string = substr($data, $from, $count);
		if ($this->isLittleEndian) $string = strrev($string);
		return hexdec(bin2hex($string));
	}
}
