<?php

/*
 * smerovac podla url adresy nasmeruje poziadavky na spravny kontroler
 * podla adresy zisti ktory kontroler volame a ulozi ho do premennej $kontroler
 * smerovackontroler vezme URL, spracuje ju a zavola prislusny kontroler napr ZdrojeKontroler
 * oba budu mat pohlad, smerovac ma rozlozenie stranky a vlozeny kontroler ma sablonu s obsahom stranky
 */

class SmerovacKontroler extends Kontroler {

    protected $kontroler;

    //$parametre=pole kde na prvom mieste- URL adresa
    public function spracuj($parametre) {
        $naparsovanaURL = $this->parsujURL($parametre[0]);
        //ak nieje zadany ziaden kontroler presmeruje sa na uvodny clanok
        if (empty($naparsovanaURL[0]))
            $this->presmeruj('uvod');

        //array_shift-ziskanie .parametra a jeho vymazanie
        $triedaKontroleru = $this->prerobeniePomlciek(array_shift($naparsovanaURL)) . 'Kontroler';
        //cize ak existuje kontroler pozrieme sa ci existuje trieda kontroleru a vytvorime jej instanciu
        if (file_exists('kontrolery/' . $triedaKontroleru . '.php'))
            $this->kontroler = new $triedaKontroleru;
        else
            $this->presmeruj('chyba');

        $this->kontroler->spracuj($naparsovanaURL); //vnoreny kontroler prevedie svoju metodu spracuj 
        //nastavenie premennych pre sablonu
        $this->data['spravy'] = $this->vratSpravy();

        $this->data['titulok'] = $this->kontroler->hlavicka['titulok'];
        $this->data['popis'] = $this->kontroler->hlavicka['popis'];
        $this->data['klucove_slova'] = $this->kontroler->hlavicka['klucove_slova'];

        $this->pohlad = 'rozlozenie';
    }

    private function parsujURL($url) {
        //parse_url oddeli domenu od parametrov 
        //z www.domena.sk/parameter1/parameter2 dostaneme do $rozdelenaCesta: /parameter1/parameter2
        $naparsovanaURL = parse_url($url);
        //odstranenie lomitka na zaciatku
        $naparsovanaURL["path"] = ltrim($naparsovanaURL["path"], "/");
        //odstranenie medzier okolo
        $naparsovanaURL["path"] = trim($naparsovanaURL["path"]);
        //pole $rozdelenaCesta
        $rozdelenaCesta = explode("/", $naparsovanaURL["path"]);
        return $rozdelenaCesta;
    }

    /* co to spravi?
     * http://localhost/uvod/zdroje/zdroj1 
     * ->Array $rozdelenaCesta ( [0] => zdroje [1] => zdroj1 ) UvodKontroler
     */

    /* zistenie nazvu triedy kontrolera
     * nazov kontroleru pride ako 1.parameter v naparsovanej URL
     * nazov pride ako vypis-uzivatelov 
     * potrebujeme spravit VypisUzivatelovKontroler
     */

    private function prerobeniePomlciek($text) {
        $veta = str_replace('-', '', $text);
        $veta = ucwords($veta);
        $veta = str_replace(' ', '', $veta);
        return $veta;
    }

}
