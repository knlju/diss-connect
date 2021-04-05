<div class = 'container'>
  <div class = 'form-group'>
    <form action = 'index.php?stranica=dissuj' method = 'post' id = 'diss-track-forma'>
      <span class = 'bela'>Dissujem </span><input type = 'text' name = 'dissovan' placeholder = 'koga'
      list = 'korisnici' id = 'dissovan' required><br>
      <span class = 'bela'>Naslov diss-a </span><input type = 'text' name = 'naslov' placeholder = 'naslov diss-a' required>
      <datalist id = 'korisnici'>
        <?php
        $konekcija = $_SESSION['baza'];
        ubaci_korisnike($konekcija);
        $konekcija = null;
        ?>
      </datalist>
      <br>
      <label for="sadrzaj"><span class = 'bela'>Sadrzaj:</span></label>
      <textarea placeholder = "Ovde ide tekst diss-a" name = "sadrzaj" id = "sadrzaj" class = "form-control"
      placeholder = "ovde ide sadrzaj" rows = "12" required></textarea>
      <button onclick = 'pripremi_i_posalji();' class = 'btn btn-success' name = 'diss-track-dugme'>
        OK, Dissuj!
      </button>
      <br>
    </form>
  </div>
  <div class = 'diss-lajne'>
    <textarea cols = "2" class = 'form-control dodaj-lajnu' id = 'dodaj-lajnu'
    placeholder = 'Dodaj linije teksta za kasnije ubacivanje u tekst'></textarea>
    <button onclick = 'dodaj_lajnu();' class = "btn btn-primary" id='dodaj-lajnu-dugme'>+ Dodaj lajnu</button>
    <br>
    <p class = 'alert alert-info'>Klikni na dodatu lajnu da je dodas u track !</p>
  </div>
</div>
