

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
            'prispevok' => '',
            'zodpovednost' => '',
            'vydanie' => '',
            'miesto_vydania' => '',
            'vydavatelstvo' => '',
            'rok_vydania' => '',
            'isbn' => '',
            'issn' => '',
            'doi' => '',
            'strany' => '',
            'od' => '',
            'do' => '',
            'url' => '',
            'datum_vydania' => date("Y-m-d"),
            'datum_aktualizacie' => date("Y-m-d"),
            'datum_pridania' => date("Y-m-d"),
            'nosic'=>'',
            'hodnotenie' => '',
            'poznamka' => '',
            'citacia' => '',
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
            $kluce_zdroj = array('pouzivatel_id', 'druh_zdroja', 'nazov', 'podnazov', 'prispevok','zodpovednost',
                'vydanie', 'miesto_vydania', 'vydavatelstvo', 'rok_vydania', 'isbn', 'issn', 'doi',
                'strany', 'od', 'do', 'url','datum_vydania','nosic', 'datum_aktualizacie', 'datum_pridania', 'hodnotenie', 'poznamka', 'citacia');
            //zlucenie klucov s hodnotami s post- a ich priradenie
            $zdroj = array_intersect_key($_POST, array_flip($kluce_zdroj));

            /*
             * tu je vytvorenie / vygenerovanie citacie podla zadanych udajov
             */
            $druh = $_POST['druh_zdroja'];
            $citacia = '';
            //ltrim odstrani medzery z konca
            //chop zo zaciatku
            // trim aj zo zaciatku aj  z konca
            //-----------------------kniha----------------------------

            if ($_POST['druh_zdroja'] == 'kniha') {

                // priezvisko prveho autora
                if (isset($_POST['priezvisko_0'])) {
                    $priezvisko = $_POST['priezvisko_0'];
                    $priezvisko = trim($priezvisko);
                    if ($priezvisko != "") {
                        $priezvisko = (StrToUpper($priezvisko)) . ', ';
                        $citacia = $citacia . $priezvisko;
                        echo $priezvisko;
                    }
                }
                //meno prveho autora
                if (isset($_POST['meno_0'])) {
                    $meno = $_POST['meno_0'];
                    $meno = trim($meno);
                    if ($meno != "") {
                        $meno = StrToUpper($meno[0]) . '., ';
                        $citacia = $citacia . $meno;
                        echo $meno;
                    }
                }

                //priezvisko druheho autora
                if (isset($_POST['priezvisko_1'])) {
                    $priezvisko1 = $_POST['priezvisko_1'];
                    $priezvisko1 = trim($priezvisko1);
                    if ($priezvisko1 != "") {
                        $priezvisko1 = (StrToUpper($priezvisko1)) . ', ';
                        $citacia = $citacia . $priezvisko1;
                        echo $priezvisko1;
                    }
                }

                //meno druheeho autora
                if (isset($_POST['meno_1'])) {
                    $meno1 = $_POST['meno_1'];
                    $meno1 = trim($meno1);
                    if ($meno1 != "") {
                        $meno1 = StrToUpper($meno1[0]) . '., ';
                        $citacia = $citacia . $meno1;
                        echo $meno1;
                    }
                }

                //nazov zdroja
                if (isset($_POST['nazov'])) {
                    $nazov = $_POST['nazov'];
                    $nazov = trim($nazov);
                    if ($nazov != "") {
                        if (isset($_POST['podnazov'])) {
                            $podnazov = $_POST['podnazov'];
                            $podnazov = trim($podnazov);
                            if ($podnazov != "") {
                                $nazov = ucfirst($nazov) . ': ';
                            } else {
                                $nazov = ucfirst($nazov) . '. ';
                            }
                        } else {
                            $nazov = ucfirst($nazov) . '. ';
                        }
                        $citacia = $citacia . $nazov;
                        echo $nazov;
                    }
                }

                //podnazov zdroja
                if (isset($_POST['podnazov'])) {
                    $podnazov = $_POST['podnazov'];
                    $podnazov = trim($podnazov);
                    if ($podnazov != "") {
                        $podnazov = ucfirst(chop(ltrim($podnazov))) . '. ';
                        $citacia = $citacia . $podnazov;
                        echo $podnazov;
                    }
                }

                //vydanie zdroja
                if (isset($_POST['vydanie'])) {
                    $vydanie = $_POST['vydanie'];
                    $vydanie = trim($vydanie);
                    if ($vydanie != "") {
                        $vydanie = $vydanie . '. vydanie. ';
                        $citacia = $citacia . $vydanie;
                        echo $vydanie;
                    }
                }

                //miesto vydania
                if (isset($_POST['miesto_vydania'])) {
                    $miesto = $_POST['miesto_vydania'];
                    $miesto = trim($miesto);
                    if ($miesto != "") {
                        if (isset($_POST['vydavatelstvo'])) {
                            $vydavatelstvo = $_POST['vydavatelstvo'];
                            $vydavatelstvo = trim($vydavatelstvo);
                            if ($vydavatelstvo != "") {

                                $miesto = ucfirst($miesto) . ': ';
                            } else {
                                if (isset($_POST['rok_vydania'])) {
                                    $rok = $_POST['rok_vydania'];
                                    $rok = trim($rok);
                                    if ($rok != "") {

                                        $miesto = ucfirst($miesto) . ': ';
                                    } else {

                                        $miesto = ucfirst($miesto) . '. ';
                                    }
                                }
                            }
                        } else {
                            if (isset($_POST['rok_vydania'])) {
                                $rok = $_POST['rok_vydania'];
                                $rok = trim($rok);
                                if ($rok != "") {
                                    $miesto = ucfirst($miesto) . ': ';
                                } else {
                                    $miesto = ucfirst($miesto) . '. ';
                                }
                            }
                        }
                        $citacia = $citacia . $miesto;
                        echo $miesto;
                    }
                }



                //vydavatelstvo
                if (isset($_POST['vydavatelstvo'])) {
                    $vydavatelstvo = $_POST['vydavatelstvo'];
                    $vydavatelstvo = trim($vydavatelstvo);
                    if ($vydavatelstvo != "") {
                        if (isset($_POST['rok_vydania'])) {
                            $rok = $_POST['rok_vydania'];
                            $rok = trim($rok);
                            if ($rok != "") {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . ', ';
                            } else {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                            }
                        } else {
                            $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                        }
                        $citacia = $citacia . $vydavatelstvo;
                        echo $vydavatelstvo;
                    }
                }



                //rok vydania
                if (isset($_POST['rok_vydania'])) {
                    $rok = $_POST['rok_vydania'];
                    $rok = trim($rok);
                    if ($rok != "") {
                        $rok = $rok . '. ';
                        $citacia = $citacia . $rok;
                        echo $rok;
                    }
                }

                //strany
                if (isset($_POST['strany'])) {
                    $strany = $_POST['strany'];
                    $strany = trim($strany);
                    if ($strany != "") {
                        $strany = $strany . ' s. ';
                        $citacia = $citacia . $strany;
                        echo $strany;
                    }
                }

                //strany
                if (isset($_POST['isbn'])) {
                    $isbn = $_POST['isbn'];
                    $isbn = trim($isbn);
                    if ($isbn != "") {
                        $isbn = 'ISBN ' . $isbn . '. ';
                        $citacia = $citacia . $isbn;
                        echo $isbn;
                    }
                }
            }
            
            //-----------------------elektronicka kniha----------------------------

            if ($_POST['druh_zdroja'] == 'elektronicka_kniha') {

                // priezvisko prveho autora
                if (isset($_POST['priezvisko_0'])) {
                    $priezvisko = $_POST['priezvisko_0'];
                    $priezvisko = trim($priezvisko);
                    if ($priezvisko != "") {
                        $priezvisko = (StrToUpper($priezvisko)) . ', ';
                        $citacia = $citacia . $priezvisko;
                        echo $priezvisko;
                    }
                }
                //meno prveho autora
                if (isset($_POST['meno_0'])) {
                    $meno = $_POST['meno_0'];
                    $meno = trim($meno);
                    if ($meno != "") {
                        $meno = StrToUpper($meno[0]) . '., ';
                        $citacia = $citacia . $meno;
                        echo $meno;
                    }
                }

               

                //nazov zdroja
                if (isset($_POST['nazov'])) {
                    $nazov = $_POST['nazov'];
                    $nazov = trim($nazov);
                    if ($nazov != "") {
                        if (isset($_POST['podnazov'])) {
                            $podnazov = $_POST['podnazov'];
                            $podnazov = trim($podnazov);
                            if ($podnazov != "") {
                                $nazov = ucfirst($nazov) . ': ';
                            } else {
                                $nazov = ucfirst($nazov) . '. ';
                            }
                        } else {
                            $nazov = ucfirst($nazov) . '. ';
                        }
                        $citacia = $citacia . $nazov;
                        echo $nazov;
                    }
                }

                //podnazov zdroja
                if (isset($_POST['podnazov'])) {
                    $podnazov = $_POST['podnazov'];
                    $podnazov = trim($podnazov);
                    if ($podnazov != "") {
                        $podnazov = ucfirst(chop(ltrim($podnazov))) . '. ';
                        $citacia = $citacia . $podnazov;
                        echo $podnazov;
                    }
                }
                //nosic
                if (isset($_POST['nosic'])) {
                    $nosic = $_POST['nosic'];
                    $nosic = trim($nosic);
                    if ($nosic != "") {
                       
                        $nosic = '[' .strtolower($nosic) . ']. ';
                        $citacia = $citacia . $nosic;
                        echo $nosic;
                    }
                }

                //vydanie zdroja
                if (isset($_POST['vydanie'])) {
                    $vydanie = $_POST['vydanie'];
                    $vydanie = trim($vydanie);
                    if ($vydanie != "") {
                        $vydanie = $vydanie . '. vydanie. ';
                        $citacia = $citacia . $vydanie;
                        echo $vydanie;
                    }
                }

                //miesto vydania
                if (isset($_POST['miesto_vydania'])) {
                    $miesto = $_POST['miesto_vydania'];
                    $miesto = trim($miesto);
                    if ($miesto != "") {
                        if (isset($_POST['vydavatelstvo'])) {
                            $vydavatelstvo = $_POST['vydavatelstvo'];
                            $vydavatelstvo = trim($vydavatelstvo);
                            if ($vydavatelstvo != "") {

                                $miesto = ucfirst($miesto) . ': ';
                            } else {
                                if (isset($_POST['datum_vydania'])) {
                                    $rok = $_POST['datum_vydania'];
                                    $rok = trim($rok);
                                    if ($rok != "") {

                                        $miesto = ucfirst($miesto) . ': ';
                                    } else {

                                        $miesto = ucfirst($miesto) . '. ';
                                    }
                                }
                            }
                        } else {
                            if (isset($_POST['datum_vydania'])) {
                                $rok = $_POST['datum_vydania'];
                                $rok = trim($rok);
                                if ($rok != "") {
                                    $miesto = ucfirst($miesto) . ': ';
                                } else {
                                    $miesto = ucfirst($miesto) . '. ';
                                }
                            }
                        }
                        $citacia = $citacia . $miesto;
                        echo $miesto;
                    }
                }



                //vydavatelstvo
                if (isset($_POST['vydavatelstvo'])) {
                    $vydavatelstvo = $_POST['vydavatelstvo'];
                    $vydavatelstvo = trim($vydavatelstvo);
                    if ($vydavatelstvo != "") {
                        if (isset($_POST['datum_vydania'])) {
                            $rok = $_POST['datum_vydania'];
                            $rok = trim($rok);
                            if ($rok != "") {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . ', ';
                            } else {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                            }
                        } else {
                            $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                        }
                        $citacia = $citacia . $vydavatelstvo;
                        echo $vydavatelstvo;
                    }
                }



                //datum vydania
                if (isset($_POST['datum_vydania'])) {
                    $datum_vydania = $_POST['datum_vydania'];
                    $datum_vydania = trim($datum_vydania);
                    if ($datum_vydania != "") {
                        $datum_vydania = $datum_vydania . '. ';
                        $citacia = $citacia . $datum_vydania;
                        echo $rok;
                    }
                }
                
               

                //datum aktualziacia
                if (isset($_POST['datum_aktualizacie'])) {
                    $datum_aktualizacie = $_POST['datum_aktualizacie'];
                    $datum_aktualizacie = trim($datum_aktualizacie);
                    if ($datum_aktualizacie != "") {
                        $datum_aktualizacie = $datum_aktualizacie . '. ';
                        $citacia = $citacia . $datum_aktualizacie;
                        echo $datum_aktualizacie;
                    }
                }
                //datum citvania
                if (isset($_POST['datum_citovania'])) {
                    $datum_citovania = $_POST['datum_citovania'];
                    $datum_citovania = trim($datum_citovania);
                    if ($datum_citovania != "") {
                        $datum_citovania = '[cit. '.$datum_citovania . ']. ';
                        $citacia = $citacia . $datum_citovania;
                        echo $datum_citovania;
                    }
                }

              
                
                //url
                if (isset($_POST['url'])) {
                    $url = $_POST['url'];
                    $url = trim($url);
                    if ($url != "") {
                        $url = 'Dostupné na internete: < ' .$url. ' >. ';
                        $citacia = $citacia.$url;
                        echo $url;
                    }
                }
            }



            //-----------------------kniha cast----------------------------


            if ($_POST['druh_zdroja'] == 'cast_knihy') {

                // priezvisko prveho autora
                if (isset($_POST['priezvisko_0'])) {
                    $priezvisko = $_POST['priezvisko_0'];
                    $priezvisko = trim($priezvisko);
                    if ($priezvisko != "") {
                        $priezvisko = (StrToUpper($priezvisko)) . ', ';
                        $citacia = $citacia . $priezvisko;
                        echo $priezvisko;
                    }
                }
                //meno prveho autora
                if (isset($_POST['meno_0'])) {
                    $meno = $_POST['meno_0'];
                    $meno = trim($meno);
                    if ($meno != "") {
                        $meno = StrToUpper($meno[0]) . '., ';
                        $citacia = $citacia . $meno;
                        echo $meno;
                    }
                }

                //priezvisko druheho autora
                if (isset($_POST['priezvisko_1'])) {
                    $priezvisko1 = $_POST['priezvisko_1'];
                    $priezvisko1 = trim($priezvisko1);
                    if ($priezvisko1 != "") {
                        $priezvisko1 = (StrToUpper($priezvisko1)) . ', ';
                        $citacia = $citacia . $priezvisko1;
                        echo $priezvisko1;
                    }
                }

                //meno druheeho autora
                if (isset($_POST['meno_1'])) {
                    $meno1 = $_POST['meno_1'];
                    $meno1 = trim($meno1);
                    if ($meno1 != "") {
                        $meno1 = StrToUpper($meno1[0]) . '., ';
                        $citacia = $citacia . $meno1;
                        echo $meno1;
                    }
                }

                //nazov zdroja
                if (isset($_POST['nazov'])) {
                    $nazov = $_POST['nazov'];
                    $nazov = trim($nazov);
                    if ($nazov != "") {
                        if (isset($_POST['podnazov'])) {
                            $podnazov = $_POST['podnazov'];
                            $podnazov = trim($podnazov);
                            if ($podnazov != "") {
                                $nazov = ucfirst($nazov) . ': ';
                            } else {
                                $nazov = ucfirst($nazov) . '. ';
                            }
                        } else {
                            $nazov = ucfirst($nazov) . '. ';
                        }
                        $citacia = $citacia . $nazov;
                        echo $nazov;
                    }
                }

                //podnazov zdroja
                if (isset($_POST['podnazov'])) {
                    $podnazov = $_POST['podnazov'];
                    $podnazov = trim($podnazov);
                    if ($podnazov != "") {
                        $podnazov = ucfirst(chop(ltrim($podnazov))) . '. ';
                        $citacia = $citacia . $podnazov;
                        echo $podnazov;
                    }
                }

                //vydanie zdroja
                if (isset($_POST['vydanie'])) {
                    $vydanie = $_POST['vydanie'];
                    $vydanie = trim($vydanie);
                    if ($vydanie != "") {
                        $vydanie = $vydanie . '. vydanie. ';
                        $citacia = $citacia . $vydanie;
                        echo $vydanie;
                    }
                }

                //miesto vydania
                if (isset($_POST['miesto_vydania'])) {
                    $miesto = $_POST['miesto_vydania'];
                    $miesto = trim($miesto);
                    if ($miesto != "") {
                        if (isset($_POST['vydavatelstvo'])) {
                            $vydavatelstvo = $_POST['vydavatelstvo'];
                            $vydavatelstvo = trim($vydavatelstvo);
                            if ($vydavatelstvo != "") {

                                $miesto = ucfirst($miesto) . ': ';
                            } else {
                                if (isset($_POST['rok_vydania'])) {
                                    $rok = $_POST['rok_vydania'];
                                    $rok = trim($rok);
                                    if ($rok != "") {

                                        $miesto = ucfirst($miesto) . ': ';
                                    } else {

                                        $miesto = ucfirst($miesto) . '. ';
                                    }
                                }
                            }
                        } else {
                            if (isset($_POST['rok_vydania'])) {
                                $rok = $_POST['rok_vydania'];
                                $rok = trim($rok);
                                if ($rok != "") {
                                    $miesto = ucfirst($miesto) . ': ';
                                } else {
                                    $miesto = ucfirst($miesto) . '. ';
                                }
                            }
                        }
                        $citacia = $citacia . $miesto;
                        echo $miesto;
                    }
                }



                //vydavatelstvo
                if (isset($_POST['vydavatelstvo'])) {
                    $vydavatelstvo = $_POST['vydavatelstvo'];
                    $vydavatelstvo = trim($vydavatelstvo);
                    if ($vydavatelstvo != "") {
                        if (isset($_POST['rok_vydania'])) {
                            $rok = $_POST['rok_vydania'];
                            $rok = trim($rok);
                            if ($rok != "") {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . ', ';
                            } else {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                            }
                        } else {
                            $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                        }
                        $citacia = $citacia . $vydavatelstvo;
                        echo $vydavatelstvo;
                    }
                }



                //rok vydania
                if (isset($_POST['rok_vydania'])) {
                    $rok = $_POST['rok_vydania'];
                    $rok = trim($rok);
                    if ($rok != "") {
                        $rok = $rok . '. ';
                        $citacia = $citacia . $rok;
                        echo $rok;
                    }
                }

                //strany
                if (isset($_POST['isbn'])) {
                    $isbn = $_POST['isbn'];
                    $isbn = trim($isbn);
                    if ($isbn != "") {
                        $isbn = 'ISBN ' . $isbn . '. ';
                        $citacia = $citacia . $isbn;
                        echo $isbn;
                    }
                }

                //rozsah
                if ((isset($_POST['od'])) && (isset($_POST['do']))) {
                    $od = $_POST['od'];
                    $do = $_POST['do'];
                    $od = trim($od);
                    $do = trim($do);
                    if (($od != "") && ($do != "")) {
                        $rozsah = $od . '-' . $do . '. ';
                        $citacia = $citacia . $rozsah;
                        echo $rozsah;
                    }
                }
            }
            
            //-----------------------elektronicka kniha cast----------------------------


            if ($_POST['druh_zdroja'] == 'cast_elektronickej_knihy') {

                // priezvisko prveho autora
                if (isset($_POST['priezvisko_0'])) {
                    $priezvisko = $_POST['priezvisko_0'];
                    $priezvisko = trim($priezvisko);
                    if ($priezvisko != "") {
                        $priezvisko = (StrToUpper($priezvisko)) . ', ';
                        $citacia = $citacia . $priezvisko;
                        echo $priezvisko;
                    }
                }
                //meno prveho autora
                if (isset($_POST['meno_0'])) {
                    $meno = $_POST['meno_0'];
                    $meno = trim($meno);
                    if ($meno != "") {
                        $meno = StrToUpper($meno[0]) . '., ';
                        $citacia = $citacia . $meno;
                        echo $meno;
                    }
                }

                

                //nazov zdroja
                if (isset($_POST['nazov'])) {
                    $nazov = $_POST['nazov'];
                    $nazov = trim($nazov);
                    if ($nazov != "") {
                        if (isset($_POST['podnazov'])) {
                            $podnazov = $_POST['podnazov'];
                            $podnazov = trim($podnazov);
                            if ($podnazov != "") {
                                $nazov = ucfirst($nazov) . ': ';
                            } else {
                                $nazov = ucfirst($nazov) . '. ';
                            }
                        } else {
                            $nazov = ucfirst($nazov) . '. ';
                        }
                        $citacia = $citacia . $nazov;
                        echo $nazov;
                    }
                }

                //podnazov zdroja
                if (isset($_POST['podnazov'])) {
                    $podnazov = $_POST['podnazov'];
                    $podnazov = trim($podnazov);
                    if ($podnazov != "") {
                        $podnazov = ucfirst(chop(ltrim($podnazov))) . '. ';
                        $citacia = $citacia . $podnazov;
                        echo $podnazov;
                    }
                }
                
                 //nosic
                if (isset($_POST['nosic'])) {
                    $nosic = $_POST['nosic'];
                    $nosic = trim($nosic);
                    if ($nosic != "") {
                       
                        $nosic = '[' .strtolower($nosic) . ']. ';
                        $citacia = $citacia . $nosic;
                        echo $nosic;
                    }
                }


                //vydanie zdroja
                if (isset($_POST['vydanie'])) {
                    $vydanie = $_POST['vydanie'];
                    $vydanie = trim($vydanie);
                    if ($vydanie != "") {
                        $vydanie = $vydanie . '. vydanie. ';
                        $citacia = $citacia . $vydanie;
                        echo $vydanie;
                    }
                }

                //miesto vydania
                if (isset($_POST['miesto_vydania'])) {
                    $miesto = $_POST['miesto_vydania'];
                    $miesto = trim($miesto);
                    if ($miesto != "") {
                        if (isset($_POST['vydavatelstvo'])) {
                            $vydavatelstvo = $_POST['vydavatelstvo'];
                            $vydavatelstvo = trim($vydavatelstvo);
                            if ($vydavatelstvo != "") {

                                $miesto = ucfirst($miesto) . ': ';
                            } else {
                                if (isset($_POST['rok_vydania'])) {
                                    $rok = $_POST['rok_vydania'];
                                    $rok = trim($rok);
                                    if ($rok != "") {

                                        $miesto = ucfirst($miesto) . ': ';
                                    } else {

                                        $miesto = ucfirst($miesto) . '. ';
                                    }
                                }
                            }
                        } else {
                            if (isset($_POST['rok_vydania'])) {
                                $rok = $_POST['rok_vydania'];
                                $rok = trim($rok);
                                if ($rok != "") {
                                    $miesto = ucfirst($miesto) . ': ';
                                } else {
                                    $miesto = ucfirst($miesto) . '. ';
                                }
                            }
                        }
                        $citacia = $citacia . $miesto;
                        echo $miesto;
                    }
                }



               //vydavatelstvo
                if (isset($_POST['vydavatelstvo'])) {
                    $vydavatelstvo = $_POST['vydavatelstvo'];
                    $vydavatelstvo = trim($vydavatelstvo);
                    if ($vydavatelstvo != "") {
                        if (isset($_POST['datum_vydania'])) {
                            $rok = $_POST['datum_vydania'];
                            $rok = trim($rok);
                            if ($rok != "") {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . ', ';
                            } else {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                            }
                        } else {
                            $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                        }
                        $citacia = $citacia . $vydavatelstvo;
                        echo $vydavatelstvo;
                    }
                }



                //datum vydania
                if (isset($_POST['datum_vydania'])) {
                    $datum_vydania = $_POST['datum_vydania'];
                    $datum_vydania = trim($datum_vydania);
                    if ($datum_vydania != "") {
                        $datum_vydania = $datum_vydania . '. ';
                        $citacia = $citacia . $datum_vydania;
                        echo $rok;
                    }
                }
                
               

                //datum aktualziacia
                if (isset($_POST['datum_aktualizacie'])) {
                    $datum_aktualizacie = $_POST['datum_aktualizacie'];
                    $datum_aktualizacie = trim($datum_aktualizacie);
                    if ($datum_aktualizacie != "") {
                        $datum_aktualizacie = $datum_aktualizacie . '. ';
                        $citacia = $citacia . $datum_aktualizacie;
                        echo $datum_aktualizacie;
                    }
                }
                //datum citvania
                if (isset($_POST['datum_citovania'])) {
                    $datum_citovania = $_POST['datum_citovania'];
                    $datum_citovania = trim($datum_citovania);
                    if ($datum_citovania != "") {
                        $datum_citovania = '[cit. '.$datum_citovania . ']. ';
                        $citacia = $citacia . $datum_citovania;
                        echo $datum_citovania;
                    }
                }

               //rozsah
                if ((isset($_POST['od'])) && (isset($_POST['do']))) {
                    $od = $_POST['od'];
                    $do = $_POST['do'];
                    $od = trim($od);
                    $do = trim($do);
                    if (($od != "") && ($do != "")) {
                        $rozsah = $od . '-' . $do . '. ';
                        $citacia = $citacia . $rozsah;
                        echo $rozsah;
                    }
                }
                
                //url
                if (isset($_POST['url'])) {
                    $url = $_POST['url'];
                    $url = trim($url);
                    if ($url != "") {
                        $url = 'Dostupné na internete: < ' .$url. ' >. ';
                        $citacia = $citacia.$url;
                        echo $url;
                    }
                }
            }

               

               
            
            
            
            
             //-----------------------prispevok v zborniku / zbornik ---------------------------


            if (($_POST['druh_zdroja'] == 'zbornik') || ($_POST['druh_zdroja'] == 'prispevok_v_zborniku') || ($_POST['druh_zdroja'] == 'clanok') ){

                // priezvisko prveho autora
                if (isset($_POST['priezvisko_0'])) {
                    $priezvisko = $_POST['priezvisko_0'];
                    $priezvisko = trim($priezvisko);
                    if ($priezvisko != "") {
                        $priezvisko = (StrToUpper($priezvisko)) . ', ';
                        $citacia = $citacia . $priezvisko;
                        echo $priezvisko;
                    }
                }
                //meno prveho autora
                if (isset($_POST['meno_0'])) {
                    $meno = $_POST['meno_0'];
                    $meno = trim($meno);
                    if ($meno != "") {
                        $meno = StrToUpper($meno[0]) . '., ';
                        $citacia = $citacia . $meno;
                        echo $meno;
                    }
                }

                //priezvisko druheho autora
                if (isset($_POST['priezvisko_1'])) {
                    $priezvisko1 = $_POST['priezvisko_1'];
                    $priezvisko1 = trim($priezvisko1);
                    if ($priezvisko1 != "") {
                        $priezvisko1 = (StrToUpper($priezvisko1)) . ', ';
                        $citacia = $citacia . $priezvisko1;
                        echo $priezvisko1;
                    }
                }

                //meno druheeho autora
                if (isset($_POST['meno_1'])) {
                    $meno1 = $_POST['meno_1'];
                    $meno1 = trim($meno1);
                    if ($meno1 != "") {
                        $meno1 = StrToUpper($meno1[0]) . '., ';
                        $citacia = $citacia . $meno1;
                        echo $meno1;
                    }
                }
                
                
                 //nazov prispevku
                if (isset($_POST['prispevok'])) {
                    $prispevok = $_POST['prispevok'];
                    $prispevok = trim($prispevok);
                    if ($prispevok != "") {
                        $prispevok = ucfirst(chop(ltrim($prispevok))) . '. ';
                        $citacia = $citacia . $prispevok;
                        echo $prispevok;
                    }
                }
                
                
                 //nazov prispevku
                if (isset($_POST['zodpovednost'])) {
                    $zodpovednost = $_POST['zodpovednost'];
                    $zodpovednost = trim($zodpovednost);
                    if ($prispevok != "") {
                        $zodpovednost = 'In '.ucfirst(chop(ltrim($zodpovednost))) . '. ';
                        $citacia = $citacia . $zodpovednost;
                        echo $prispevok;
                    }
                }

                //nazov zdroja
                if (isset($_POST['nazov'])) {
                    $nazov = $_POST['nazov'];
                    $nazov = trim($nazov);
                    if ($nazov != "") {
                        if (isset($_POST['podnazov'])) {
                            $podnazov = $_POST['podnazov'];
                            $podnazov = trim($podnazov);
                            if ($podnazov != "") {
                                $nazov = ucfirst($nazov) . ': ';
                            } else {
                                $nazov = ucfirst($nazov) . '. ';
                            }
                        } else {
                            $nazov = ucfirst($nazov) . '. ';
                        }
                        $citacia = $citacia . $nazov;
                        echo $nazov;
                    }
                }

                //podnazov zdroja
                if (isset($_POST['podnazov'])) {
                    $podnazov = $_POST['podnazov'];
                    $podnazov = trim($podnazov);
                    if ($podnazov != "") {
                        $podnazov = ucfirst(chop(ltrim($podnazov))) . '. ';
                        $citacia = $citacia . $podnazov;
                        echo $podnazov;
                    }
                }

                //vydanie zdroja
                if (isset($_POST['vydanie'])) {
                    $vydanie = $_POST['vydanie'];
                    $vydanie = trim($vydanie);
                    if ($vydanie != "") {
                        $vydanie = $vydanie . '. vydanie. ';
                        $citacia = $citacia . $vydanie;
                        echo $vydanie;
                    }
                }

                //miesto vydania
                if (isset($_POST['miesto_vydania'])) {
                    $miesto = $_POST['miesto_vydania'];
                    $miesto = trim($miesto);
                    if ($miesto != "") {
                        if (isset($_POST['vydavatelstvo'])) {
                            $vydavatelstvo = $_POST['vydavatelstvo'];
                            $vydavatelstvo = trim($vydavatelstvo);
                            if ($vydavatelstvo != "") {

                                $miesto = ucfirst($miesto) . ': ';
                            } else {
                                if (isset($_POST['rok_vydania'])) {
                                    $rok = $_POST['rok_vydania'];
                                    $rok = trim($rok);
                                    if ($rok != "") {

                                        $miesto = ucfirst($miesto) . ': ';
                                    } else {

                                        $miesto = ucfirst($miesto) . '. ';
                                    }
                                }
                            }
                        } else {
                            if (isset($_POST['rok_vydania'])) {
                                $rok = $_POST['rok_vydania'];
                                $rok = trim($rok);
                                if ($rok != "") {
                                    $miesto = ucfirst($miesto) . ': ';
                                } else {
                                    $miesto = ucfirst($miesto) . '. ';
                                }
                            }
                        }
                        $citacia = $citacia . $miesto;
                        echo $miesto;
                    }
                }



                //vydavatelstvo
                if (isset($_POST['vydavatelstvo'])) {
                    $vydavatelstvo = $_POST['vydavatelstvo'];
                    $vydavatelstvo = trim($vydavatelstvo);
                    if ($vydavatelstvo != "") {
                        if (isset($_POST['rok_vydania'])) {
                            $rok = $_POST['rok_vydania'];
                            $rok = trim($rok);
                            if ($rok != "") {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . ', ';
                            } else {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                            }
                        } else {
                            $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                        }
                        $citacia = $citacia . $vydavatelstvo;
                        echo $vydavatelstvo;
                    }
                }



                //rok vydania
                if (isset($_POST['rok_vydania'])) {
                    $rok = $_POST['rok_vydania'];
                    $rok = trim($rok);
                    if ($rok != "") {
                        $rok = $rok . '. ';
                        $citacia = $citacia . $rok;
                        echo $rok;
                    }
                }

                //strany
                if (isset($_POST['isbn'])) {
                    $isbn = $_POST['isbn'];
                    $isbn = trim($isbn);
                    if ($isbn != "") {
                        $isbn = 'ISBN ' . $isbn . '. ';
                        $citacia = $citacia . $isbn;
                        echo $isbn;
                    }
                }

                //rozsah
                if ((isset($_POST['od'])) && (isset($_POST['do']))) {
                    $od = $_POST['od'];
                    $do = $_POST['do'];
                    $od = trim($od);
                    $do = trim($do);
                    if (($od != "") && ($do != "")) {
                        $rozsah = $od . '-' . $do . '. ';
                        $citacia = $citacia . $rozsah;
                        echo $rozsah;
                    }
                }
            }
            
            
             //-----------------------elektronicky prispevvok v zborniku, elektronicky zbornik, elektronicky clanok ---------------------------


            if (($_POST['druh_zdroja'] == 'zbornik') || ($_POST['druh_zdroja'] == 'prispevok_v_zborniku') || ($_POST['druh_zdroja'] == 'clanok')){

                // priezvisko prveho autora
                if (isset($_POST['priezvisko_0'])) {
                    $priezvisko = $_POST['priezvisko_0'];
                    $priezvisko = trim($priezvisko);
                    if ($priezvisko != "") {
                        $priezvisko = (StrToUpper($priezvisko)) . ', ';
                        $citacia = $citacia . $priezvisko;
                        echo $priezvisko;
                    }
                }
                //meno prveho autora
                if (isset($_POST['meno_0'])) {
                    $meno = $_POST['meno_0'];
                    $meno = trim($meno);
                    if ($meno != "") {
                        $meno = StrToUpper($meno[0]) . '., ';
                        $citacia = $citacia . $meno;
                        echo $meno;
                    }
                }

                //priezvisko druheho autora
                if (isset($_POST['priezvisko_1'])) {
                    $priezvisko1 = $_POST['priezvisko_1'];
                    $priezvisko1 = trim($priezvisko1);
                    if ($priezvisko1 != "") {
                        $priezvisko1 = (StrToUpper($priezvisko1)) . ', ';
                        $citacia = $citacia . $priezvisko1;
                        echo $priezvisko1;
                    }
                }

                //meno druheeho autora
                if (isset($_POST['meno_1'])) {
                    $meno1 = $_POST['meno_1'];
                    $meno1 = trim($meno1);
                    if ($meno1 != "") {
                        $meno1 = StrToUpper($meno1[0]) . '., ';
                        $citacia = $citacia . $meno1;
                        echo $meno1;
                    }
                }
                
                
                 //nazov prispevku
                if (isset($_POST['prispevok'])) {
                    $prispevok = $_POST['prispevok'];
                    $prispevok = trim($prispevok);
                    if ($prispevok != "") {
                        $prispevok = ucfirst(chop(ltrim($prispevok))) . '. ';
                        $citacia = $citacia . $prispevok;
                        echo $prispevok;
                    }
                }
                
                
                 //nazov prispevku
                if (isset($_POST['zodpovednost'])) {
                    $zodpovednost = $_POST['zodpovednost'];
                    $zodpovednost = trim($zodpovednost);
                    if ($prispevok != "") {
                        $zodpovednost = 'In '.ucfirst(chop(ltrim($zodpovednost))) . '. ';
                        $citacia = $citacia . $zodpovednost;
                        echo $prispevok;
                    }
                }

                //nazov zdroja
                if (isset($_POST['nazov'])) {
                    $nazov = $_POST['nazov'];
                    $nazov = trim($nazov);
                    if ($nazov != "") {
                        if (isset($_POST['podnazov'])) {
                            $podnazov = $_POST['podnazov'];
                            $podnazov = trim($podnazov);
                            if ($podnazov != "") {
                                $nazov = ucfirst($nazov) . ': ';
                            } else {
                                $nazov = ucfirst($nazov) . '. ';
                            }
                        } else {
                            $nazov = ucfirst($nazov) . '. ';
                        }
                        $citacia = $citacia . $nazov;
                        echo $nazov;
                    }
                }

                //podnazov zdroja
                if (isset($_POST['podnazov'])) {
                    $podnazov = $_POST['podnazov'];
                    $podnazov = trim($podnazov);
                    if ($podnazov != "") {
                        $podnazov = ucfirst(chop(ltrim($podnazov))) . '. ';
                        $citacia = $citacia . $podnazov;
                        echo $podnazov;
                    }
                }

                //vydanie zdroja
                if (isset($_POST['vydanie'])) {
                    $vydanie = $_POST['vydanie'];
                    $vydanie = trim($vydanie);
                    if ($vydanie != "") {
                        $vydanie = $vydanie . '. vydanie. ';
                        $citacia = $citacia . $vydanie;
                        echo $vydanie;
                    }
                }

                //miesto vydania
                if (isset($_POST['miesto_vydania'])) {
                    $miesto = $_POST['miesto_vydania'];
                    $miesto = trim($miesto);
                    if ($miesto != "") {
                        if (isset($_POST['vydavatelstvo'])) {
                            $vydavatelstvo = $_POST['vydavatelstvo'];
                            $vydavatelstvo = trim($vydavatelstvo);
                            if ($vydavatelstvo != "") {

                                $miesto = ucfirst($miesto) . ': ';
                            } else {
                                if (isset($_POST['rok_vydania'])) {
                                    $rok = $_POST['rok_vydania'];
                                    $rok = trim($rok);
                                    if ($rok != "") {

                                        $miesto = ucfirst($miesto) . ': ';
                                    } else {

                                        $miesto = ucfirst($miesto) . '. ';
                                    }
                                }
                            }
                        } else {
                            if (isset($_POST['rok_vydania'])) {
                                $rok = $_POST['rok_vydania'];
                                $rok = trim($rok);
                                if ($rok != "") {
                                    $miesto = ucfirst($miesto) . ': ';
                                } else {
                                    $miesto = ucfirst($miesto) . '. ';
                                }
                            }
                        }
                        $citacia = $citacia . $miesto;
                        echo $miesto;
                    }
                }



                //vydavatelstvo
                if (isset($_POST['vydavatelstvo'])) {
                    $vydavatelstvo = $_POST['vydavatelstvo'];
                    $vydavatelstvo = trim($vydavatelstvo);
                    if ($vydavatelstvo != "") {
                        if (isset($_POST['rok_vydania'])) {
                            $rok = $_POST['rok_vydania'];
                            $rok = trim($rok);
                            if ($rok != "") {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . ', ';
                            } else {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                            }
                        } else {
                            $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                        }
                        $citacia = $citacia . $vydavatelstvo;
                        echo $vydavatelstvo;
                    }
                }



                //rok vydania
                if (isset($_POST['rok_vydania'])) {
                    $rok = $_POST['rok_vydania'];
                    $rok = trim($rok);
                    if ($rok != "") {
                        $rok = $rok . '. ';
                        $citacia = $citacia . $rok;
                        echo $rok;
                    }
                }

                //strany
                if (isset($_POST['issn'])) {
                    $issn = $_POST['issn'];
                    $issn = trim($issn);
                    if ($issn != "") {
                        $issn = 'ISSN ' . $issn . '. ';
                        $citacia = $citacia . $issn;
                        echo $issn;
                    }
                }

                //rozsah
                if ((isset($_POST['od'])) && (isset($_POST['do']))) {
                    $od = $_POST['od'];
                    $do = $_POST['do'];
                    $od = trim($od);
                    $do = trim($do);
                    if (($od != "") && ($do != "")) {
                        $rozsah = $od . '-' . $do . '. ';
                        $citacia = $citacia . $rozsah;
                        echo $rozsah;
                    }
                }
            }
            
            
              //-----------------------akademicka praca ---------------------------


            if (($_POST['druh_zdroja'] == 'akademicka_praca') ){

                // priezvisko prveho autora
                if (isset($_POST['priezvisko_0'])) {
                    $priezvisko = $_POST['priezvisko_0'];
                    $priezvisko = trim($priezvisko);
                    if ($priezvisko != "") {
                        $priezvisko = (StrToUpper($priezvisko)) . ', ';
                        $citacia = $citacia . $priezvisko;
                        echo $priezvisko;
                    }
                }
                //meno prveho autora
                if (isset($_POST['meno_0'])) {
                    $meno = $_POST['meno_0'];
                    $meno = trim($meno);
                    if ($meno != "") {
                        $meno = StrToUpper($meno[0]) . '., ';
                        $citacia = $citacia . $meno;
                        echo $meno;
                    }
                }

                if (isset($_POST['nazov'])) {
                    $nazov = $_POST['nazov'];
                    $nazov = trim($nazov);
                    if ($nazov != "") {
                        if (isset($_POST['podnazov'])) {
                            $podnazov = $_POST['podnazov'];
                            $podnazov = trim($podnazov);
                            if ($podnazov != "") {
                                $nazov = ucfirst($nazov) . ': ';
                            } else {
                                $nazov = ucfirst($nazov) . '. ';
                            }
                        } else {
                            $nazov = ucfirst($nazov) . '. ';
                        }
                        $citacia = $citacia . $nazov;
                        echo $nazov;
                    }
                }

                //podnazov zdroja
                if (isset($_POST['podnazov'])) {
                    $podnazov = $_POST['podnazov'];
                    $podnazov = trim($podnazov);
                    if ($podnazov != "") {
                        $podnazov = ucfirst(chop(ltrim($podnazov))) . '. ';
                        $citacia = $citacia . $podnazov;
                        echo $podnazov;
                    }
                }

            

                //miesto vydania
                if (isset($_POST['miesto_vydania'])) {
                    $miesto = $_POST['miesto_vydania'];
                    $miesto = trim($miesto);
                    if ($miesto != "") {
                        if (isset($_POST['vydavatelstvo'])) {
                            $vydavatelstvo = $_POST['vydavatelstvo'];
                            $vydavatelstvo = trim($vydavatelstvo);
                            if ($vydavatelstvo != "") {

                                $miesto = ucfirst($miesto) . ': ';
                            } else {
                                if (isset($_POST['rok_vydania'])) {
                                    $rok = $_POST['rok_vydania'];
                                    $rok = trim($rok);
                                    if ($rok != "") {

                                        $miesto = ucfirst($miesto) . ': ';
                                    } else {

                                        $miesto = ucfirst($miesto) . '. ';
                                    }
                                }
                            }
                        } else {
                            if (isset($_POST['rok_vydania'])) {
                                $rok = $_POST['rok_vydania'];
                                $rok = trim($rok);
                                if ($rok != "") {
                                    $miesto = ucfirst($miesto) . ': ';
                                } else {
                                    $miesto = ucfirst($miesto) . '. ';
                                }
                            }
                        }
                        $citacia = $citacia . $miesto;
                        echo $miesto;
                    }
                }



                //vydavatelstvo
                if (isset($_POST['vydavatelstvo'])) {
                    $vydavatelstvo = $_POST['vydavatelstvo'];
                    $vydavatelstvo = trim($vydavatelstvo);
                    if ($vydavatelstvo != "") {
                        if (isset($_POST['rok_vydania'])) {
                            $rok = $_POST['rok_vydania'];
                            $rok = trim($rok);
                            if ($rok != "") {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . ', ';
                            } else {
                                $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                            }
                        } else {
                            $vydavatelstvo = ucfirst($vydavatelstvo) . '. ';
                        }
                        $citacia = $citacia . $vydavatelstvo;
                        echo $vydavatelstvo;
                    }
                }



                //rok vydania
                if (isset($_POST['rok_vydania'])) {
                    $rok = $_POST['rok_vydania'];
                    $rok = trim($rok);
                    if ($rok != "") {
                        $rok = $rok . '. ';
                        $citacia = $citacia . $rok;
                        echo $rok;
                    }
                }

                //strany
                if (isset($_POST['issn'])) {
                    $issn = $_POST['issn'];
                    $issn = trim($issn);
                    if ($issn != "") {
                        $issn = 'ISSN ' . $issn . '. ';
                        $citacia = $citacia . $issn;
                        echo $issn;
                    }
                }

            }
            
            
            echo "<br>finalna citacia:" . $citacia;
            $zdroj['citacia'] = $citacia;



            /*
             * ulozenie clanku do DB
             */
            $vypisZdrojov->ulozZdroj($_POST['zdroj_id'], $zdroj);
//ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
            $idPoslednehoVlozenehoZdroja = $vypisZdrojov->posledneId();
            if ($idPoslednehoVlozenehoZdroja == 0)
                $idPoslednehoVlozenehoZdroja = $_POST['zdroj_id'];

// $this->pridajSpravu('ulozil sa zdroj pod id= ' . $idPoslednehoVlozenehoZdroja);
            $this->pridajSpravu('Zdroj bol uložený.');


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
//pred kazdym ukladanim vycisti prepajaciu tabulku aby sa autori nezdvojovali
            $vypisZdrojov->vymazZprepajacejAutora($_POST['zdroj_id']);
            for ($a = 0; $a <= 10; $a++) {
                if ((isset($_POST['titul_pred_' . $a])) OR ( isset($_POST['meno' . $a])) OR ( isset($_POST['priezvisko' . $a])) OR ( isset($_POST['titul_po' . $a]))) {

                    $idAutorovPodlaUdajov = $vypisZdrojov->vratIdAutora($_POST['titul_pred_' . $a], $_POST['meno_' . $a], $_POST['priezvisko_' . $a], $_POST['titul_po_' . $a]);
                    $idAutorovPodlaUdajov = $idAutorovPodlaUdajov[0];
                    if (!$idAutorovPodlaUdajov) {
//vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                        $kluce_autor = array('titul_pred_' . $a, 'meno_' . $a, 'priezvisko_' . $a, 'titul_po_' . $a);
//zlucenie klucov s hodnotami s post- a ich priradenie
                        $autor = array_intersect_key($_POST, array_flip($kluce_autor));

//nahradenie autora v poli autor_cislo za autor  aby doslo k ulozeniu do databazy podla klucov
                        if (isset($autor['titul_pred_' . $a])) {
                            $autor['titul_pred'] = $autor['titul_pred_' . $a];
                            unset($autor['titul_pred_' . $a]);
                        }

                        if (isset($autor['meno_' . $a])) {
                            $autor['meno'] = $autor['meno_' . $a];
                            unset($autor['meno_' . $a]);
                        }

                        if (isset($autor['priezvisko_' . $a])) {
                            $autor['priezvisko'] = $autor['priezvisko_' . $a];
                            unset($autor['priezvisko_' . $a]);
                        }

                        if (isset($autor['titul_po_' . $a])) {
                            $autor['titul_po'] = $autor['titul_po_' . $a];
                            unset($autor['titul_po_' . $a]);
                        }
                        $vypisZdrojov->ulozAutora('', $autor); //tu sa predpoklada ze autor este neexistuje
//ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                        $idPoslednehoVlozenehoAutora = $vypisZdrojov->posledneId();


//$this->pridajSpravu('<br>ulozil sa autor ktory este neexistoval pod id= ' . $idPoslednehoVlozenehoAutora);
//echo '<br>ulozil sa autor ktory este neexistoval pod id=' . $idPoslednehoVlozenehoAutora;
                    } else {
                        $idPoslednehoVlozenehoAutora = $idAutorovPodlaUdajov;
//echo '<br>autor uz existoval takze sa neukladal iba sa ulozilo jeho id=' . $idPoslednehoVlozenehoAutora;
//$this->pridajSpravu('<br>autor uz existoval takze sa neukladal iba sa ulozilo jeho id== ' . $idPoslednehoVlozenehoAutora);
                    };

                    /*
                     * vlozenie udajov do prepajacej tabulky autor_zdroj
                     */

                    $idPodlaUdajov = $vypisZdrojov->vratIdZdrojAutorPodlaUdajov($idPoslednehoVlozenehoAutora, $idPoslednehoVlozenehoZdroja);

                    if (!$idPodlaUdajov) {
                        $idPodlaUdajov = '';
// $this->pridajSpravu('id z prepajacej tabulky sa nenaslo, malo by sa ulozit ako prazdny retazec=' . $idPodlaUdajov);

                        $kluce_autor_zdroj = array('autor_id', 'zdroj_id');
//zlucenie klucov s hodnotami s post- a ich priradenie
                        $pomocne = array('autor_id' => $idPoslednehoVlozenehoAutora, 'zdroj_id' => $idPoslednehoVlozenehoZdroja);

                        $autor_zdroj = array_intersect_key($pomocne, array_flip($kluce_autor_zdroj));
//ulozenie autora do db
                        $vypisZdrojov->ulozZdrojAutor($idPodlaUdajov, $autor_zdroj);
                    }
                }
            }




            /*
             * ----------------------------------------------------------------------------------
             * vlozenie KLUCOVEHO SLOVA do tabulky klucove slovo + vlozenie do prepajacej tabulky
             * ----------------------------------------------------------------------------------
             */
