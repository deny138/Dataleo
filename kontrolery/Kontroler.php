<?php

abstract class Kontroler {
    /*
     *  pole s datami kde si kontroler uklada data od modelov-
     *  predavanie medzi modelom  a pohladom
     */

    protected $data = array();

    /*
     * nazov pohladu ktory sa ma vypisat
     */
    protected $pohlad = "";

    /*
     * hlavicka html stranky,tieto atributy ma kazda stranka
     */
    protected $hlavicka = array('titulok' => '', 'klucove_slova' => '', 'popis' => '');

    /*
     * hlavna metoda v ktorej spracuje kontroler svoje parametre
     */

    abstract function spracuj($parametre);

    /*
     * metoda ktora vypise pohlad pouzivatelovi
     * extract- rozbali premenne z pola $data 
     * vsetky indexy pola budu pristupne v sablone ako obyc.premenne
     */

    public function vypisPohlad() {
        if ($this->pohlad) {
            extract($this->data);
            require("pohlady/" . $this->pohlad . ".phtml");
        }
    }

    /*
     * presmerovanie na inu stranku a zastavenie spracovania skriptu
     */

    public function presmeruj($url) {
        header("Location: /$url");
        header("Connection: close");
        exit;
    }

    /*
     * spravy su aktualizovane po kazdom updatovani stranky
     * pridaj spravu- prida spravu do pola session
     * vrat spravy- vraci cele pole zo session
     */

    public function pridajSpravu($sprava) {
        if (isset($_SESSION['spravy']))
            $_SESSION['spravy'][] = $sprava;
        else
            $_SESSION['spravy'] = array($sprava);
    }

    public static function vratSpravy() {
        if (isset($_SESSION['spravy'])) {
            $spravy = $_SESSION['spravy'];
            unset($_SESSION['spravy']);
            return $spravy;
        } else
            return array();
    }

}
