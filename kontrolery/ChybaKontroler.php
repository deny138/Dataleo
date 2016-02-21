<?php

class ChybaKontroler extends Kontroler {

    public function spracuj($parametre) {
        //hlavicka poziadavky
        header("HTTP//1.0 404 Not Found");
        //hlavicka stranky
        $this->hlavicka['titulok'] = 'Chyba 404';
        //nastavenie sablony
        $this->pohlad = 'chyba';
       
        
    }

}
