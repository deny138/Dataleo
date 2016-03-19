<?php

class VypisZdrojov {
    /*
     * zakladna nepouzita metoda, ktora vrati vsetky zdroje v databaze
     */

    public function vratZdroje() {
        return Db::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`');
    }

    /*
     *  vrati zdroj ktory sa bude ukazovat po kliknutu nanho, podla parametra zdroj_id
     */

    public function vratZdrojPodlaZdrojId($id) {

        return DB::dotazJeden('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `zdroj_id`=?', array($id));
    }

    /*
     * vrati vsetky zdroje spolu s autorom, budu sa vypisovat ako zoznam pre daneho pouzivatela
     * podmienkou je ze partia danemu pouzivatelovi
     */

    public function vratZdrojeSautorom($pouzivatel_id, $zoradit) {
        return Db::dotazVsetky('SELECT z.zdroj_id,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka`, '
                        . 'a.autor_id, `titul_pred`,`meno`,`priezvisko`,`titul_po` '
                        . 'FROM `zdroj` AS z  LEFT JOIN autor_zdroj AS az ON az.zdroj_id = z.zdroj_id  '
                        . 'LEFT JOIN `autor` AS a ON a.autor_id=az.autor_id WHERE `pouzivatel_id`=? ORDER BY ? ASC', array($pouzivatel_id, $zoradit));
    }

    /*
     * metoda na vypis autorov,kedze ich moze byt viac
     */

    public function vratAutorov($id) {
        return Db::dotazVsetky('SELECT `titul_pred`,`meno`,`priezvisko`, `titul_po` FROM `autor` AS a'
                        . ' JOIN `autor_zdroj` AS az ON a.autor_id = az.autor_id WHERE `zdroj_id`=?', array($id));
    }

    public function vratIdAutoraPodlaTitulPred($titul_pred){
       return Db::dotazVsetky('SELECT `autor_id` FROM `autor` WHERE `titul_pred`=? ', array($titul_pred));
    } 
    public function vratIdAutoraPodlaMena($meno){
       return Db::dotazVsetky('SELECT `autor_id` FROM `autor` WHERE  `meno`=?  ', array($meno));
    } 
    public function vratIdAutoraPodlaPriezviska($priezvisko){
       return Db::dotazVsetky('SELECT `autor_id` FROM `autor` WHERE `priezvisko`=? ', array($priezvisko));
    } 
    public function vratIdAutoraPodlaTitulPo($titul_po){
       return Db::dotazVsetky('SELECT `autor_id` FROM `autor` WHERE `titul_po`=? ', array($titul_po));
    } 
    
    /*
     * metoda na vypis klucovych slov pre DANY ZDROJ,kedze ich moze byt viac 
     */

    public function vratKlucoveSlova($id) {
        return Db::dotazVsetky('SELECT `klucove_slovo` FROM `klucove_slova` AS k'
                        . ' JOIN `zdroj_klucove_slovo` AS zk ON k.klucove_slovo_id = zk.klucove_slovo_id WHERE `zdroj_id`=?', array($id));
    }

    /*
     * vrati ID klucoveho slova
     */

    public function vratIdKlucovehoSlova($klucove_slovo) {
        return Db::dotazJeden('SELECT `klucove_slovo_id` FROM `klucove_slova` WHERE `klucove_slovo`=?', array($klucove_slovo));
    }


    /*
     * metoda na vypis okruhov ,kedze ich moze byt viac
     */

    public function vratOkruhy($id) {
        return Db::dotazVsetky('SELECT `nazov_okruhu` FROM `okruh` AS o'
                        . ' JOIN `zdroj_okruh` AS zo ON o.okruh_id = zo.okruh_id WHERE `zdroj_id`=?', array($id));
    }
    /*
     * vrati ID okruhu
     */
    
    public function vratIdOkruhu($okruh) {
        return Db::dotazJeden('SELECT `okruh_id` FROM `okruh` WHERE `nazov_okruhu`=?', array($okruh));
    }

    /*
     * ak nieje zadane ID zdroja tak vlozime zdroj ako novy s nasledujucim id/autoincrement
     * ak je zadane id zdroja tak dany zdroj prepiseme
     */

    public function ulozZdroj($id, $zdroj) {
        if (!$id) {
            Db::vloz('zdroj', $zdroj);
        } else {
            Db::zmen('zdroj', $zdroj, 'WHERE zdroj_id=?', array($id));
        }
    }

    /*
     * funkcia ulozi autora
     * autor je pole uchovava vsetky udaje o autorovi
     * prepojovacie udaje je pole uchovava vsetky udaje do tabulke autor zdroj
     */

    public function ulozAutora($autor_id, $autor) {
        if (!$autor_id) {
            Db::vloz('autor', $autor);
        } else {
            Db::zmen('autor', $autor, 'WHERE autor_id=?', array($autor_id));
        }
    }

    /*
     * funkcia ulozi klucove_slovo
     * $klucove slovo id je parameter
     * klucove slovo je pole kde je ulozene vsetko do tabulky klucove slovo
     * zdroj_klucove_slovo su vsetky udaje do tabulkt zdroj klucove slovo, je to tiez pole
     */

    public function ulozKlucoveSlovo($klucove_slovo_id, $klucove_slovo) {
        if (!$klucove_slovo_id) {

            Db::vloz('klucove_slova', $klucove_slovo);
        } else {
            Db::zmen('klucove_slova', $klucove_slovo, 'WHERE klucove_slovo_id=?', array($klucove_slovo_id));
        }
    }

    /*
     * funkcia ulozi okruh
     * okruh je pole
     */

    public function ulozOkruh($okruh_id, $nazov_okruhu) {
        if (!$okruh_id) {
            Db::vloz('okruh', $nazov_okruhu);
        } else {
            Db::zmen('okruh', $nazov_okruhu, 'WHERE okruh_id=?', array($okruh_id));
        }
    }

    /*
     * metoda ulozi do prepojovvacej tabulky udaje o zdroji a klucovom slove
     */

    public function ulozZdrojKlucoveSlovo($zdroj_klucove_slovo) {
        Db::vloz('zdroj_klucove_slovo', $zdroj_klucove_slovo);
    }

    /*
     * metoda ulozi do prepojovvacej tabulky udaje o zdroji a klucovom slove
     */

    public function ulozZdrojAutor($autor_zdroj) {
        Db::vloz('autor_zdroj', $autor_zdroj);
    }

    /*
     * metoda ulozi do prepojovvacej tabulky udaje o zdroji a klucovom slove
     */

    public function ulozZdrojOkruh($zdroj_okruh) {
        Db::vloz('zdroj_okruh', $zdroj_okruh);
    }

    /*
     * metoda odstrani zdroj s danym id zdroja
     */

    public function odstranZdroj($id) {
        Db::dotaz('DELETE FROM autor_zdroj WHERE zdroj_id=?', array($id));
        Db::dotaz('DELETE FROM zdroj_okruh WHERE zdroj_id=?', array($id));
        Db::dotaz('DELETE FROM zdroj_klucove_slovo WHERE zdroj_id=?', array($id));
        Db::dotaz('DELETE FROM zdroj WHERE zdroj_id=?', array($id));
    }

    /*
     * vrati posledne ID vlozeneho zaznamu, napr ak vlozime zdroj, vrati posledne zdroj_id
     * parameter ktory vrati musi byt autoincrement
     */

    public function posledneId() {
        return Db::getLastId();
    }

}
