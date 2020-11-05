<?php


namespace classes\Types;


class tString extends tType {

    function setVal($input, &$logger = null) {
        $this->input = filter_var( $input, FILTER_SANITIZE_STRING);
    }
}