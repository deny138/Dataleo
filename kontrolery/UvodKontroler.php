<?php

class UvodKontroler extends Kontroler {

    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'DATALEO';
        $this->hlavicka['klucove_slova'] = 'DATALEO';
        $this->hlavicka['popis'] = 'DATALEO';
        $this->pohlad = 'uvod';
    }

}
