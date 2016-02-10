<?php

class UvodKontroler extends Kontroler {

    public function spracuj($parametre) {
        header("HTTP//1.0 Uvod"); //TODO: ako napisat header?
        $this->hlavicka['titulok'] = 'Uvodna stranka Datalea';
        $this->pohlad = 'uvod';
    
    }

}
