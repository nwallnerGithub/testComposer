<?php


namespace classes\Types;


class tLogMessage
{
    private $message;
    private $status;

    public function __construct($message, $status, &$logger = null){
        $inputMessage = new tString( $message, $logger);
        $inputStatus = new tInt( $status, 1, 4);

        $this->setMessage( $inputMessage->val());
        $this->setStatus( $inputStatus->val());
    }

    private function __get($name){
        if ( $name == "message") {

        }

    }
}