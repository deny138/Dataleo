<?php

class ZdrojeKontroler extends Kontroler {

    public function spracuj($parametre) {

        $id = 0;
        if (!empty($_GET['zdroj_id'])) {
            $id = $_GET['zdroj_id'];
        }
        $this->hlavicka['titulok'] = 'Moje zdroje';
        //vytvorenie novej instancie ktora nam umozni vypis zdrojov
        $vypisZdrojov = new VypisZdrojov();

        $zdroje = $vypisZdrojov->vratZdrojeAutor();
        $zdroje_ukazka = $vypisZdrojov->vratZdroj($id);


        $this->data['zdroje'] = $zdroje; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['zdroje_ukazka'] = $zdroje_ukazka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->pohlad = 'zdroje';
        $this->pridajSpravu("fd");
    }

}
