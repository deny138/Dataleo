<?php

class Db {
    /*
     * je potrebna staticka premenna, aby sa spojenie s databazou ulozilo
     * a dalo sa pouzit zo vsetkych miest aplikacie
     * alternativne riesenie: vzor Singleton alebo Dependency Injection
     */

    private static $spojenie;

    /*
     * premenna nastavenia je potrebna pre vytvorenie noveho PDO ovladaca,
     * kde je vyuzita ako parameter
     */
    private static $nastavenia = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //chyby v mysql budu sposobovat vynimky
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", //inicializacny prikaz nastavenia kodovania    
    );

    /*
     * metoda pre pripojenie k databaze pomocou parametrov 
     * host- localhost, uzivatel a heslo- udaje k phpmyadmin,
     * databaza- nazov databazy- dataleo
     */

    public static function pripoj($host, $uzivatel, $heslo, $databaza) {
        if (!isset(self::$spojenie)) {
            self::$spojenie = @new PDO(
                    "mysql:host=$host;dbname=$databaza", $uzivatel, $heslo, self::$nastavenia
            );
        }
    }

    /*
     * metoda ktora vrati jeden riadok z databazy
     */

    public static function dotazJeden($dotaz, $parametre = array()) {
        $vysledok = self::$spojenie->prepare($dotaz); //vlozi sa text dotazu so zastupnymi znakmi?
        $vysledok->execute($parametre); // pripoji sa pole parametrov a dotaz sa vykona
        return $vysledok->fetch(); //fetch vrati 1 riadok
    }

    /*
     * metoda ktora vrati vsetky vysledky pozadovaneho dotazu
     */

    public static function dotazVsetky($dotaz, $parametre = array()) {
        $vysledok = self::$spojenie->prepare($dotaz);
        $vysledok->execute($parametre);
        return $vysledok->fetchAll();
    }

    /*
     * metoda ktora vrati stlpec napr v dotazoch select count(*),
     * cize vrati 1. hodnotu v 1.riadku
     */

    public static function dotazStlpec($dotaz, $parametre = array()) {
        $vysledok = self::dotazJeden($dotaz, $parametre);
        return $vysledok[0];
    }

    /*
     * metoda ktora vrati ovplyvneny pocet riadkov pomocou SQL dotazu
     */

    public static function dotaz($dotaz, $parametre = array()) {
        $vysledok = self::$spojenie->prepare($dotaz);
        $vysledok->execute($parametre);
        return $vysledok->rowCount();
    }

    /*
     * metoda vlozi novy riadok do tabulke ako data z asociativneho pola
     */
    public static function vloz($tabulka, $parametre = array()) {
        return self::dotaz("INSERT INTO `$tabulka`(`"
                        . implode('`,`', array_keys($parametre))
                        . "`) VALUES (" . str_repeat('?,', sizeOf($parametre) - 1)
                        . "?)", array_values($parametre));
    }

    /*
     * zmeni riadok v tabulke tak aby obsahoval data z asocitivneho pola
     */
    public static function zmen($tabulka, $hodnoty = array(), $podmienka, $parametre = array()) {
        return self::dotaz("UPDATE `$tabulka` SET `" .
                        implode('` = ?, `', array_keys($hodnoty)) .
                        "` = ? " . $podmienka, array_merge(array_values($hodnoty), $parametre));
    }

    /*
     * vrati ID posledneho vlozeneho zaznamu
     */
    public static function getLastId() {
        return self::$spojenie->lastInsertId();
    }

}
