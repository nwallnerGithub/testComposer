<?php


namespace classes\Types;

/**

Konstruktor
Übergeben werden kann eine Datums-Zeit-Angabe wie z.B. 1.1.2020 0:00:00 oder ein UNIX-Timestamp

Zugriff auf Einzelteile
Mit gJahr, gMonat, gTag, gWochentag, gStd, gMin, gSec

Korrektur der Zeitverschiebung
Wird nur eine Uhrzeit übergeben, wird der Timestamp korrigiert, sodass z.B. 0:1:15 dem Timestamp 85 entspricht

Ausgabe mit: gTimeText, gDateTimeText, gTimeOhneSecText oder gDateTimeOhneSecText
Zur Aufgabe des Datums sind die Funktionen gTimeText (z.B. 18:05:33), gDateTimeText (z.B. 1.1.2020, 18:05:33)
gTimeOhneSecText (siehe oben - ohne Sekunden), gDateTimeOhneSecText (siehe oben - ohne Sekunden)
Ausgabe über toString möglich

Formatierung für SQL mit gDateSQL
Mit gDateSQL kann die Datumsangabe für eine SQL-Datenbank formatiert werden, in der DATETIME erwartet wird

Zeitliche Abstände mit gAbsSec, gAbsNwZeit
Mit gAbsSec kann der zeitliche Abstand in Sekunden zu einem 2. nwZeit-Objekt bestimmt werden.
Mit gAbsNwZeit kann der zeitliche Abstand zu einem 2. nwZeit-Objekt bestimmt werden. Zurückgegeben wird wieder
ein nwZeit-Objekt, mit dem weitergearbeitet werden kann.

Uhrzeit mit Komma (z.B. 14,511) mit gUhrzeitKomma.
gUhrzeitKomma liefert eine Uhrzeit, z.B. 14:30:40 (=14 + (30*60+49)/3600 ) als float zurück, als 14.511
Diese Funktion wird zur Anzeige von Diagrammen benötigt.
 */

class tZeit
{
    private $zeitParsed;
    private $useGMTplus2 = true;

    public function __construct($zeitAlsString = "", $zeitAlsTimestamp = "", $useGMTplus2 = true)
    {
        $this->useGMTplus2 = $useGMTplus2;

        if ( $this->useGMTplus2) {
            if ( $zeitAlsString == "" && $zeitAlsTimestamp == "") {
                $this->zeitParsed = mktime();
            } else if ( is_numeric( $zeitAlsTimestamp)) {
                $this->zeitParsed = ($zeitAlsTimestamp-7200);
            } else if ( !$this->zeitParsed = (strtotime( $zeitAlsString)-7200)) {
                die( "Error: Aus der folgenden Zeichenkette konnte kein NilsDateTime-Objekt erzeugt werden: " . $zeitAlsString);
            }
        } else {
            if ( $zeitAlsString == "" && $zeitAlsTimestamp == "") {
                $this->zeitParsed = mktime();
            } else if ( is_numeric( $zeitAlsTimestamp)) {
                $this->zeitParsed = $zeitAlsTimestamp;
            } else if ( !$this->zeitParsed = strtotime( $zeitAlsString)) {
                die( "Error: Aus der folgenden Zeichenkette konnte kein NilsDateTime-Objekt erzeugt werden: " . $zeitAlsString);
            }
        }
    }

    //Standard-Getter
    public function getZeitParsed(){
        return $this->zeitParsed;
    }

    //Standard-Ausgaben der Datums-Bestandteile
    public function gJahr(){
        $h = date( "Y-m-d", $this->zeitParsed);
        if ( $h == '1970-01-01') {
            return 0;
        } else {
            return date( "Y", $this->zeitParsed);
        }

    }

    public function gMonat(){
        $h = date( "Y-m-d", $this->zeitParsed);
        if ( $h == '1970-01-01') {
            return 0;
        } else {
            return date( "m", $this->zeitParsed);
        }
    }

    public function gTag(){
        $h = date( "Y-m-d", $this->zeitParsed);
        if ( $h == '1970-01-01') {
            return 0;
        } else {
            return date( "d", $this->zeitParsed);
        }
    }

    public function gWochentag(){
        $h = date( "Y-m-d", $this->zeitParsed);
        if ( $h == '1970-01-01') {
            return "--";
        } else {
            return $this->int2wochentag( date( "w", $this->zeitParsed));
        }
    }

    public function gStd(){
        return date( "H", $this->zeitParsed);
    }

    public function gMin(){
        return date( "i", $this->zeitParsed);
    }

    public function gSec(){
        return date( "s", $this->zeitParsed);
    }


    public function __toString( ){
        $h = date( "Y-m-d", $this->zeitParsed);
        if ( $h == '1970-01-01') {
            return $this->gTimeText();
        } else {
            return $this->gDateTimeText();
        }
    }

    public function gTimeText(){
        return date( "H:i:s", $this->zeitParsed);
    }

    public function gDateTimeText(){
        return date( "d.m.Y H:i:s", $this->zeitParsed);
    }

    public function gTimeOhneSecText(){
        return date( "H:i", $this->zeitParsed) . " Uhr";
    }

    public function gDateTimeOhneSecText(){
        return date( "d.m.Y H:i", $this->zeitParsed);
    }

    public function gDateSQL(){
        return date( "Y-m-d H:i:s", $this->zeitParsed);
    }



    public function gAbsSec( $nwZeitObjekt) {
        return abs( $this->zeitParsed - $nwZeitObjekt->getZeitParsed());
    }

    public function gAbsNwZeit( $nwZeitObjekt) {
        $h = abs( $this->zeitParsed - $nwZeitObjekt->getZeitParsed());
        return new nwZeit( date( "Y-m-d H:i:s", mktime(0,0, $h, 1, 1, 1970)));
    }


    public function gUhrzeitKomma(){
        //Übergebene Zeit auf Stunden, Minuten und Sekunden reduzieren
        $tmp = $this->zeitParsed - strtotime( date( "Y-m-d", $this->zeitParsed));
        return round( $tmp / 3600, 3);
    }



    public function int2wochentag( $zahl, $short = false){
        if ( $zahl == 1) {
            if ($short){
                return "Mo";
            } else {
                return "Montag";
            }
        } else if ( $zahl == 2) {
            if ($short){
                return "Di";
            } else {
                return "Dienstag";
            }
        } else if ( $zahl == 3){
            if ($short){
                return "Mi";
            } else {
                return "Mittwoch";
            }
        } else if ( $zahl == 4){
            if ($short){
                return "Do";
            } else {
                return "Donnerstag";
            }
        } else if ( $zahl == 5) {
            if ($short){
                return "Fr";
            } else {
                return "Freitag";
            }
        } else if ( $zahl == 6) {
            if ($short){
                return "Sa";
            } else {
                return "Samstag";
            }
        } else if ( $zahl == 7) {
            if ($short){
                return "So";
            } else {
                return "Sonntag";
            }
        } else {
            die ("Ungültige Zahl für einen Wochentag: " . $zahl);
        }
    }

}