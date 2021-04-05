<!DOCTYPE html>
<html>
<head>
    <title>Pocetna</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../view/css/style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script
      src = "https://code.jquery.com/jquery-3.2.1.min.js"
      integrity = "sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
      crossorigin = "anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="site">
    <header class="header">
        <div class="header-menu">
      <?php
      if(isset($_GET['stranica'])){
        if($_GET['stranica'] === 'pocetna'){
          echo "<div class=\"logo\">
            <h1>DISS-CONNECT</h1>
            <h3> <em>Talking shit for shock value - boy you aint real</em></h3>
        </div>";
        }
      }
      else{
        echo "<div class=\"logo\">
          <h1>DISS-CONNECT</h1>
          <h3> <em>What died on your neck? Oh - it's your head.</em></h3>
      </div>";
      }
      ?>
        <div id="nav">
          <nav>
              <ul class="levo">
                <?php
                if(!isset($_SESSION['ulogovan'])){
                  echo "<li><a href=\"index.php?stranica=login\">Login</a></li>
                        <li><a href=\"index.php?stranica=register\">Register</a></li>";
                }
                else{
                  $korisnik = $_SESSION['korisnik'];
                  $id = $korisnik->get_id();
                  echo "<li><a href=\"index.php?stranica=profil&profil=$id\">Moj profil</a></li>";
                }
                ?>
              </ul>
              <ul class="desno">
                  <li><a href="index.php?stranica=pomoc">Pomoc</a></li>
                  <li><a href="index.php?stranica=feed">Feed</a></li>
                  <li><a href="index.php?stranica=pocetna">Pocetna</a></li>
                  <?php
                    if(isset($_SESSION['ulogovan']))
                      echo "<li><a href = 'index.php?stranica=dissuj'>Dissuj</a></li>";
                  ?>
              </ul>
          </nav>
        </div>
        </div>
    </header>
