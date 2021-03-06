<?php

class ZdrojeKontroler extends Kontroler {

    public function spracuj($parametre) {
        //zabezpecenie aby v pohlade zdroje.phtml bol pristup k udajom o pouzivatelovi kvoli vypisu iba JEHO zdrojov
        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();
        $pouzivatelovo_id = $pouzivatel['pouzivatel_id'];

        if (!$pouzivatel)
            $this->presmeruj('prihlasenie');

        $zoradit = 'nazov';
        if (empty($_GET['zoradit'])) {
            //echo 'zoradit je prazdne';
        } else {
            $zoradit = $_GET['zoradit'];
            echo $zoradit;
        }

        $id = 0;
        if (!empty($_GET['zdroj_id'])) {
            $id = $_GET['zdroj_id'];
        }




        //vytvorenie novej instancie ktora nam umozni vypis zdrojov
        $vypisZdrojov = new VypisZdrojov();
        //metoda ktora vrati zdroje pre vypis  zoznamu vsetkych zdrojov pouzivatela, ktory je proihlaseny
        $zdroje = $vypisZdrojov->vratZdrojeBezAutora($pouzivatelovo_id, $zoradit);
        $zdroje_ukazka = $vypisZdrojov->vratZdrojPodlaZdrojId($id);
        $autori = $vypisZdrojov->vratAutorov($id);
        $slovicka = $vypisZdrojov->vratKlucoveSlova($id);
        $okruhy = $vypisZdrojov->vratOkruhy($id);
        //vsetky okruhy
        $vsetky_okruhy=$vypisZdrojov->vratVsetkyOkruhy($pouzivatelovo_id);
        $vsetky_slova=$vypisZdrojov->vratVsetkySlova($pouzivatelovo_id);
      

      
        
        if (empty($zdroje)) {
            $this->presmeruj('Prazdno');
        }

        $this->hlavicka['titulok'] = 'Knižnica';

        $this->data['zdroje'] = $zdroje; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['zdroje_ukazka'] = $zdroje_ukazka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['autori'] = $autori; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['slovicka'] = $slovicka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['okruhy'] = $okruhy; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['vsetky_okruhy'] = $vsetky_okruhy; 
        $this->data['vsetky_slova'] = $vsetky_slova; 
        $this->data['pouzivatel'] = $pouzivatel; //prenesie do pohladu zdroje pole pouzivatel ktore obsahuje vsetky udaje z tabulky pouzivatel
        $this->pohlad = 'zdroje';
    }

}
