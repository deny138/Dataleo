<?php

class VypisZdrojov {
    /*
     * zakladna nepouzita metoda, ktora vrati vsetky zdroje v databaze
     */

    public function vratZdroje() {
        return Db::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_vydania`,`nosic`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`');
    }

    /*
     *  vrati zdroj ktory sa bude ukazovat po kliknutu nanho, podla parametra zdroj_id
     */

    public function vratZdrojPodlaZdrojId($id) {

        return DB::dotazJeden('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`,`prispevok`,`zodpovednost`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`od`,`do`,`url`,`datum_vydania`,`datum_aktualizacie`,`datum_pridania`,`nosic`, `hodnotenie`, `poznamka`,`citacia` FROM `zdroj`'
                        . 'WHERE `zdroj_id`=?', array($id));
    }
    
    
   
    /*
     * vrati vsetky zdroje spolu s autorom, budu sa vypisovat ako zoznam pre daneho pouzivatela
     * podmienkou je ze partia danemu pouzivatelovi
     */

    public function vratZdrojeSautorom($pouzivatel_id, $zoradit) {
        return Db::dotazVsetky('SELECT az.id, z.zdroj_id,`druh_zdroja`, `nazov`,`podnazov`,`prispevok`,`zodpovednost`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`od`,`do`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka`, '
                        . 'a.autor_id, `titul_pred`,`meno`,`priezvisko`,`titul_po` '
                        . 'FROM `zdroj` AS z  LEFT JOIN autor_zdroj AS az ON az.zdroj_id = z.zdroj_id  '
                        . 'LEFT JOIN `autor` AS a ON a.autor_id=az.autor_id WHERE `pouzivatel_id`=? ORDER BY ? ASC', array($pouzivatel_id, $zoradit));
    }
    
    
    public function vratZdrojeBezAutora($pouzivatel_id, $zoradit) {
        return Db::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka`,`citacia`'
                . ' FROM `zdroj`WHERE `pouzivatel_id`=? ORDER BY ? ASC', array($pouzivatel_id, $zoradit));
    }

    /*
     * metoda na vypis autorov,kedze ich moze byt viac
     */

    public function vratAutorov($id) {
        return Db::dotazVsetky('SELECT `titul_pred`,`meno`,`priezvisko`, `titul_po` FROM `autor` AS a'
                        . ' JOIN `autor_zdroj` AS az ON a.autor_id = az.autor_id WHERE `zdroj_id`=?', array($id));
    }
    
    
    public function vratIdAutora($titul_pred,$meno,$priezvisko,$titul_po) {
        return Db::dotazJeden('SELECT `autor_id` FROM `autor`  WHERE `titul_pred`=? AND `meno`=? AND `priezvisko`=? AND `titul_po`=? ', array($titul_pred,$meno,$priezvisko,$titul_po));
    }

    public function vratIdZdrojAutorPodlaUdajov($autor_id,$zdroj_id){
        return Db::dotazJeden('SELECT `id` FROM `autor_zdroj`  WHERE `autor_id`=? AND `zdroj_id`=?  ', array($autor_id,$zdroj_id));
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
    //tieto 2 funkcie su pre prve okno, kde su odkazy na filtrovanie podla  okruhu a slova
     public function vratVsetkyOkruhy($pouzivatel_id) {
        return Db::dotazVsetky('SELECT  DISTINCT o.nazov_okruhu, o.okruh_id FROM `okruh` AS o  JOIN `zdroj_okruh` AS zo ON o.okruh_id = zo.okruh_id'
                . '  JOIN `zdroj` AS z ON z.zdroj_id = zo.zdroj_id WHERE `pouzivatel_id` = ?',array($pouzivatel_id));
    }
    
     public function vratVsetkySlova($pouzivatel_id) {
        return Db::dotazVsetky('SELECT DISTINCT o.klucove_slovo, o.klucove_slovo_id FROM `klucove_slova` AS o  JOIN `zdroj_klucove_slovo` AS zo ON o.klucove_slovo_id = zo.klucove_slovo_id'
                . '  JOIN `zdroj` AS z ON z.zdroj_id = zo.zdroj_id WHERE `pouzivatel_id` = ?',array($pouzivatel_id));
    }
    /*
     * vrati ID okruhu
     */
    
    public function vratIdOkruhu($okruh) {
        return Db::dotazJeden('SELECT `okruh_id` FROM `okruh` WHERE `nazov_okruhu`=?', array($okruh));
    }
    
    public function vratIdZdrojOkruhPodlaUdajov($zdroj_id,$okruh_id){
        return Db::dotazJeden('SELECT `zdroj_okruh_id` FROM `zdroj_okruh`  WHERE `zdroj_id`=? AND `okruh_id`=?  ', array($zdroj_id,$okruh_id));
    }
    
    public function vratIdZdrojSlovoPodlaUdajov($zdroj_id,$klucove_slovo_id){
        return Db::dotazJeden('SELECT `zdroj_klucove_slovo_id` FROM `zdroj_klucove_slovo`  WHERE `zdroj_id`=? AND `klucove_slovo_id`=?  ', array($zdroj_id,$klucove_slovo_id));
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
           echo 'okruh neexistoval';
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

    public function ulozZdrojAutor($id,$autor_zdroj) {
        if (!$id) {
        Db::vloz('autor_zdroj', $autor_zdroj);
         } else {
            Db::zmen('autor_zdroj', $autor_zdroj, 'WHERE `id`=?', array($id));
        }
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
    
    public function vymazZprepajacejOkruhy($zdroj_id){
        Db::dotaz('DELETE FROM zdroj_okruh WHERE zdroj_id=?', array($zdroj_id));
    }
    
    public function vymazZprepajacejSlova($zdroj_id){
        Db::dotaz('DELETE FROM zdroj_klucove_slovo WHERE zdroj_id=?', array($zdroj_id));
    }
    
    public function vymazZprepajacejAutora($zdroj_id){
        Db::dotaz('DELETE FROM autor_zdroj WHERE zdroj_id=?', array($zdroj_id));
    }

    /*
     * vrati posledne ID vlozeneho zaznamu, napr ak vlozime zdroj, vrati posledne zdroj_id
     * parameter ktory vrati musi byt autoincrement
     */

    public function posledneId() {
        return Db::getLastId();
    }

}
