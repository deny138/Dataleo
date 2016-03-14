<?php

class PrazdnoKontroler extends Kontroler{
    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'Knižnica';

        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();
      
        $this->data['pouzivatel'] = $pouzivatel;
        $this->pohlad='prazdno';
        
    }

}