//pred kazdym ukladanim vycisti prepajaciu tabulku aby sa slova nezdvojovali
            $vypisZdrojov->vymazZprepajacejSlova($_POST['zdroj_id']);
            for ($i = 0; $i <= 10; $i++) {
//ak nieje zadany nazov_okruhu_$i -> nerobi sa nic,ak je zadany treba ho ulozit, ale iba v tom pripade ak uz v tabulke nieje
                if (isset($_POST['klucove_slovo_' . $i])) {
//najdeme id okruhu ktore sme zadali v editore
                    $idSlova = $vypisZdrojov->vratIdKlucovehoSlova($_POST['klucove_slovo_' . $i]);
//ak sa klucove slovo nenaslo  tak sa vytvori nove a ulozi sa do tabulky
                    if (!$idSlova) {
//echo "<br> id slova sa nenaslo v tabulke <br>";
//vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                        $kluce_slovo = array('klucove_slovo_' . $i);
//zlucenie klucov s hodnotami s post- a ich priradenie
                        $klucove_slovo = array_intersect_key($_POST, array_flip($kluce_slovo));


//nahradenie kluca v poli  nazov_okruhu_cislo za nazov_okruhu, aby to sedelo pri ukladani do DB
                        $klucove_slovo['klucove_slovo'] = $klucove_slovo['klucove_slovo_' . $i];
                        unset($klucove_slovo['klucove_slovo_' . $i]);

//echo "<br> slovo po uprave= " . $klucove_slovo['klucove_slovo'];
//ulozenie klucoveho slova do db
                        $vypisZdrojov->ulozKlucoveSlovo('', $klucove_slovo);
//ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                        $idPoslednehoVlozenehoSlova = $vypisZdrojov->posledneId();

// echo '<br>slovo este neexistoval takze sa ulozil pod id=' . $idPoslednehoVlozenehoSlova;
// $this->pridajSpravu('<br>slovo este neexistoval takze sa ulozil pod id= ' . $idPoslednehoVlozenehoSlova);
                    } else {
//ak sa okruh nasiel tak sa iba ulozi jeho id pre dalsie pouzitie na vkladanie do medzitabulky
                        $idPoslednehoVlozenehoSlova = $idSlova['klucove_slovo_id'];
//   echo $idPoslednehoVlozenehoSlova;
//    echo '<br>slovo uz existoval takze sa neukladal vratilo sa len jeho id' . $idPoslednehoVlozenehoSlova;
//  $this->pridajSpravu('<br>slovo uz existoval takze sa neukladal vratilo sa len jeho id ' . $idPoslednehoVlozenehoSlova);
                    }

                    /*
                     * vlozenie udajov do prepajacej tabulky zdroj_slovo
                     */

//ak uz slovo v prepajacej tabulke je tak sa uz neuklada znova, ak nieje tak sa ulozi- kvoli duplicitnym vypisom
                    $jeSlovovPrepajacej = $vypisZdrojov->vratIdZdrojSlovoPodlaUdajov($idPoslednehoVlozenehoZdroja, $idPoslednehoVlozenehoSlova);
                    if (!$jeSlovovPrepajacej) {
//priradenie hodnoty posledneho vlozeneho zdroja
                        $zdroj_klucove_slovo['zdroj_id'] = $idPoslednehoVlozenehoZdroja;
//priradenie hodnoty posledneho vlozeneho klucoveho slova
                        $zdroj_klucove_slovo['klucove_slovo_id'] = $idPoslednehoVlozenehoSlova;
//ulozenie udajov do databazy, pricom prvy udaj zdroj_klucove_slovo_id je nastavene hore v poli na null
                        $vypisZdrojov->ulozZdrojKlucoveSlovo($zdroj_klucove_slovo);
                    }
                }
            }

            /*
             * ------------------------------------------------------------------
             * vlozenie OKRUHU do tabulky okruh + vlozenie do prepajacej tabulky
             * ------------------------------------------------------------------
             */
