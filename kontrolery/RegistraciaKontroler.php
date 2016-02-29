<?php

class RegistraciaKontroler extends Kontroler {

    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'Registrácia';
        /* ak boli vyplnene udaje na registraciu a odoslane vykonaju sa instrukcie v tele ifu
         * ak udaje neboli zadane a stranka sa nacitava prvykrat vykona sa zobrazenie pohladu registraciu
         */
        if ($_POST) {
            try {
                $spravcaPouzivatelov = new SpravcaPouzivatelov();
                $spravcaPouzivatelov->registruj($_POST['meno_reg'], $_POST['heslo_reg'], $_POST['heslo_znovu_reg'], $_POST['email']);
                $spravcaPouzivatelov->prihlas($_POST['meno_reg'], $_POST['heslo_reg']);
                $pouzivatel = $spravcaPouzivatelov->vratPouzivatela(); //kvoli vypisu
                $this->pridajSpravu('Registrácia prebehla úspešne. Vitajte v svojej knižnici ' .
                        $pouzivatel['meno'] . '.');
                $this->presmeruj('zdroje');
            } catch (ChybaPouzivatela $chyba) {
                $this->pridajSpravu($chyba->getMessage());
            }
        }
        $this->pohlad = 'registracia';
    }

}
