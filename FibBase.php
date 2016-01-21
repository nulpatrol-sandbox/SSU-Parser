<?php
/*class Fib 
{
    public function __construct($stream, $reader, $shift = 0)
    {

        $fields = array('base', 'nFib', 'unused', 'lid', 'pnNext', 'flags', 'nFibBack',
        'IKey', 'envr', 'flags2', 'reserved3', 'reserved4', 'reserved5', 'reserved6');
        $sizes = array(2, 2, 2, 2, 2, 2, 2, 4, 1, 1, 2, 2, 4, 4);

        for($i = 0; $i < count($fields); $i++) {
            $this->$fields[$i] = $reader->get($shift, $sizes[$i], $stream);
            $shift += $sizes[$i];
        }
    }
}*/


class FibBase 
{
    public function __construct($stream, $reader, $shift = 0)
    {

        $fields = array('wIdent', 'nFib', 'unused', 'lid', 'pnNext', 'flags', 'nFibBack',
        'IKey', 'envr', 'flags2', 'reserved3', 'reserved4', 'reserved5', 'reserved6');
        $sizes = array(2, 2, 2, 2, 2, 2, 2, 4, 1, 1, 2, 2, 4, 4);

        for($i = 0; $i < count($fields); $i++) {
            $this->$fields[$i] = $reader->get($shift, $sizes[$i], $stream);
            $shift += $sizes[$i];
        }
        
        $this->fDot 		= ($this->flags & (1 << 0)) == (1 << 0);
        $this->fGlsy		= ($this->flags & (1 << 1)) == (1 << 1);
        $this->fComplex		= ($this->flags & (1 << 2)) == (1 << 2);
        $this->fHasPic		= ($this->flags & (1 << 3)) == (1 << 3);
        $this->cQuickSaves  = ($this->flags & 0x00f0) >> 4;
        $this->fEncrypted   = ($this->flags & (1 << 8)) == (1 << 8);
        $this->fWhichTblStm = ($this->flags & (1 << 9)) == (1 << 9);
        $this->fReadOnlyRecommended = ($this->flags & (1 << 10)) == (1 << 10);
        $this->fWriteReservation    = ($this->flags & (1 << 11)) == (1 << 11);
        $this->fExtChar 			= ($this->flags & (1 << 12)) == (1 << 12);
        $this->fLoadOverride 		= ($this->flags & (1 << 13)) == (1 << 13);
        $this->fFarEast 			= ($this->flags & (1 << 14)) == (1 << 14);
        $this->fObfuscated 			= ($this->flags & (1 << 15)) == (1 << 15);
        unset($this->flags);
    }
}
