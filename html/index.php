<?php
require_once("../includes/classKorisnik.php");
session_start();
require_once("../includes/helpers.php");

$db = mysqli_connect("localhost","root","","dissconnect") or die("rip baza");
mysqli_query($db, "SET NAMES UTF8");
$_SESSION['baza'] = $db;

$poruka = null;

if(isset($_POST['diss-track-dugme'])){
  if(isset($_POST['dissovan']) && isset($_POST['naslov']) && isset($_POST['sadrzaj'])){
    // proveri da li je korisnik pravilno unet
    if(!da_li_korisnik_postoji($db,$_POST['dissovan'])){
      $poruka = array("Korisnik ne postoji",1);
    }
    else{
      $korisnik = $_SESSION['korisnik'];
      $poruka = dodaj_diss($db,$_POST['dissovan'],$_POST['naslov'],
                           $_POST['sadrzaj'],$korisnik->get_id(),$korisnik->get_sezona());
    }
  }
  else{
    $poruka = array("Nisu uneti svi elementi forme",1);
  }
}

if(isset($_POST['new-username'])){
  if(isset($_POST['new-password'])){
    if(!registruj_korisnika($_POST['new-username'],$_POST['new-password'],$db)){
      $poruka = array("Neuspesna registracija",1);
    }
  }
  else{
    $poruka = array("Uneti sifru",1);
  }
}

if(isset($_POST['username'])){
  if(isset($_POST['password'])){
    if(!uloguj_korisnika($_POST['username'],$_POST['password'],$db)){
      $poruka = array("Pogresan username ili password",1);
    }
    else
      $poruka = array("Uspesno ulogovan",0);
  }
  else
    $poruka = array("Uneti sifru",1);
}

// salje stranicu

// ako je zatrazena stranica
if(isset($_GET['stranica'])){
  // ako je korisnik ulogovan
  if(isset($_SESSION['ulogovan'])){
    // ako stranice nisu login, logout ili register
    if(!($_GET['stranica'] === 'login'
         || $_GET['stranica'] === 'register'
         || $_GET['stranica'] === 'logout')){
      render_page("{$_GET["stranica"]}.php",$poruka);
    }
    // ako je stranica logout izloguj
    else{
      if($_GET['stranica'] === 'logout'){
        session_unset();
        session_destroy();
        $poruka = array("Uspesno izlogovan",0);
      }
      render_page("pocetna.php",$poruka);
    }
  }
  else{
    if($_GET['stranica'] === 'dissuj'){
      $poruka = array('Morate se prvo ulogovati da biste dissovali',1);
      render_page("pocetna.php",$poruka);
    }
    else
      render_page("{$_GET['stranica']}.php",$poruka);
  }
}
else{
    render_page("pocetna.php",$poruka);
}

unset($_SESSION['baza']);
mysqli_close($db);
