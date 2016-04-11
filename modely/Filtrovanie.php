<?php

class Filtrovanie {
    
    public function FiltrujPodlaNazvu($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `nazov` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaPodnazvu($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `podnazov` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    //TODO : druh je vyberovy
     public function FiltrujPodlaDruhu($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `druh` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
 
    public function FiltrujPodlaVydania($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `vydanie` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaMiestaVydania($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `miesto_vydania` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaVydavatelstva($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `vydavatelstvo` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaRoku($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `rok_vydania` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaIsbn($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `isbn` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaIssn($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `issn` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaDoi($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `doi` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaStran($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `strany` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaUrl($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `url` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaDatumuAktualizacie($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `datum_aktualizacie` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaDatumuPridania($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `datum_pridania` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
    public function FiltrujPodlaHodnotenia($parameter,$pouzivatel) {

        return DB::dotazVsetky('SELECT `zdroj_id`,`pouzivatel_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `vydavatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                        . 'WHERE `hodnotenie` = ? AND `pouzivatel_id` = ?', array($parameter,$pouzivatel));
    }
   
    
}
