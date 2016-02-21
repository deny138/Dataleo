<?php

class RegistraciaKontroler extends Kontroler {

    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'Registrácia';

        if ($_POST) {
            try {
                $spravcaPouzivatelov = new SpravcaPouzivatelov();
                $spravcaPouzivatelov->registruj($_POST['meno_reg'], $_POST['heslo_reg'], $_POST['heslo_znovu_reg'], $_POST['email']);
                $this->pridajSpravu('Registrácia prebehla úspešne.');
                $this->presmeruj('uvod');
            } catch (ChybaPouzivatela $chyba) {
                $this->pridajSpravu($chyba->getMessage());
            }
        }
        $this->pohlad = 'registracia';
    }

}
