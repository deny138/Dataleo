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
        //metoda ktora vrati zdroje pre vypis  zoznamu vsetkych zdrojov pouzivatela
        $zdroje = $vypisZdrojov->vratZdrojeSautorom();
        //metoda ktora vrati zdroj podla zdroj_id a podla toho zobrazi ukazku
        $zdroje_ukazka = $vypisZdrojov->vratZdrojPodlaZdrojId($id); 

        $this->data['zdroje'] = $zdroje; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['zdroje_ukazka'] = $zdroje_ukazka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->pohlad = 'zdroje';
        
    }

}
