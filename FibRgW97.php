<?php
class FibRgW97 
{
    public function __construct($stream, $reader, $shift = 0)
    {
        $fields = array('reserved1', 'reserved2', 'reserved3', 'reserved4', 'reserved5', 
		'reserved6', 'reserved7','reserved8', 'reserved9', 'reserved10', 'reserved11', 
		'reserved12', 'reserved13', 'lidFE');
        $sizes = array(2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2);

        for($i = 0; $i < count($fields); $i++) {
            $this->$fields[$i] = $reader->get($shift, $sizes[$i], $stream);
            $shift += $sizes[$i];
        }
    }
}
