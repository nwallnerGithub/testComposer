<?php


namespace classes\Types;


class tInt extends tType
{

    private $min;
    private $max;

    public function __construct($input, $mitIntervall = false, $min = 0, $max = 100, &$logger){
        $this->min = $min;
        $this->max = $max;

        parent::__construct( $input, $logger);
    }

    function setVal($input, &$logger = null){
        if ( $logger != null) {

        }
    }
}