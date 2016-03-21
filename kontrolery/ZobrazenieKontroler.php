<?php

class ZobrazenieKontroler extends Kontroler {

    public function spracuj($parametre) {

        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();
        $pouzivatelovo_id = $pouzivatel['pouzivatel_id'];

        if (!$pouzivatel)
            $this->presmeruj('prihlasenie');

        
         $id = 0;
        if (!empty($_GET['zdroj_id'])) {
            $id = $_GET['zdroj_id'];
        }
        
        //vytvorenie novej instancie ktora nam umozni vypis zdrojov
        $vypisZdrojov = new VypisZdrojov();

        $zdroje_ukazka = $vypisZdrojov->vratZdrojPodlaZdrojId($id);
        $autori = $vypisZdrojov->vratAutorov($id);
        $slovicka = $vypisZdrojov->vratKlucoveSlova($id);
        $okruhy = $vypisZdrojov->vratOkruhy($id);


        $this->hlavicka['titulok'] = 'Zobrazenie';
        $this->hlavicka['klucove_slova'] = 'DATALEO';
        $this->hlavicka['popis'] = 'DATALEO';

        $this->data['zdroje_ukazka'] = $zdroje_ukazka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['autori'] = $autori; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['slovicka'] = $slovicka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['okruhy'] = $okruhy; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['pouzivatel'] = $pouzivatel; //prenesie do pohladu zdroje pole pouzivatel ktore obsahuje vsetky udaje z tabulky pouzivatel

        $this->pohlad = 'zobrazenie';
    }

}
