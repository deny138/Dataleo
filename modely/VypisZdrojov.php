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
     */

    public function vratZdrojeSautorom($pouzivatel_id) {
        return Db::dotazVsetky('SELECT z.zdroj_id,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka`, '
                        . 'a.autor_id, `titul_pred`,`meno`,`priezvisko`,`titul_po` '
                        . 'FROM `zdroj` AS z  LEFT JOIN autor_zdroj AS az ON az.zdroj_id = z.zdroj_id  '
                        . 'LEFT JOIN `autor` AS a ON a.autor_id=az.autor_id WHERE `pouzivatel_id`=?', array($pouzivatel_id));
    }
    
    /*
     * ak nieje zadane ID zdroja tak vlozime zdroj ako novy s nasledujucim id/autoincrement
     * ak je zadane id zdroja tak dany zdroj prepiseme
     */
    public function ulozZdroj($id,$zdroj){
        if(!$id)
            Db::vloz ('zdroj',$zdroj);
        else
            Db::zmen('zdroj',$zdroj,'WHERE zdroj_id=?',array($id));
    }

    /*
     * metoda odstrani zdroj s danym id zdroja
     */
    public function odstranZdroj($id){
        Db::dotaz('DELETE FROM zdroje WHERE zdroj_id=?',array($url));
    }

}
