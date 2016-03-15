<?php

class EditorKontroler extends Kontroler {

    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'Editor zdrojov';

        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();

        $vypisZdrojov = new VypisZdrojov();

        $zdroj = array(
            'zdroj_id' => '',
            'pouzivatel_id' => $pouzivatel['pouzivatel_id'],
            'druh_zdroja' => '',
            'nazov' => '',
            'podnazov' => '',
            'vydanie' => '',
            'miesto_vydania' => '',
            'vydavatelstvo' => '',
            'rok_vydania' => '',
            'isbn' => '',
            'issn' => '',
            'doi' => '',
            'strany' => '',
            'url' => '',
            'datum_aktualizacie' => '',
            'datum_pridania' => '2016-03-13',
            'hodnotenie' => '',
            'poznamka' => '',
        );

        $klucove_slovo = array(
            'klucove_slovo_id' => '',
            'klucove_slovo' => '',
        );

        $zdroj_klucove_slovo = array(
            'zdroj_klucove_slovo_id' => null, //treba nastavit na null
            'zdroj_id' => '',
            'klucove_slovo_id' => '',
        );

        
        $autor = array(
            'autor_id' => '',
            'titul_pred' => '',
            'meno' => '',
            'priezvisko' => '',
            'titul_po' => ''
        );
        
        $autor_zdroj=array(
            'id'=>null,
            'autor_id'=>'',
            'zdroj_id'=>'',
        );

        
        $okruh = array(
            'okruh_id' => '',
            'nazov_okruhu' => '',
        );
        
        $zdroj_okruh=array(
            'zdroj_okruh_id'=>null,
            'zdroj_id'=>'',
            'okruh_id'=>'',
        );

        //ak je odoslany formular
        if ($_POST) {

            /*
             * vlozenie zdroja do tabuky zdroj
             */

            //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
            $kluce_zdroj = array('pouzivatel_id', 'druh_zdroja', 'nazov', 'podnazov',
                'vydanie', 'miesto_vydania', 'vydavatelstvo', 'rok_vydania', 'isbn', 'issn', 'doi',
                'strany', 'url', 'datum_aktualizacie', 'datum_pridania', 'hodnotenie', 'poznamka',);
            //zlucenie klucov s hodnotami s post- a ich priradenie
            $zdroj = array_intersect_key($_POST, array_flip($kluce_zdroj));
            //ulozenie clanku do db
            $vypisZdrojov->ulozZdroj($_POST['zdroj_id'], $zdroj);
            //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
            $idPoslednehoVlozenehoZdroja = $vypisZdrojov->posledneId();

            
            /*
             *vlozenie autora do tabulky autorov 
             *TODO: rozklik na viac autorov
             */
            //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
            $kluce_autor = array('titul_pred','meno','priezvisko','titul_po');
            //zlucenie klucov s hodnotami s post- a ich priradenie
            $autor = array_intersect_key($_POST, array_flip($kluce_autor));
            //ulozenie klucoveho slova do db
            $vypisZdrojov->ulozAutora($_POST['autor_id'], $autor);
            //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
            $idPoslednehoVlozenehoAutora = $vypisZdrojov->posledneId();
            
             /*
             * vlozenie udajov do prepajacej tabulky autor_zdroj
             */
            
            //priradenie hodnoty posledneho vlozeneho zdroja
            $autor_zdroj['autor_id'] = $idPoslednehoVlozenehoAutora;
            //priradenie hodnoty posledneho vlozeneho klucoveho slova
            $autor_zdroj['zdroj_id'] = $idPoslednehoVlozenehoZdroja;
            //ulozenie udajov do databazy, pricom prvy udaj zdroj_klucove_slovo_id je nastavene hore v poli na null
            $vypisZdrojov->ulozZdrojAutor($autor_zdroj);
            
            /*
             * vlozenie klucoveho slova do tabulky klucove_slova
             */

            //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
            $kluce_klucove_slovo = array('klucove_slovo');
            //zlucenie klucov s hodnotami s post- a ich priradenie
            $klucove_slovo = array_intersect_key($_POST, array_flip($kluce_klucove_slovo));
            //ulozenie klucoveho slova do db
            $vypisZdrojov->ulozKlucoveSlovo($_POST['klucove_slovo_id'], $klucove_slovo);
            //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
            $idPoslednehoVlozenehoSlova = $vypisZdrojov->posledneId();

            /*
             * vlozenie udajov do prepajacej tabulky zdroj_klucove_slovo
             */
            
            //priradenie hodnoty posledneho vlozeneho zdroja
            $zdroj_klucove_slovo['zdroj_id'] = $idPoslednehoVlozenehoZdroja;
            //priradenie hodnoty posledneho vlozeneho klucoveho slova
            $zdroj_klucove_slovo['klucove_slovo_id'] = $idPoslednehoVlozenehoSlova;
            //ulozenie udajov do databazy, pricom prvy udaj zdroj_klucove_slovo_id je nastavene hore v poli na null
            $vypisZdrojov->ulozZdrojKlucoveSlovo($zdroj_klucove_slovo);
            
            /*
             * vlozenie okruhu do tabulky okruh
             */
             //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
            $kluce_okruh = array('nazov_okruhu');
            //zlucenie klucov s hodnotami s post- a ich priradenie
            $okruh = array_intersect_key($_POST, array_flip($kluce_okruh));
            //ulozenie klucoveho slova do db
            $vypisZdrojov->ulozOkruh($_POST['okruh_id'], $okruh);
            //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
            $idPoslednehoVlozenehoOkruhu = $vypisZdrojov->posledneId();
            
            /*
             * vlozenie udajov do prepajacej tabulky zdroj_okruh
             */
            
             //priradenie hodnoty posledneho vlozeneho zdroja
            $zdroj_okruh['okruh_id'] = $idPoslednehoVlozenehoOkruhu;
            //priradenie hodnoty posledneho vlozeneho klucoveho slova
            $zdroj_okruh['zdroj_id'] = $idPoslednehoVlozenehoZdroja;
            //ulozenie udajov do databazy, pricom prvy udaj zdroj_klucove_slovo_id je nastavene hore v poli na null
            $vypisZdrojov->ulozZdrojOkruh($zdroj_okruh);

            $this->pridajSpravu('Záznam bol uložený: <br>zdrojid:' . $idPoslednehoVlozenehoZdroja . '<br>klucoveslovo:' . $idPoslednehoVlozenehoSlova);
            $this->presmeruj('zdroje');
        }
        //ak je zadane url clanku k editacii zdroja TODO: po kliknuti na zdroj a tlacidlo editovat: urovit z url localhost//zdroje/124  co je zdroj id
        else if (!empty($parametre[0])) {
            $nacitanyZdroj = $vypisZdrojov->vratZdrojPodlaZdrojId($parametre[0]); //tu parameter podla url
            if ($nacitanyZdroj)
                $zdroj = $nacitanyZdroj;
            else
                $this->pridajSpravu('Záznam nenájdený.');
        }

        $this->data['pouzivatel'] = $pouzivatel;
        $this->data['zdroj'] = $zdroj;
        $this->data['autor'] = $autor;
        $this->data['okruh'] = $okruh;
        $this->data['klucove_slovo'] = $klucove_slovo; //pozor : aj v hlavicke sa spominaju klucove slova
        $this->pohlad = 'editor';
    }

}
