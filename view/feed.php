<div class = 'container'>
  <ul class = 'feed-meni'>
    <li><a href = "index.php?stranica=feed&sezona=pocetna">Pocetna sezona</a></li>
    <li><a href = "index.php?stranica=feed&sezona=underground">Underground</a></li>
    <li><a href = "index.php?stranica=feed&sezona=mainstream">Mainstream</a></li>
  </ul>
</div>
<?php
if(!isset($_GET['sezona'])){
  include "pomoc.php";
}
else{
  $konekcija = $_SESSION['baza'];
  if(isset($_GET['i'])){
    ubaci_feed($konekcija,$_GET['sezona'],$_GET['i']);
  }
  else
    ubaci_feed($konekcija,$_GET['sezona']);
  $konekcija = null;
}
?>
