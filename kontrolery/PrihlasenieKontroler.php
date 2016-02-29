<?php

class PrihlasenieKontroler extends Kontroler {

    public function spracuj($parametre) {

        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $spravcaPouzivatelov->vratPouzivatela();

        $this->hlavicka['titulok'] = 'Registrácia';
        /* ak boli vyplnene udaje na prihlasenie a odoslane vykonaju sa instrukcie v tele ifu
         * ak udaje neboli zadane a stranka sa nacitava prvykrat vykona sa zobrazenie pohladu prihlasenie
         */

        if ($_POST) {
            try {
                $spravcaPouzivatelov->prihlas($_POST['login'], $_POST['heslo']);
                $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();  //vrati udaje o pouzivatelovi len kvoli vypisu mena         
                $this->pridajSpravu('Prihlásenie prebehlo úspešne. Vitajte vo svojej vlastnej knižnici ' .
                        $pouzivatel['meno'] . '.');
                $this->presmeruj('zdroje');
            } catch (ChybaPouzivatela $chyba) {
                $this->pridajSpravu($chyba->getMessage());
            }
        }
        $this->pohlad = 'prihlasenie';
    }

}
