<?php

class UvodKontroler extends Kontroler {

    public function spracuj($parametre) {
        $spravcaPouzivatelov= new SpravcaPouzivatelov();
        if ((!empty($parametre[0]) && $parametre[0]=='odhlasit')){
            $spravcaPouzivatelov->odhlas();
            $this->presmeruj('uvod');
        }
        $this->hlavicka['titulok'] = 'DATALEO';
        $this->hlavicka['klucove_slova'] = 'DATALEO';
        $this->hlavicka['popis'] = 'DATALEO';
        $this->pohlad = 'uvod';
    }

}
