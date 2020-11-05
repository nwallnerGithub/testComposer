<?php

namespace classes\DB\Basis;

use classes\Basic\BasicFunctions;
use classes\Basic\Logger;
use PDO;

class DB_ extends DBRaw
{

    public function __construct( Logger &$logger = null)
    {
        $this->parent(__construct($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, Logger &$logger = null)


    }


}

?>