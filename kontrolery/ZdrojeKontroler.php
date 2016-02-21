<?php

class ZdrojeKontroler extends Kontroler {

    public function spracuj($parametre) {
        //zabezpecenie aby v pohlade zdroje.phtml bol pristup k udajom o pouzivatelovi kvoli vypisu iba JEHO zdrojov
        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel=$spravcaPouzivatelov->vratPouzivatela();
        $pouzivatelovo_id=$pouzivatel['pouzivatel_id'];
        

        $id = 0;
        if (!empty($_GET['zdroj_id'])) {
            $id = $_GET['zdroj_id'];
        }
        $this->hlavicka['titulok'] = 'Moje zdroje';
        //vytvorenie novej instancie ktora nam umozni vypis zdrojov
        $vypisZdrojov = new VypisZdrojov();
        //metoda ktora vrati zdroje pre vypis  zoznamu vsetkych zdrojov pouzivatela, ktory je proihlaseny
        $zdroje = $vypisZdrojov->vratZdrojeSautorom($pouzivatelovo_id);
        //metoda ktora vrati zdroj podla zdroj_id a podla toho zobrazi ukazku
        $zdroje_ukazka = $vypisZdrojov->vratZdrojPodlaZdrojId($id); 

        $this->data['zdroje'] = $zdroje; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['zdroje_ukazka'] = $zdroje_ukazka; //aby sa dalo v pohlade pracovat s premennou zdroje
        $this->data['pouzivatel']=$pouzivatel; //prenesie do pohladu zdroje pole pouzivatel ktore obsahuje vsetky udaje z tabulky pouzivatel
        $this->pohlad = 'zdroje';
        
        
        
        
    }

}
