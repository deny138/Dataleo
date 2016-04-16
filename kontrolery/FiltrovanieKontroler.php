<?php

class FiltrovanieKontroler extends Kontroler {

    public function spracuj($parametre) {

        $this->hlavicka['titulok'] = 'Filtrovanie';
        $spravcaPouzivatelov = new SpravcaPouzivatelov();
        $pouzivatel = $spravcaPouzivatelov->vratPouzivatela();
        $filtrovanie = new Filtrovanie;
        $pouzivatelovo_id = $pouzivatel['pouzivatel_id'];

        if (!empty($_GET['podla'])) {
            $podla = $_GET['podla'];
            
             if ($podla == 'okruh') {
                $kliknute_na_okruh = $_GET['filter'];
                $zdroje = $filtrovanie->FiltrujPodlaOhruhu($kliknute_na_okruh, $pouzivatelovo_id);
                $this->data['zdroje'] = $zdroje;
                $this->pohlad = 'filtrovane_zdroje';
            }
             if ($podla == 'slovo') {
                $kliknute_na_slovo = $_GET['filter'];
                $zdroje = $filtrovanie->FiltrujPodlaSlova($kliknute_na_slovo, $pouzivatelovo_id);
                $this->data['zdroje'] = $zdroje;
                $this->pohlad = 'filtrovane_zdroje';
            }
        } 
        elseif ($_POST) {
            $podla_coho_filtrovat = $_POST['filtrovanie'];
            $parameter_filtrovania = $_POST['parameter_filtrovania'];

            if ($podla_coho_filtrovat == 'f_nazov')
                $zdroje = $filtrovanie->FiltrujPodlaNazvu($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_podnazov')
                $zdroje = $filtrovanie->FiltrujPodlaPodnazvu($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_druh')
                $zdroje = $filtrovanie->FiltrujPodlaNazvu($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_vydanie')
                $zdroje = $filtrovanie->FiltrujPodlaVydania($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_miesto_vydania')
                $zdroje = $filtrovanie->FiltrujPodlaMiestaVydania($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_vydavatelstvo')
                $zdroje = $filtrovanie->FiltrujPodlaVydavatelstva($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_rok_vydania')
                $zdroje = $filtrovanie->FiltrujPodlaRoku($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_isbn')
                $zdroje = $filtrovanie->FiltrujPodlaIsbn($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_issn')
                $zdroje = $filtrovanie->FiltrujPodlaIssn($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_doi')
                $zdroje = $filtrovanie->FiltrujPodlaDoi($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_strany')
                $zdroje = $filtrovanie->FiltrujPodlaStran($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_url')
                $zdroje = $filtrovanie->FiltrujPodlaUrl($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_datum_aktualizacie')
                $zdroje = $filtrovanie->FiltrujPodlaDatumuAktualizacie($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_datum_pridania')
                $zdroje = $filtrovanie->FiltrujPodlaDatumuPridania($parameter_filtrovania, $pouzivatelovo_id);
            if ($podla_coho_filtrovat == 'f_hodnotenie')
                $zdroje = $filtrovanie->FiltrujPodlaHodnotenia($parameter_filtrovania, $pouzivatelovo_id);

            $this->data['zdroje'] = $zdroje;
            $this->pohlad = 'filtrovane_zdroje';
        }
        else {


            $this->pohlad = 'filtrovanie';
        }
        $this->data['pouzivatel'] = $pouzivatel;
    }

}
