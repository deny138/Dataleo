<?php

class VypisZdrojov {

    public function vratZdroje() {
        return Db::dotazVsetky('SELECT `zdroj_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `nakladatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`');
    }
    
    public function vratZdroj($id){
        return DB::dotazJeden('SELECT `zdroj_id`,`druh_zdroja`, `nazov`,`podnazov`, `vydanie`,'
                        . '`miesto_vydania`, `nakladatelstvo`, `rok_vydania`, `isbn`, `issn`,`doi`,'
                        . '`strany`,`url`,`datum_aktualizacie`,`datum_pridania`, `hodnotenie`, `poznamka` FROM `zdroj`'
                . 'WHERE `zdroj_id`=?',array($id));
         
        
    }
}
