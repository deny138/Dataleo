<?php

class UvodKontroler extends Kontroler {
/*tlacitko odhlasit presmerovava pouzivatela na adresu uvod/odhlasit
 * preto sa metoda pyta ci je tento parameter /odhlasit
 * a ak ano tak sa prevedie odhlasenie a presmeruje sa na uvod
 * 
 * ak vsak pouzivatel bol presmerovany na adresu /uvod z ineho kontrolera tak sa 
 * obycajne presmeruje na uvod bez odhlasenia
 */
   
    public function spracuj($parametre) {
        $spravcaPouzivatelov= new SpravcaPouzivatelov();
        if ((!empty($parametre[0]) && $parametre[0]=='odhlasit')){
            $spravcaPouzivatelov->odhlas();
            $this->presmeruj('uvod');
        }
        
        $this->hlavicka['titulok'] = 'DATALEO';
        $this->hlavicka['klucove_slova'] = 'DATALEO';
        $this->hlavicka['popis'] = 'DATALEO';
        $this->pohlad = 'uvod';
    }
    
    

}
