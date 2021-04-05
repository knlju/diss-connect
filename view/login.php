<div class="login-content">
    <form action="index.php?stranica=login" method="post">
        <input type="text" name="username" placeholder="Unesite korisnicko ime"><br>
        <input type="password" name="password" placeholder="Sifra" onkeyup='proveri_duzinu_sifre();' id='password'><br>
        <input type="submit" value="Uloguj se!" name="button-login">
    </form>
    <div id="poruka-login"></div>
</div>
