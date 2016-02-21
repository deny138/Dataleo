<?php

class SpravcaPouzivatelov {
    /*
     * metoda ktora zasifruje heslo 
     */

    public function zasifruj($heslo) {
        $sifra = 'dbgfb4648dfrb4564';
        return hash('sha256', $heslo . $sifra);
    }

    /*
     * zaregistrovanie pouzivatela 
     */

    public function registruj($meno, $heslo, $hesloZnovu, $email) {
        //todo: doplnit vynimky ked sa hesla nezhoduju
        $pouzivatel = array('meno' => $meno, 'heslo' => $this->zasifruj($heslo), 'email' => $email);
        Db::vloz('pouzivatelia', $pouzivatel);
        //TODO: tabulka este obsahuje atribut "registrovany"
    }

    public function prihlas($meno, $heslo) {
        $pouzivatel = Db::dotazJeden('SELECT pouzivatel_id,meno,heslo,email,registrovany FROM pouzivatelia WHERE meno=? AND heslo=?', array($meno, $this->zasifruj($heslo)));
        $_SESSION['pouzivatel'] = $pouzivatel;
    }
    
    public function odhlas(){
        unset($_SESSION['pouzivatel']);
    }
    
    //todo: metoda na zistenie ci je registrovany ? treba?

}
