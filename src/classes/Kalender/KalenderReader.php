<?php


namespace classes\SHARED\Kalender;


use classes\SHARED\Basic\BasicFunctions;
use classes\SHARED\Kalender\CalDAV\SimpleCalDAVClient;
use classes\SHARED\Kalender\ICSParser\ICSParser;

/* Nils Idee und Erläuterung zur Verwendung

Über die beiden Methoden

--->loadTermineFromICS($url)

und

--->loadTermineFromCalDAV( $url, $username, $password, $kalendername, $beginnUNIX, $endeUNIX)

können Kalender ausgelesen werden.

In der Variante über ICS wird nur die URL benötigt - es werden alle Termine geladen.

In der Variante über einen CalDAV-Server wird zusätzlich zur URL, zum Start- und Enddatum,
ein Benutzername und Passwort, sowie der Kalendername benötigt.
Achtung: Es handelt sich hierbei um eine spezielle ID. Mit der Hilfsfunktion

--->getKalendersIDs( $url, $username, $password)

kann man sich diese anzeigen lassen.
*/

class KalenderReader
{
    //Enthält nach dem Einlesen des Kalenders alle Termine
    private $termine = [];

    //Die Termine können entweder direkt aus einer ICS-Datei...
    public function loadTermineFromICS($url) {
        $nw_parser = new ICSParser();
        $nw_parser->parseICSByURL( $url);
        $this->termine = $nw_parser->cal;
    }

    //...oder von einem CalDAV-Server geladen werden. In beiden Fällen entsteht im Attribut "termine"
    //eine Liste der Termine, mit der weitergearbeitet werden kann.
    public function loadTermineFromCalDAV( $url, $username, $password, $kalendername, $beginnUNIX, $endeUNIX){
        $c = new SimpleCalDAVClient();
        $c->connect($url, $username, $password);

        $arrayOfCalendars = $c->findCalendars();

        //Nils: Befehl, mit dem man die IDs der Kalender sehen kann.
        //BasicFunctions::pae( $arrayOfCalendars);


        $c->setCalendar($arrayOfCalendars[$kalendername]);
        $beginnCalDAV = $this->UNIXDate2CalDAVDate( $beginnUNIX);
        $endeCalDAV = $this->UNIXDate2CalDAVDate( $endeUNIX);
        $events = $c->getEvents( $beginnCalDAV, $endeCalDAV);

        $lines = ["BEGIN:VCALENDAR", "CALSCALE:GREGORIAN", "VERSION:2.0", "PRODID:-//SabreDAV//SabreDAV//EN"];

        foreach( $events as $event) {
            $tmp = $event->getData();
            $tmpAsArray = explode("\n", $tmp);
            array_pop( $tmpAsArray); //Letztes Element entfernen
            $tmpStripped = array_slice( $tmpAsArray, 4); //Ersten vier Elemente entfernen

            $lines = array_merge( $lines, $tmpStripped);
        }

        $lines = array_merge( $lines, ["END:VCALENDAR"]);

        $nw_parser = new ICSParser();
        $nw_parser->parseICSByArray( $lines);
        $this->termine = $nw_parser->cal;
    }

    //Hilfsfunktion, um sich alle KalenderIDs eines CalDAV-Accounts anzeigen zu lassen
    public function getKalendersIDs( $url, $username, $password)
    {
        $c = new SimpleCalDAVClient();
        $c->connect($url, $username, $password);

        $arrayOfCalendars = $c->findCalendars();

        BasicFunctions::pae( $arrayOfCalendars);
    }

    public function getTermine(){
        return $this->termine;
    }

    //Hilfsfunktionen zum Umwandeln in und von dem speziellen Datums-Format von Kalenderdateien
    public static function calDAVDate2UNIXDate( $calDAVDate) {
        date_default_timezone_set('Europe/Berlin');
        if (strlen( $calDAVDate ) == 8) {
            return mktime( 0,0,0, substr( $calDAVDate, 4, 2), substr( $calDAVDate, 6, 2), substr( $calDAVDate, 0, 4));
        } else {
            return mktime( substr( $calDAVDate, 9, 2), substr( $calDAVDate, 11, 2),0, substr( $calDAVDate, 4, 2), substr( $calDAVDate, 6, 2), substr( $calDAVDate, 0, 4));
        }

    }

    public static function unixDate2CalDAVDate( $UNIXDate) {
        return strftime("%Y%m%dT%H%M00Z", $UNIXDate);
    }

    public static function unix2Readable( $unixTimestamp) {
        if ( !is_numeric( $unixTimestamp)){
            return "ERROR: " . $unixTimestamp;
        } else {
            return date("d.m.Y, H:i", $unixTimestamp);
        }

    }

}