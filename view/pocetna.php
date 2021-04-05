<div class="center">
    <div class="slider">
        <div class="slike s-1"></div>
        <div class="slike s-2"></div>
        <div class="slike s-3"></div>
        <div class="slike s-4"></div>
    </div>
</div>
<div class="forma"></div>
<div class="row">
    <div class="col-lg-2">

    </div>
    <div class="col-lg-8 text-center"><?php
        if (!isset($_SESSION['ulogovan']))
            echo "
<div class = \"login-form-container\">
  <form action = \"index.php?stranica=login\" method = \"post\">
    <input type = \"text\" name = \"username\" placeholder = \"Korisnicko ime\">
    <input type = \"password\" name = \"password\" placeholder = \"Sifra\">
    <input type = \"submit\" value = \"Uloguj se!\">
    <button class='reg' disabled><a href = \"index.php?stranica=register\">Registruj se?</a></button>
  </form>
</div>
"
        ?></div>
</div>
<div class="col-lg-2">

</div>
