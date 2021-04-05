<div class="login-content">
    <form action="index.php?stranica=register" method="post">
        <div>
            <input type="text" name="new-username" placeholder="Unesite korisnicko ime"
                   class="col-sm-3 form-control" required><br>
        </div>
        <div id="sifre">
            <input type="password" name="new-password" placeholder="Sifra"
                   id="new-password" class="form-control col-sm-3" onkeyup="proveri_sifre();" required>
            <input type="password" placeholder="Ponovi sifru"
                   id="new-password-confirm" class="form-control col-sm-3" onkeyup="proveri_sifre();" required>
        </div>
        <input type="submit" value="Registruj se!" name="button-register" disabled>
    </form>
    <p id="poruka-register"></p>
    <p id="poruka-register-1"></p>
</div>