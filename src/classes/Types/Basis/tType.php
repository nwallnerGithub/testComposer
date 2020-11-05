<?php


namespace classes\Types;


abstract class tType
{
    protected $inhalt;

    public function __construct($input,  &$logger = null){
        if ( $logger != null) {
            $this->setVal( $input, $logger);
        } else {
            $this->setVal( $input );
        }
    }

    abstract function setVal( $input, &$logger = null);

    public function val() {
        return $this->inhalt;
    }

}