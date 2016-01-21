<?php
class FibRgLw97 
{
    public function __construct($stream, $reader, $shift = 0)
    {
        $fields = array('cbMac', 'reserved1', 'reserved2', 'cppText', 'cppFtn', 
		'cppHdd', 'reserved3','cppAtn', 'ccpEdn', 'cppTxbx', 'cppHdrTxbx', 
		'reserved4', 'reserved5', 'reserved6', 'reserved7', 'reserved8', 'reserved9',
		'reserved10', 'reserved11', 'reserved12', 'reserved13', 'reserved14');
        $sizes = array(4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4);

        for($i = 0; $i < count($fields); $i++) {
            $this->$fields[$i] = $reader->get($shift, $sizes[$i], $stream);
            $shift += $sizes[$i];
        }
    }
}
