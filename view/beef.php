<div class = 'beef'>

  <?php
  $konekcija = $_SESSION['baza'];
  ubaci_beef($konekcija,$_GET['id']);
  $konekcija = null;
  ?>

</div>
