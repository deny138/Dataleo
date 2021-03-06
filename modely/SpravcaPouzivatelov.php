<?php

class SpravcaPouzivatelov {
    /*
     * metoda ktora zasifruje heslo pomocou funkcie hash
     * hash- jednocestna sifra, do DB sa uklada sifra nie plaintext
     */

    public function zasifruj($heslo) {
        $sifra = 'dbgfb4648dfrb4564';
        return hash('sha256', $heslo . $sifra);
    }

    /*
     * zaregistrovanie pouzivatela 
     */

    public function registruj($zaskrtavatko,$meno, $heslo, $hesloZnovu, $email) {
        if (!$zaskrtavatko)
            throw new ChybaPouzivatela('Pri registrácii musíte súhlasiť so spracovaním údajov.');
        if ($heslo != $hesloZnovu)
            throw new ChybaPouzivatela('Heslá sa nezhodujú.');
        if ((empty($meno)) OR ( empty($heslo)) OR ( empty($hesloZnovu)) OR ( empty($email)))
            throw new ChybaPouzivatela('Vyplňte prosím všetky polia.');
        $pouzivatel = array('meno' => $meno, 'heslo' => $this->zasifruj($heslo), 'email' => $email);
        try {
            Db::vloz('pouzivatelia', $pouzivatel);
        } catch (PDOException $chyba) {
            throw new ChybaPouzivatela('Používateľ s týmto menom alebo emailom už existuje.');
        }

        //TODO: tabulka este obsahuje atribut "registrovany"
    }

    public function prihlas($meno, $heslo) {
        $pouzivatel = Db::dotazJeden('SELECT pouzivatel_id,meno,heslo,email,registrovany '
                        . 'FROM pouzivatelia WHERE meno=? AND heslo=?', array($meno, $this->zasifruj($heslo)));
        if (!$pouzivatel)
            throw new ChybaPouzivatela('Neplatné meno alebo heslo.');
        $_SESSION['pouzivatel'] = $pouzivatel;
    }

    public function odhlas() {
        unset($_SESSION['pouzivatel']);
    }

    public function vratPouzivatela() {
        if (isset($_SESSION['pouzivatel'])) {
            return $_SESSION['pouzivatel'];
        }
        return null;
    }

    //todo: metoda na zistenie ci je registrovany ? treba?
    //todo: emailove overovanie, posielanie hesla na email
}
