<?php

class ChybaKontroler extends Kontroler {

    public function spracuj($parametre) {
        //kontroler odosle prehliadacu hlavicku aby vedel ze ide o chybovy strankku
        header("HTTP//1.0 404 Not Found");
        //hlavicka stranky
        $this->hlavicka['titulok'] = 'Chyba 404';
        $this->hlavicka['popis'] = 'Chybova stranka Datalea';
        $this->hlavicka['klucove_slova'] = 'chyba, chybovy vypis';
        //nastavenie sablony
        $this->pohlad = 'chyba';
       
        
    }

}
