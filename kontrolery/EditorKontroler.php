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
            'datum_aktualizacie' => null,
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

        $autor_zdroj = array(
            'id' => null,
            'autor_id' => '',
            'zdroj_id' => '',
        );


        $okruh = array(
            'okruh_id' => '',
            'nazov_okruhu' => '',
        );

        $zdroj_okruh = array(
            'zdroj_okruh_id' => null,
            'zdroj_id' => '',
            'okruh_id' => '',
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
             * vlozenie autora do tabulky autorov 
             */

            //najdeme id autora ktore sme zadali v editore
            $idAutoraPodlaTitulPred = $vypisZdrojov->vratIdAutoraPodlaTitulPred($_POST['titul_pred']);
            //$this->pridajSpravu(print_r($idAutoraPodlaTitulPred));
            $idAutoraPodlaMena = $vypisZdrojov->vratIdAutoraPodlaMena($_POST['meno']);
          //  $this->pridajSpravu(print_r($idAutoraPodlaTitulPred));
            $idAutoraPodlaPriezviska = $vypisZdrojov->vratIdAutoraPodlaPriezviska($_POST['priezvisko']);
         //   $this->pridajSpravu(print_r($idAutoraPodlaTitulPred));
            $idAutoraPodlaTitulPo = $vypisZdrojov->vratIdAutoraPodlaTitulPo($_POST['titul_po']);
          // $this->pridajSpravu(print_r($idAutoraPodlaTitulPred));

            foreach ($idAutoraPodlaMena as $meno) {
                foreach ($idAutoraPodlaPriezviska as $priezvisko) {
                    foreach ($idAutoraPodlaTitulPo as $po) {
                        foreach ($idAutoraPodlaTitulPred as $pred) {
                            if ((($meno['autor_id'] == $priezvisko['autor_id']) == $pred['autor_id']) == $po['autor_id'])
                               { $jeAutorVdatabaze = 1;
                            $rovnakeid= $meno['autor_id'];
                        }
                            else
                                $jeAutorVdatabaze = 0;
                        }
                    }
                }
            };
            echo "je v databaze:";
            echo $jeAutorVdatabaze;
            echo "<br> IDautora:";
            echo $rovnakeid;

            //ak sa klucove slovo nenaslo  tak sa vytvori nove a ulozi sa do tabulky
            if ($jeAutorVdatabaze == 0) {
                //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                $kluce_autor = array('titul_pred', 'meno', 'priezvisko', 'titul_po');
                //zlucenie klucov s hodnotami s post- a ich priradenie
                $autor = array_intersect_key($_POST, array_flip($kluce_autor));
                //ulozenie klucoveho slova do db
                $vypisZdrojov->ulozAutora($_POST['autor_id'], $autor);
                //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                $idPoslednehoVlozenehoAutora = $vypisZdrojov->posledneId();
            } else {
                $idPoslednehoVlozenehoAutora = $idAutoraPodlaMena['autor_id'];
                echo"id posledneho vlozeneho autorA:";
                echo $idPoslednehoVlozenehoAutora;
            }





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
             * ulozenie klucoveho slova - ale iba ak uz nieje ulozene
             * ak je ulozene tak sa vrati len jeho ID pre dalsie pouzitie ak sa bude ukladat do tabulky
             * zdroj_klucove_slovo
             */

            //najdeme id klucoveho slova ktore sme zadali v editore
            $idSlova = $vypisZdrojov->vratIdKlucovehoSlova($_POST['klucove_slovo']);
            //ak sa klucove slovo nenaslo  tak sa vytvori nove a ulozi sa do tabulky
            if ($idSlova['klucove_slovo_id'] == '') {
                //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                $kluce_klucove_slovo = array('klucove_slovo');
                //zlucenie klucov s hodnotami s post- a ich priradenie
                $klucove_slovo = array_intersect_key($_POST, array_flip($kluce_klucove_slovo));
                //ulozenie klucoveho slova do db

                $vypisZdrojov->ulozKlucoveSlovo($_POST['klucove_slovo_id'], $klucove_slovo);
                //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                $idPoslednehoVlozenehoSlova = $vypisZdrojov->posledneId();
                //ak sa klucove slovo naslo tak sa iba ulozi jeho id pre dalsie pouzitie na vkladanie do medzitabulky
            } else {
                $idPoslednehoVlozenehoSlova = $idSlova['klucove_slovo_id'];
                echo $idPoslednehoVlozenehoSlova;
            }

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


            //najdeme id ookruhu ktore sme zadali v editore
            $idOkruhu = $vypisZdrojov->vratIdOkruhu($_POST['nazov_okruhu']);
            //ak sa klucove slovo nenaslo  tak sa vytvori nove a ulozi sa do tabulky
            if ($idOkruhu['okruh_id'] == '') {
                //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                $kluce_okruh = array('nazov_okruhu');
                //zlucenie klucov s hodnotami s post- a ich priradenie
                $okruh = array_intersect_key($_POST, array_flip($kluce_okruh));
                //ulozenie klucoveho slova do db
                $vypisZdrojov->ulozOkruh($_POST['okruh_id'], $okruh);
                //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                $idPoslednehoVlozenehoOkruhu = $vypisZdrojov->posledneId();
                //ak sa okruh nasiel tak sa iba ulozi jeho id pre dalsie pouzitie na vkladanie do medzitabulky
            } else {
                $idPoslednehoVlozenehoOkruhu = $idOkruhu['okruh_id'];
                echo $idPoslednehoVlozenehoOkruhu;
            }


            /*
             * vlozenie udajov do prepajacej tabulky zdroj_okruh
             */

            //priradenie hodnoty posledneho vlozeneho zdroja
            $zdroj_okruh['okruh_id'] = $idPoslednehoVlozenehoOkruhu;
            //priradenie hodnoty posledneho vlozeneho klucoveho slova
            $zdroj_okruh['zdroj_id'] = $idPoslednehoVlozenehoZdroja;
            //ulozenie udajov do databazy, pricom prvy udaj zdroj_klucove_slovo_id je nastavene hore v poli na null
            $vypisZdrojov->ulozZdrojOkruh($zdroj_okruh);

            $this->pridajSpravu('Záznam bol uložený: <br>zdrojid:' . $idPoslednehoVlozenehoZdroja . '<br>klucoveslovo:' . $idPoslednehoVlozenehoSlova
                    . '<br>' . print_r($autor));
            $this->presmeruj('zdroje');
        }
        //ak je zadane url clanku pre zmazanie
        if (!empty($parametre[1]) && $parametre[1] == 'odstranit') {
            $vypisZdrojov->odstranZdroj($parametre[0]);
            $this->pridajSpravu('Článek byl úspěšně odstraněn');
            $this->presmeruj('zdroje');
        }
        //ak je zadane url clanku k editacii zdroja TODO: po kliknuti na zdroj a tlacidlo editovat: urovit z url localhost//zdroje/124  co je zdroj id
        else if (!empty($parametre[0])) {
            $nacitanyZdroj = $vypisZdrojov->vratZdrojPodlaZdrojId($parametre[0]); //tu parameter podla url
            $nacitatAutorov = $vypisZdrojov->vratAutorov($parametre[0]); // TODO
            $nacitatSlova = $vypisZdrojov->vratKlucoveSlova($parametre[0]); //TODO
            $nacitatOkruhy = $vypisZdrojov->vratOkruhy($parametre[0]); //TODO [0] tu je len docasne, treba vypisat vsetko co pole ma!!!

            if ($nacitanyZdroj) {
                $zdroj = $nacitanyZdroj;
                if ($nacitatAutorov) {
                    $autor = $nacitatAutorov[0];
                }
                if ($nacitatSlova) {
                    $klucove_slovo = $nacitatSlova[0];
                }
                if ($nacitatOkruhy) {
                    $okruh = $nacitatOkruhy[0];
                }
            } else
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
