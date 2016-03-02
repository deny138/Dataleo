<?php

class EditorKontroler extends Kontroler {

    public function spracuj($parametre) {
        $this->hlavicka['titulok'] = 'Editor zdrojov';

        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();

        $vypisZdrojov = new VypisZdrojov();

        $zdroj = array(
            'zdroj_id' => 5,
            'pouzivatel_id' => '2',
            'druh_zdroja' => '',
            'nazov' => '',
            'podnazov' => '',
            'meno_autora' => '',
            'priezvisko_autora' => '',
            'vydanie' => '',
            'miesto_vydania' => '',
            'vydavatelstvo' => '',
            'rok_vydania' => '',
            'isbn' => '',
            'issn' => '',
            'doi' => '',
            'strany' => '',
            'url' => '',
            'datum_aktualizacie' => '',
            'datum_pridania' => '21.02.2011',
            'hodnotenie' => '',
            'poznamka' => '',
        );
        //ak je odoslany formular
        if ($_POST) {
            //ziskanie zdroja z $_post
            $kluce = array('zdroj_id', 'pouzivatel_id', 'druh_zdroja', 'nazov', 'podnazov', 
                'vydanie', 'miesto_vydania', 'vydavatelstvo', 'rok_vydania', 'isbn', 'issn', 'doi',
                'strany', 'url', 'datum_aktualizacie', 'datum_pridania', 'hodnotenie', 'poznamka',);
            $zdroj= array_intersect_key($_POST,  array_flip($kluce));
            //ulozenie clanku do db
            $vypisZdrojov->ulozZdroj($_POST['zdroj_id'], $zdroj);
            $this->pridajSpravu('Clanok bol uspesne ulozeny');
            $this->presmeruj('zdroje');
        }
        else if (!empty($parametre[0])){
            $nacitanyZdroj= $vypisZdrojov->vratZdrojPodlaZdrojId($id);//tu parameter podla url
            if($nacitanyZdroj)
                $zdroj=$nacitanyZdroj;
            else
                $this->pridajSpravu ('Zdroj nenajdeny');
        }

        $this->data['pouzivatel'] = $pouzivatel;
        $this->data['zdroj'] = $zdroj;
        $this->pohlad = 'editor';
    }

}
