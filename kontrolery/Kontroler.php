<?php

abstract class Kontroler {
    /*
     *  pole s datami kde si kontroler uklada data od modelov-
     *  predavanie medzi modelom  a pohladom
     */

    protected $data = array();
    protected $data_ukazka = array();
    /*
     * nazov pohladu ktory sa ma vypisat
     */
    protected $pohlad = "";
    protected $pohlad_ukazka = "";
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

    public function vypisPohladZdroj() {
        if ($this->pohlad_ukazka) {
            extract($this->data_ukazka);
            require("pohlady/" . $this->pohlad_ukazka . ".phtml");
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

   

}
