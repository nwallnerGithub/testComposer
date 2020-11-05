<?php

namespace classes\Basic;

class BasicFunctions
{
    public static function nwecho( $ausgabe, $exitAfter = false) {
        if ( !is_array( $ausgabe)) echo "<p>" . $ausgabe . "</p>"; else self::pa( $ausgabe);

        if ( $exitAfter) exit;
    }

    public static function pae($array)
    {
        self::pa($array);
        exit;
    }

    public static function pa($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        //exit;
    }

    public static function ping()
    {
        echo "<p>Hier bin ich</p>";
        exit;
    }



}