//pred kazdym ukladanim vycisti prepajaciu tabulku aby sa okruhy nezdvojovali
            $vypisZdrojov->vymazZprepajacejOkruhy($_POST['zdroj_id']);
            for ($j = 0; $j <= 10; $j++) {
//ak nieje zadany nazov_okruhu_$i -> nerobi sa nic,ak je zadany treba ho ulozit, ale iba v tom pripade ak uz v tabulke nieje
                if (isset($_POST['nazov_okruhu_' . $j])) {
//najdeme id okruhu ktore sme zadali v editore
                    $idOkruhu = $vypisZdrojov->vratIdOkruhu($_POST['nazov_okruhu_' . $j]);
//ak sa klucove slovo nenaslo  tak sa vytvori nove a ulozi sa do tabulky
                    if (!$idOkruhu) {
//  echo "<br> id okruhu sa nenaslo v tabulke <br>";
//vytvorenie klucov, ktore sa zhodouju s udajmi ktora ziskame z $_POST,potom sa priradia k sebe
                        $kluce_okruh = array('nazov_okruhu_' . $j);
//zlucenie klucov s hodnotami s post- a ich priradenie
                        $okruh = array_intersect_key($_POST, array_flip($kluce_okruh));

//    echo "<br> POST: ";
//    print_r($_POST);
//nahradenie kluca v poli  nazov_okruhu_cislo za nazov_okruhu, aby to sedelo pri ukladani do DB
                        $okruh['nazov_okruhu'] = $okruh['nazov_okruhu_' . $j];
                        unset($okruh['nazov_okruhu_' . $j]);

//   echo "<br> okruh po uprave= " . $okruh['nazov_okruhu'];
//ulozenie klucoveho slova do db
                        $vypisZdrojov->ulozOkruh('', $okruh);
//ziskania posledneho ID kvoli vlozeniu do prepajacej tabulky
                        $idPoslednehoVlozenehoOkruhu = $vypisZdrojov->posledneId();

// echo '<br>okruh este neexistoval takze sa ulozil pod id=' . $idPoslednehoVlozenehoOkruhu;
// $this->pridajSpravu('<br>okruh este neexistoval takze sa ulozil pod id= ' . $idPoslednehoVlozenehoOkruhu);
                    } else {
//ak sa okruh nasiel tak sa iba ulozi jeho id pre dalsie pouzitie na vkladanie do medzitabulky
                        $idPoslednehoVlozenehoOkruhu = $idOkruhu['okruh_id'];
//   echo $idPoslednehoVlozenehoOkruhu;
//    echo '<br>okruh uz existoval takze sa neukladal vratilo sa len jeho id' . $idPoslednehoVlozenehoOkruhu;
//   $this->pridajSpravu('<br>okruh uz existoval takze sa neukladal vratilo sa len jeho id ' . $idPoslednehoVlozenehoOkruhu);
                    }

                    /*
                     * vlozenie udajov do prepajacej tabulky zdroj_okruh
                     */

//ak uz okruh v prepajacej tabulke je tak sa uz neuklada znova, ak nieje tak sa ulozi- kvoli duplicitnym vypisom
                    $jeOkruhvPrepajacej = $vypisZdrojov->vratIdZdrojOkruhPodlaUdajov($idPoslednehoVlozenehoZdroja, $idPoslednehoVlozenehoOkruhu);
                    if (!$jeOkruhvPrepajacej) {

//priradenie hodnoty posledneho vlozeneho zdroja
                        $zdroj_okruh['okruh_id'] = $idPoslednehoVlozenehoOkruhu;
//priradenie hodnoty posledneho vlozeneho klucoveho slova
                        $zdroj_okruh['zdroj_id'] = $idPoslednehoVlozenehoZdroja;
//ulozenie udajov do databazy, pricom prvy udaj zdroj_klucove_slovo_id je nastavene hore v poli na null
                        $vypisZdrojov->ulozZdrojOkruh($zdroj_okruh);
                    } else {
//nic
                    }
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
            $nacitatAutorov = $vypisZdrojov->vratAutorov($parametre[0]);
            $nacitatSlova = $vypisZdrojov->vratKlucoveSlova($parametre[0]);
            $nacitatOkruhy = $vypisZdrojov->vratOkruhy($parametre[0]);

            if ($nacitanyZdroj) {
                $zdroj = $nacitanyZdroj;
                if ($nacitatAutorov) {
                    $autor = $nacitatAutorov;
                }
                if ($nacitatSlova) {
                    $klucove_slovo = $nacitatSlova;
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
