<?php

class PrihlasenieKontroler extends Kontroler{
    public function spracuj($parametre) {
        
        $spravcaPouzivatelov= new SpravcaPouzivatelov();
        $spravcaPouzivatelov->vratPouzivatela();
        //$this->presmeruj('prihlasenie');
        $this->hlavicka['titulok'] = 'Registrácia';
        
        if($_POST){
        try{
            $spravcaPouzivatelov->prihlas($_POST['login'],$_POST['heslo']);
           $pouzivatel= $spravcaPouzivatelov->vratPouzivatela();  //vrati udaje o pouzivatelovi         
            $this->pridajSpravu('Prihlásenie prebehlo úspešne. Vitajte vo svojej vlastnej knižnici.   Kontrola: pouzivatel_id='.$pouzivatel['pouzivatel_id']);
            $this->presmeruj('zdroje');
        }
        catch(ChybaPouzivatela $chyba){
            $this->pridajSpravu($chyba->getMessage());
        }
        }
        $this->pohlad='prihlasenie';
       
        
    }

}


