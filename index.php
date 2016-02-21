<?php
/*
 * zapnutie session
 */
session_start();
$_SESSION['zdroj_na_zobrazenie']= "";
/*
 * nastavenie kodovania
 */
mb_internal_encoding("UTF-8"); 

/*
 * automaticke nacitanie triedy, ked je potrebne ju pouzit
 */
function autoloadFunkcia($trieda) {
    if (preg_match('/Kontroler$/', $trieda))
        require("kontrolery/" . $trieda . ".php");
    else
        require("modely/" . $trieda . ".php");
}

/*
 * zaregistrovanie autoloadFunkcie aby sa vykonavala ako autoloader
 */
spl_autoload_register("autoloadFunkcia");

/*
 * pripojenie k databaze pomocou udajov z phpmyadmin a nazvu databazy mysql
 */
Db::pripoj("127.0.0.1","root","root","dataleo" );

/*
 * vytvorenie instancie smerovaca ktora spracuje adresu URL a presunie nas na pozadovanu stranku 
 */
$smerovac = new SmerovacKontroler();
$smerovac->spracuj(array($_SERVER['REQUEST_URI']));
$smerovac->vypisPohlad();

//skuska pridania textu kvoli druhemu commitu
