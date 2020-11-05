<?php
namespace classes\Logger\Basis;

require __DIR__ . '/vendor/autoload.php';

use PDO;

class Logger
{
    private $meldungen = [];
    private $syncWithDB = false;
    private $pdo;
    private $aid;

    public function __construct( bool $syncWithDB = null, int $aid = null) {
        if ( $aid != null) {
            $this->aid = 0;
        } else {
            $this->aid = $aid;
        }

        if ( $syncWithDB != null) {
            $this->syncWithDB = true;

            require_once "config/Logger.conf.php";

            try {
                $this->pdo = new PDO('mysql:host=' . LOGGER_DB_HOST . ';dbname=' . LOGGER_DB_NAME,  LOGGER_DB_USER, LOGGER_DB_PASS);
                $this->pdo->exec("set names utf8");

                $statement = $this->pdo->prepare("SELECT id, uhrzeit, meldung, status FROM meldungen WHERE aid = ? ORDER BY uhrzeit ASC ");

                if ($statement->execute( $aid)) {
                    $h = $statement->fetchALL(PDO::FETCH_ASSOC);
                    if ( $h == null){
                        $this->meldungen = array();
                    } else {
                        $this->meldungen = $h;
                    }
                } else {
                    echo "SQL Fehler im Logger <br />";
                    echo $statement->queryString . "<br />";
                    echo $statement->errorInfo()[2];
                    exit;
                }
            } catch (Exception $e) {
                die( $e->getMessage());
            }
        }
    }

    public function getMeldungenArray() {
        return array_reverse( $this->meldungen);
    }

    public function getMeldungenHTML(){
        $meldungen_umgekehrt = array_reverse( $this->meldungen);
        foreach( $meldungen_umgekehrt as $meldung) {
            BasicFunctions::nwecho($meldung);
        }
    }

    public function addMeldung( string $meldung, int $status) {
        $this->meldungen[] = $meldung;

        if ( $this->syncWithDB) {
            $statement = $this->pdo->prepare("INSERT INTO meldungen(aid, meldung, status) VALUES( ?, ?, ?)");

            if (!$statement->execute( $this->aid, $meldung, $status)) {
                echo "SQL Fehler im Logger <br />";
                echo $statement->queryString . "<br />";
                echo $statement->errorInfo()[2];
                exit;
            }
        }
    }

}