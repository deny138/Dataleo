<?php

class EditorKontroler extends Kontroler {

    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'Editor zdrojov';

        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();

        if (!$pouzivatel)
            $this->presmeruj('prihlasenie');

        $vypisZdrojov = new VypisZdrojov();

        /*
         * ---------------------------------------------------------------------
         * inicializacia poli
         * ---------------------------------------------------------------------
         */

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
            'datum_pridania' => date("Y-m-d"),
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
            'id' => '',
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


        /*
         * ---------------------------------------------------------------------------------------------
         * ak je odoslany formular vykonaju sa vsetky operacie, ak nie je, zobrazi sa formular editoru
         * ---------------------------------------------------------------------------------------------
         */


        //ak je odoslany formular
        if ($_POST) {

            /*
             * ---------------------------------------------------------------------
             * vlozenie  ZDROJA do tabulky zdroj
             * ---------------------------------------------------------------------
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
            if ($idPoslednehoVlozenehoZdroja == 0)
                $idPoslednehoVlozenehoZdroja = $_POST['zdroj_id'];

            $this->pridajSpravu('ulozil sa zdroj pod id= ' . $idPoslednehoVlozenehoZdroja);
            echo 'ulozil sa zdroj pod id=' . $idPoslednehoVlozenehoZdroja;
            echo 'ulozil sa zdroj pod id=' . $_POST['zdroj_id'];


            /*
             * ---------------------------------------------------------------------
             * vlozenie  SUBORU na server
             * ulozenie nahraneho suboru na server pod menom ktore sa rovna id posledneho zdroja ktory bol vlozeny
             * to znamena ze sa ulozi pod zdroj_id aktualneho zdroja ktory ukladame
             * toto ulozenie sa musi vykonat az po ulozeni zdroja do databazy aby sme vedeli jeho zdroj_id
             * ---------------------------------------------------------------------
             */


            //ak do pohladu editor pouzivatel  nahral subor FILE, nastavi sa premenna file na tento subor, je to pole
            if (isset($_FILES['file'])) {
                $file = $_FILES['file'];

                //vlastnosti pridaneho suboru, ulozenie do premennych
                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
                $file_size = $file['size'];
                $file_error = $file['error'];

                //rozdelenie nazvu pridaneho suboru
                $file_ext = explode('.', $file_name);
                $file_ext = strtolower(end($file_ext));
                //ocakavane pripony
                $allowed = array('txt', 'jpg', 'pdf');

                //ak su pripony ocakavane zhodne s tou ktoru pouzivatel pridal, a neprejavila sa chyba, ani nepridal vacsi subor ako mal
                //tak sa vytvori novy nazov suboru ktory je zhodny s premennou zdroj_id a ulozi sa na server do priecinka uploads 
                if (in_array($file_ext, $allowed)) {
                    if ($file_error === 0) {
                        if ($file_size <= 2097152) {

                            $file_name_new = $idPoslednehoVlozenehoZdroja . '.' . $file_ext;
                            $file_destination = 'uploads/' . $file_name_new;
                            if (move_uploaded_file($file_tmp, $file_destination)) {
                                
                            }
                        }
                    }
                }
            }

           /*
             * ------------------------------------------------------------------
             * vlozenie AUTORA do tabulky autor + vlozenie do prepajacej tabulky
             * ------------------------------------------------------------------
             */

            $idAutorovPodlaUdajov = $vypisZdrojov->vratIdAutora($_POST['titul_pred'], $_POST['meno'], $_POST['priezvisko'], $_POST['titul_po']);
            $idAutorovPodlaUdajov = $idAutorovPodlaUdajov[0];
            if (!$idAutorovPodlaUdajov) {
                //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                $kluce_autor = array('titul_pred', 'meno', 'priezvisko', 'titul_po');
                //zlucenie klucov s hodnotami s post- a ich priradenie
                $autor = array_intersect_key($_POST, array_flip($kluce_autor));
                //ulozenie autora do db
                $vypisZdrojov->ulozAutora('', $autor); //tu sa predpoklada ze autor este neexistuje
                //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                $idPoslednehoVlozenehoAutora = $vypisZdrojov->posledneId();


                $this->pridajSpravu('<br>ulozil sa autor ktory este neexistoval pod id= ' . $idPoslednehoVlozenehoAutora);
                echo '<br>ulozil sa autor ktory este neexistoval pod id=' . $idPoslednehoVlozenehoAutora;
            } else {
                $idPoslednehoVlozenehoAutora = $idAutorovPodlaUdajov;
                echo '<br>autor uz existoval takze sa neukladal iba sa ulozilo jeho id=' . $idPoslednehoVlozenehoAutora;
                $this->pridajSpravu('<br>autor uz existoval takze sa neukladal iba sa ulozilo jeho id== ' . $idPoslednehoVlozenehoAutora);
            };
            /*
             * vlozenie udajov do prepajacej tabulky autor_zdroj
             */

            $idPodlaUdajov = $vypisZdrojov->vratIdZdrojAutorPodlaUdajov($idPoslednehoVlozenehoAutora, $idPoslednehoVlozenehoZdroja);

            if (!$idPodlaUdajov) {
                $idPodlaUdajov = '';
                $this->pridajSpravu('id z prepajacej tabulky sa nenaslo, malo by sa ulozit ako prazdny retazec=' . $idPodlaUdajov);

                $kluce_autor_zdroj = array('autor_id', 'zdroj_id');
                //zlucenie klucov s hodnotami s post- a ich priradenie
                $pomocne = array('autor_id' => $idPoslednehoVlozenehoAutora, 'zdroj_id' => $idPoslednehoVlozenehoZdroja);

                $autor_zdroj = array_intersect_key($pomocne, array_flip($kluce_autor_zdroj));
                //ulozenie autora do db
                $vypisZdrojov->ulozZdrojAutor($idPodlaUdajov, $autor_zdroj);
            }




            /*
             * ------------------------------------------------------------------
             * vlozenie KLUCOVEHO SLOVA do tabulky klucove slovo + vlozenie do prepajacej tabulky
             * ------------------------------------------------------------------
             */

            //najdeme id klucoveho slova ktore sme zadali v editore
            $idSlova = $vypisZdrojov->vratIdKlucovehoSlova($_POST['klucove_slovo']);
            //ak sa klucove slovo nenaslo  tak sa vytvori nove a ulozi sa do tabulky
            if (!$idSlova) {
                //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                $kluce_klucove_slovo = array('klucove_slovo');
                //zlucenie klucov s hodnotami s post- a ich priradenie
                $klucove_slovo = array_intersect_key($_POST, array_flip($kluce_klucove_slovo));
                //ulozenie klucoveho slova do db

                $vypisZdrojov->ulozKlucoveSlovo('', $klucove_slovo);
                //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                $idPoslednehoVlozenehoSlova = $vypisZdrojov->posledneId();
                //ak sa klucove slovo naslo tak sa iba ulozi jeho id pre dalsie pouzitie na vkladanie do medzitabulky

                echo '<br>slovo este neexistovalo takze sa ulozil pod id=' . $idPoslednehoVlozenehoSlova;
                $this->pridajSpravu('<br>slovo este neexistovalo takze sa ulozil pod id= ' . $idPoslednehoVlozenehoSlova);
            } else {
                $idPoslednehoVlozenehoSlova = $idSlova['klucove_slovo_id'];
                echo $idPoslednehoVlozenehoSlova;

                echo '<br>slovo uz existovalo takze sa neukladalo vratilo sa len jeho id' . $idPoslednehoVlozenehoSlova;
                $this->pridajSpravu('<br>slovo uz existovalo takze sa neukladalo vratilo sa len jeho id ' . $idPoslednehoVlozenehoSlova);
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
             * ------------------------------------------------------------------
             * vlozenie OKRUHU do tabulky okruh + vlozenie do prepajacej tabulky
             * ------------------------------------------------------------------
             */


            for ($i = 0; $i <= 10; $i++) {
                //ak nieje zadany nazov_okruhu_$i -> nerobi sa nic,ak je zadany treba ho ulozit, ale iba v tom pripade ak uz v tabulke nieje
                if ($_POST['nazov_okruhu_' . $i]) {
                    //najdeme id okruhu ktore sme zadali v editore
                    $idOkruhu = $vypisZdrojov->vratIdOkruhu($_POST['nazov_okruhu_' . $i]);
                    //ak sa klucove slovo nenaslo  tak sa vytvori nove a ulozi sa do tabulky
                    if (!$idOkruhu) {
                        echo "<br> id okruhu sa nenaslo v tabulke <br>";
                        //vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                        $kluce_okruh = array('nazov_okruhu_' . $i);
                        //zlucenie klucov s hodnotami s post- a ich priradenie
                        $okruh = array_intersect_key($_POST, array_flip($kluce_okruh));

                        echo "<br> POST: ";
                        print_r($_POST);

                        //nahradenie kluca v poli  nazov_okruhu_cislo za nazov_okruhu, aby to sedelo pri ukladani do DB
                        $okruh['nazov_okruhu'] = $okruh['nazov_okruhu_' . $i];
                        unset($okruh['nazov_okruhu_' . $i]);

                        echo "<br> okruh po uprave= " . $okruh['nazov_okruhu'];

                        //ulozenie klucoveho slova do db
                        $vypisZdrojov->ulozOkruh('', $okruh);
                        //ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                        $idPoslednehoVlozenehoOkruhu = $vypisZdrojov->posledneId();

                        echo '<br>okruh este neexistoval takze sa ulozil pod id=' . $idPoslednehoVlozenehoOkruhu;
                        $this->pridajSpravu('<br>okruh este neexistoval takze sa ulozil pod id= ' . $idPoslednehoVlozenehoOkruhu);
                    } else {
                        //ak sa okruh nasiel tak sa iba ulozi jeho id pre dalsie pouzitie na vkladanie do medzitabulky
                        $idPoslednehoVlozenehoOkruhu = $idOkruhu['okruh_id'];
                        echo $idPoslednehoVlozenehoOkruhu;

                        echo '<br>okruh uz existoval takze sa neukladal vratilo sa len jeho id' . $idPoslednehoVlozenehoOkruhu;
                        $this->pridajSpravu('<br>okruh uz existoval takze sa neukladal vratilo sa len jeho id ' . $idPoslednehoVlozenehoOkruhu);
                    }

                    /*
                     * vlozenie udajov do prepajacej tabulky zdroj_okruh
                     */

                    //ak uz okruh v prepajacej tabulke je tak sa uz neuklada znova, ak nieje tak sa ulozi- kvoli duplicitnym vypisom
                    $jeOkruhvPrepajacej = $vypisZdrojov->vratIdZdrojOkruhPodlaUdajov($idPoslednehoVlozenehoZdroja, $idPoslednehoVlozenehoOkruhu);
                    if (!$jeOkruhvPrepajacej) {
                        $this->pridajSpravu("nieje v prepajacej:<?php print_r($jeOkruhvPrepajacej);?>");
                        print_r($jeOkruhvPrepajacej);

                        //priradenie hodnoty posledneho vlozeneho zdroja
                        $zdroj_okruh['okruh_id'] = $idPoslednehoVlozenehoOkruhu;
                        //priradenie hodnoty posledneho vlozeneho klucoveho slova
                        $zdroj_okruh['zdroj_id'] = $idPoslednehoVlozenehoZdroja;
                        //ulozenie udajov do databazy, pricom prvy udaj zdroj_klucove_slovo_id je nastavene hore v poli na null
                        $vypisZdrojov->ulozZdrojOkruh($zdroj_okruh);
                    } else
                        $this->pridajSpravu("je v prepajacej:<?php print_r($jeOkruhvPrepajacej);?>");
                }
            }

            $this->presmeruj('zdroje');
        }


        /*
         * ---------------------------------------------------------------------
         * ostatne veci
         * ---------------------------------------------------------------------
         */

        //ak je zadane url clanku pre zmazanie
        if (!empty($parametre[1]) && $parametre[1] == 'odstranit') {
            $vypisZdrojov->odstranZdroj($parametre[0]);
            $this->pridajSpravu('Zdroj bol úspešne odstránený.');
            $this->presmeruj('zdroje');
        }
        //ak je zadane url clanku k editacii zdroja 
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
                    $okruh = $nacitatOkruhy;
                }
            } else
                $this->pridajSpravu('Záznam nenájdený.');
        }

        $this->data['pouzivatel'] = $pouzivatel;
        $this->data['zdroj'] = $zdroj;
        $this->data['autor'] = $autor;
        $this->data['autor_zdroj'] = $autor_zdroj;
        $this->data['okruh'] = $okruh;
        $this->data['klucove_slovo'] = $klucove_slovo; //pozor : aj v hlavicke sa spominaju klucove slova
        $this->pohlad = 'editor';
    }

}
