<?php

class AdministraciaKontroler extends Kontroler {
/*
 * v pohlade uvod je odklik na administraciu s nazvom : Vstup do vlastnej kniznice
 * ak na neho pouzivatel klikne a je prihlaseny-> presmeruje ho na kontroler Zdroje
 * a tym padom sa vypisu vsetky jeho zdroje 
 * ak na neho klikne a nieje prihlaseny-> presmeruje ho na prihlasenie, resp
 * s prihlasenia sa moze dostat na registraciu kde sa po zaregistrovani aj prihlasi
 *  a presmeruje sa automaticky na zdroje
 */
    public function spracuj($parametre) {
        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $prihlaseny = $spravcaPouzivatelov->vratPouzivatela();
        /* 
         * funkcia vratPouzivatela vrati bud pole s udajmi o pouzivatelovi alebo null 
         * ak pouzivatel prihlaseny nieje a dana session neexistuje
         */
        if ($prihlaseny == null)
            $this->presmeruj('prihlasenie');
        $this->presmeruj('zdroje');
    }

}
