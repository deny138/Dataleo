<?php

class PrazdnoKontroler extends Kontroler{
    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'Knižnica';

        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();
        
        if (!$pouzivatel)
            $this->presmeruj('prihlasenie');
      
        $this->data['pouzivatel'] = $pouzivatel;
        $this->pohlad='prazdno';
        
    }

}
