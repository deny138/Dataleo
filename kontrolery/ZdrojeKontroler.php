<?php

class ZdrojeKontroler extends Kontroler {

   

    public function spracuj($parametre) {
         $this->hlavicka['titulok'] = 'Moje zdroje';
        //vytvorenie novej instancie ktora nam umozni vypis zdrojov
        $vypisZdrojov = new VypisZdrojov();

        $zdroje = $vypisZdrojov->vratZdroje();
        $zdroje_ukazka = $vypisZdrojov->vratZdroj("3");
        $this->data['zdroje'] = $zdroje; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data_ukazka['zdroje_ukazka'] = $zdroje_ukazka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->pohlad = 'zdroje';
        $this->pohlad_ukazka = 'ukazka';
    }

}
