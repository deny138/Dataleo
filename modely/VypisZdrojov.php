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
     * zatial nepouzita metoda, ktoru chcem pouzit na vypis  klucovych slov, pri danom zdroji- neviem ci je dotaz napisany spravne!!!!
     */
    public function vratKlucoveSlova($id) {
        return Db::dotazVsetky('SELECT `klucove_slovo` FROM `klucove_slova` AS k'
                . ' JOIN `zdroj_klucove_slovo` AS zk ON k.klucove_slovo_id = zk.klucove_slovo_id WHERE `zdroj_id`=?', array($id) );
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

    public function ulozAutora($autor_id, $autor, $prepojovacie_udaje) { //treti parameter
        if (!$autor_id) {
            Db::vloz('autor', $autor);
            Db::vloz('autor_zdroj', $prepojovacie_udaje);
        } else {
            Db::zmen('autor', $autor, 'WHERE autor_id=?', array($autor));
        }
    }

    /*
     * funkcia ulozi okruh
     * okruh je pole
     * prepojovacie udaje je pole co ulzi vsetky udaje do tabulky zdroj okruh
     */

    public function ulozOkruh($okruh_id, $okruh, $prepojovacie_udaje) {
        if (!$okruh_id) {
            Db::vloz('okruh', $okruh);
            Db::vloz('zdroj_okruh', $prepojovacie_udaje);
        } else {
            Db::zmen('okruh', $okruh, 'WHERE okruh_id=?', array($okruh));
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
    public function ulozZdrojKlucoveSlovo($zdroj_klucove_slovo_id,$zdroj_klucove_slovo){
      // Db::vloz('zdroj_klucove_slovo', $zdroj_klucove_slovo); 
        if (!$zdroj_klucove_slovo_id) {
            Db::vloz('zdroj_klucove_slovo', $zdroj_klucove_slovo);
        } else {
            Db::zmen('zdroj_klucove_slovo', $zdroj_klucove_slovo, 'WHERE klucove_slovo_id=?', array($zdroj_klucove_slovo_id));
        }
    
    }
     * */
     public function ulozZdrojKlucoveSlovo($zdroj_klucove_slovo){
      // Db::vloz('zdroj_klucove_slovo', $zdroj_klucove_slovo); 
            Db::vloz('zdroj_klucove_slovo', $zdroj_klucove_slovo);
    }

    /*
     * metoda odstrani zdroj s danym id zdroja
     */

    public function odstranZdroj($id) {
        Db::dotaz('DELETE FROM zdroje WHERE zdroj_id=?', array($url));
    }
    
    
    /*
     * vrati posledne ID vlozeneho zaznamu, napr ak vlozime zdroj, vrati posledne zdroj_id
     * parameter ktory vrati musi byt autoincrement
     */
    public function posledneId(){
        return Db::getLastId();
    }
    
   

}
