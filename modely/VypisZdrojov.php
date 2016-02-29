<?php

class VypisZdrojov {
    /*
     * zakladna nepouzita metoda, ktora vrati vsetky zdroje v databaze
     */

    public function vratZdroje() {
        return Db::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `nakladatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`');
    }

    /*
     *  vrati zdroj ktory sa bude ukazovat po kliknutu nanho, podla parametra zdroj_id
     */

    public function vratZdrojPodlaZdrojId($id) {

        return DB::dotazJeden('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `nakladatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `zdroj_id`=?', array($id));
    }

    /*
     * vrati vsetky zdroje spolu s autorom, budu sa vypisovat ako zoznam pre daneho pouzivatela
     */

    public function vratZdrojeSautorom($pouzivatel_id) {
        return Db::dotazVsetky('SELECT z.zdroj_id,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `nakladatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka`, '
                        . 'a.autor_id, `titul_pred`,`meno`,`priezvisko`,`titul_po` '
                        . 'FROM `zdroj` AS z  LEFT JOIN autor_zdroj AS az ON az.zdroj_id = z.zdroj_id  '
                        . 'LEFT JOIN `autor` AS a ON a.autor_id=az.autor_id WHERE `pouzivatel_id`=?', array($pouzivatel_id));
    }

}
