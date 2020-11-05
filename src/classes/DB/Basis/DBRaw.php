<?php

namespace classes\DB\Basis;

use classes\Basic\BasicFunctions;
use classes\Basic\Logger;
use PDO;

class DBRaw
{
    private $pdo;
    private $lastQueryWasEmptyValue = false;

    public function __construct($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, Logger &$logger = null)
    {
        try {
            $this->pdo = new PDO('mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME,  $DB_USER, $DB_PASS);
            $this->pdo->exec("set names utf8");

            if ($logger != null) {
                $logger->addMeldung( "Verbindung erfolgreich hergestellt");
            }
        } catch (Exception $e) {
            if ($logger != null) {
                $logger->addMeldung($e->getMessage());
            } else {
                die( $e->getMessage());
            }
        }
    }

    //sAbfrage = Standard-Abfrage mit einer Ergebniszeile (n=1) oder allen Ergebniszeilen (n=n) in assoziativem Array

    public function update($tabelle = "",
                           $spaltennamen = array(),
                           $werte = array(),
                           $bedingung = "? = 1",
                           $uebergabe = array())
    {

        //Für SQL-Anweisungen müssen Strings in Hochkommata gesetzt werden
        for ($i = 0; $i < sizeof($werte); $i++) {
            if (!is_numeric($werte[$i])) {
                $werte[$i] = "'" . $werte[$i] . "'";
            }
        }

        if (sizeof($spaltennamen) == sizeof($werte)) {
            $tmp = "";
            for ($i = 1; $i < sizeof($spaltennamen); $i++) {
                $tmp .= ", " . $spaltennamen[$i] . "=" . $werte[$i];
            }

            $sql = "UPDATE " . $tabelle . " SET " . $spaltennamen[0] . "=" . $werte[0] . $tmp . " WHERE " . $bedingung;

            $statement = $this->pdo->prepare($sql);

            if (!$statement->execute($uebergabe)) {
                echo "SQL Error <br />";
                echo $statement->queryString . "<br />";
                echo $statement->errorInfo()[2];
                exit;
            } else {
                return true;
            }
        } else {
            echo "Fehler im DB-Upate";
            exit;
        }

    }

    public function lastQueryWasEmpty(){
        return $this->lastQueryWasEmptyValue;
    }

    private function sAbfrage($sql = "",
                              $uebergabe = array(),
                              $n = "n")
    {

        $statement = $this->pdo->prepare($sql);
        if ($statement->execute($uebergabe)) {
            $h = $statement->fetchAll(PDO::FETCH_ASSOC);

            if ($h == null) {
                $this->lastQueryWasEmptyValue = true;
            } else {
                $this->lastQueryWasEmptyValue = false;
            }
            if ($n == "1") {
                return $h == null ? "" : $h[0];
            } else {
                return $h == null ? [] : $h;
            }
        } else {
            echo "SQL Error <br />";
            echo $statement->queryString . "<br />";
            echo $statement->errorInfo()[2];
            exit;
        }
    }

    public function sNAbfrage($sql = "SELECT * FROM login WHERE Username = ? AND Passwort = ?",
                              $uebergabe = array())
    {
        return $this->sAbfrage($sql, $uebergabe, "n");
    }
    public function s1Abfrage($sql = "SELECT * FROM login WHERE Username = ? AND Passwort = ?",
                              $uebergabe = array())
    {
        return $this->sAbfrage($sql, $uebergabe, "1");
    }



    //eAbfrage = existAbfrage
    public function eAbfrage($sql = "SELECT * FROM login WHERE Username = ? AND Passwort = ?",
                             $uebergabe = array())
    {

        $statement = $this->pdo->prepare($sql);

        if ($statement->execute($uebergabe)) {
            $flag = false;
            while ($row = $statement->fetch()) {
                $flag = true;
            }

            if ($flag) {
                return true;
            } else {
                return false;
            }
        } else {
            echo "SQL Error <br />";
            echo $statement->queryString . "<br />";
            echo $statement->errorInfo()[2];
            exit;
        }
    }

    public function insert($tabelle = "",
                           $spaltennamen = array("spaltenname1", "spaltenname2"),
                           $daten = array(1, 1)
    )
    {

        $tmp1 = $spaltennamen[0];
        $tmp2 = "?";
        for ($i = 1; $i < sizeof($spaltennamen); $i++) {
            $tmp1 .= "," . $spaltennamen[$i];
            $tmp2 .= ", ?";
        }
        $sql = "INSERT INTO " . $tabelle . " (" . $tmp1 . ") VALUES(" . $tmp2 . ")";
        $statement = $this->pdo->prepare($sql);

        //Statement für jeden Datensatz ausführen
        if (!$statement->execute($daten)) {
            echo "SQL Error <br />";
            echo $statement->queryString . "<br />";
            echo $statement->errorInfo()[2];
            exit;
        }
        return true;
    }


    public function delete($tabelle = "",
                           $whereBedingung = "id = ?",
                           $daten = array( 6)
    )
    {
        $sql = "DELETE FROM " . $tabelle . " WHERE " . $whereBedingung;

        $statement = $this->pdo->prepare( $sql);

        if (!$statement->execute( $daten)) {
            echo "SQL Error <br />";
            echo $statement->queryString . "<br />";
            echo $statement->errorInfo()[2];
            exit;
        }
        return true;
    }
}

?>