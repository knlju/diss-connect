<?php
function render_page($page, $poruka = null)
{
    /*
    * prikazuje zahtevanu stranicu
    * ako stranica ne postoji, prikazuje 404 stranicu i poruku o gresci
    */
    require "../view/templates/header.php";

    if (isset($poruka)) {
        if ($poruka[1] === 1)
            echo "<div class = 'alert alert-danger'>{$poruka[0]}</div>";
        else
            echo "<div class = 'alert alert-success'>{$poruka[0]}</div>";
    }

    if (file_exists("../view/{$page}"))
        require "../view/{$page}";
    else {
        echo "<div class = 'greska'>
            <p>
              Nazalost, {$page} ne postoji :(
            </p>
          </div>";
        require "../view/404.php";
    }
    require "../view/templates/footer.php";
}

function uloguj_korisnika($username, $hashed_pwd, $db)
{
    /*
    * uloguje korisnika
    * prihvati username, hashovan password i bazu i proveri da li su unete informacije dobre
    * ako jesu, napravi nov objekat korisnik i sacuva ga u session-u
    * vraca 1 ako je logovanje uspelo
    *       0 ako logovanje nije uspelo
    */
    $hashed_pwd = md5($hashed_pwd . "hash-kljuc");
    if (!isset($SESSION['ulogovan'])) {
        if (strlen($username) < 6 || strlen($hashed_pwd) < 6) {
            echo "<div style='width:100%;background:red;color:white'>
              Korisnicko ime i sifra moraju imati bar 6 karaktera
            </div>";
        }

        $username = mysqli_real_escape_string($db, $username);
        $hashed_pwd = mysqli_real_escape_string($db, $hashed_pwd);
        $query = "SELECT id_user,tip,sezona
              FROM korisnik
              WHERE username = '{$username}'
              AND password = '{$hashed_pwd}'";
        $rezultat = mysqli_fetch_assoc(mysqli_query($db, $query));
        if ($rezultat) {
            $niz = $rezultat;
            $id = $niz['id_user'];
            $tip = $niz['tip'];
            $sezona = $niz['sezona'];
            $korisnik = new Korisnik($id, $username, $tip, $sezona);
            $_SESSION['ulogovan'] = true;
            $_SESSION['korisnik'] = $korisnik;
            return 1;
        } else {
            return 0;
        }
    } else
        return 0;
}

function registruj_korisnika($username, $hashed_pwd, $db)
{
    /*
    * upisuje korisnika u bazu
    * vraca 1 ako je korisnik registrovan i ulogovan
    *       0 u drugom slucaju
    * prikazuje poruku ako nije ispunjen uslov za duzinu sifre ili korisnickog imena
    */
    $pwd = $hashed_pwd;
    $hashed_pwd = md5($hashed_pwd . "hash-kljuc");
    if (!isset($_SESSION['ulogovan'])) {
        if (strlen($username) < 6 || strlen($hashed_pwd) < 6) {
            echo "<div style='width:100%;background:red;color:white'>
              Korisnicko ime i sifra moraju imati bar 6 karaktera
            </div>";
        } else {
            $username = mysqli_real_escape_string($db, $username);
            $hashed_pwd = mysqli_real_escape_string($db, $hashed_pwd);
            $query = "INSERT INTO korisnik (`id_user`,`username`,`password`,`tip`,`sezona`)
                VALUES(null,'{$username}','{$hashed_pwd}','igrac','pocetna')";
            $rezultat = mysqli_query($db, $query);
            if ($rezultat) {
                echo "<div style='width:100%;background:green;color:white'>USPESNA REGISTRACIJA</div>";
                return uloguj_korisnika($username, $pwd, $db);
            } else {
                return 0;
            }
        }
    } else
        return 0;
}

function dodaj_diss($db, $dissovan, $naslov, $sadrzaj, $id, $sezona)
{
    // dodaje diss
    $dissovan = mysqli_real_escape_string($db, $dissovan);
    $naslov = mysqli_real_escape_string($db, $naslov);
    $sadrzaj = mysqli_real_escape_string($db, $sadrzaj);
    $sezona = mysqli_real_escape_string($db, $sezona);
    $query = "SELECT id_beef FROM beef
            WHERE id_dissovan = (SELECT id_user FROM korisnik
                                 WHERE username = '$dissovan')
            AND id_disser = $id
            AND sezona = '$sezona'";
    $rez = mysqli_query($db, $query);
    if ($rez) {
        if ($rez->num_rows > 0) {
            $query = "SELECT COUNT(id_track) AS num_id_track
                FROM trekovi,beef
                WHERE trekovi.id_beef = beef.id_beef
                AND id_disser = $id
                AND trekovi.id_dissovan = (SELECT id_user FROM korisnik
                                   WHERE username = '$dissovan')";

            $rez = mysqli_query($db, $query);
            $rez_assoc = mysqli_fetch_assoc($rez);
            $num_id_track = $rez_assoc['num_id_track'];

            if ($num_id_track > 2) {
                return array("Ne mozete dodavati vise pesama u ovaj beef u ovoj sezoni", 1);
            }

            $query = "SELECT id_beef
                FROM beef
                WHERE id_disser = $id
                AND id_dissovan = (SELECT id_user FROM korisnik
                                   WHERE username = '$dissovan')
                AND sezona = '$sezona'";
            $rez = mysqli_query($db, $query);
            $rez_assoc = mysqli_fetch_assoc($rez);
            $id_beef = $rez_assoc['id_beef'];

            $query = "INSERT INTO trekovi
                (id_user,id_dissovan,id_beef,Naslov,sadrzaj,sezona,datum)
                VALUES($id,(SELECT id_user FROM korisnik
                                   WHERE username = '$dissovan'),$id_beef,'$naslov','$sadrzaj','$sezona',now())";
            $rez = mysqli_query($db, $query);
            if ($db->affected_rows > 0) {
                return array("Diss uspesno dodat", 0);
            }
        } else {
            $query = "INSERT INTO beef (id_disser,id_dissovan,sezona)
                VALUES($id,(SELECT id_user FROM korisnik
                                     WHERE username = '$dissovan'),'$sezona')";
            $rez = mysqli_query($db, $query);

            $query = "SELECT id_beef
                FROM beef
                WHERE id_disser = $id
                AND id_dissovan = (SELECT id_user FROM korisnik
                                   WHERE username = '$dissovan')
                AND sezona = '$sezona'";
            $rez = mysqli_query($db, $query);
            $rez_assoc = mysqli_fetch_assoc($rez);
            $id_beef = $rez_assoc['id_beef'];

            $query = "INSERT INTO trekovi
                (id_user,id_dissovan,id_beef,Naslov,sadrzaj,sezona,datum)
                VALUES($id,(SELECT id_user FROM korisnik
                                   WHERE username = '$dissovan'),$id_beef,'$naslov','$sadrzaj','$sezona',now())";
            $rez = mysqli_query($db, $query);
            if ($rez) {
                if ($db->affected_rows > 0) {
                    return array("Diss uspesno dodat", 0);
                }
            }
        }
    }
    return array("Greska pri dodavanju diss-a", 1);
}

function da_li_korisnik_postoji($db, $korisnik)
{
    $dissovan = mysqli_real_escape_string($db, $korisnik);
    $query = "SELECT id_user FROM korisnik WHERE username = '{$dissovan}'";
    $rez = mysqli_query($db, $query);
    if ($rez) {
        if ($rez->num_rows > 0) {
            return 1;
        }
    }
    return 0;
}

function ubaci_korisnike($db)
{
    // poziva se u dissuj.php
    $rez = mysqli_query($db, "SELECT username FROM korisnik");
    while ($korisnik = mysqli_fetch_row($rez)) {
        echo "<option value = '{$korisnik[0]}'>";
    }
}

function ubaci_feed($db, $sezona, $brojac_stranica = 0)
{
    if (isset($_SESSION['korisnik']))
        $korisnik = $_SESSION['korisnik'];
    $brojac_ispisanih_stranica = 0;
    $sezona = mysqli_real_escape_string($db, $sezona);
    if ($brojac_stranica > 0) {
        $brojac_stranica--;
        $brojac_stranica *= 5;
    }
    $query = "SELECT trekovi.id_track,naslov,sadrzaj,trekovi.id_user AS id_user,
                   trekovi.id_dissovan AS id_dissovan,trekovi.sezona,trekovi.id_beef,datum
          	FROM trekovi
            WHERE sezona = '$sezona'
            ORDER BY datum DESC
          	LIMIT $brojac_stranica,5";
    $rez = mysqli_query($db, $query);
    if (!$rez) {
        echo "<p>Jos uvek nema trekova u ovoj sezoni..</p>";
        return null;
    } else if (mysqli_num_rows($rez) < 1) {
        echo "<p>Jos uvek nema trekova u ovoj sezoni..</p>";
        return null;
    } else {
        while ($assoc = mysqli_fetch_assoc($rez)) {
            $query = "SELECT username FROM korisnik WHERE id_user = {$assoc['id_user']}";
            $disser = mysqli_fetch_assoc(mysqli_query($db, $query));

            $query = "SELECT username FROM korisnik WHERE id_user = {$assoc['id_dissovan']}";
            $dissovan = mysqli_fetch_assoc(mysqli_query($db, $query));

            $query = "SELECT COUNT(id_user) AS RESPECT FROM respect WHERE id_track = {$assoc['id_track']}";
            $respect = mysqli_fetch_assoc(mysqli_query($db, $query));

            if (isset($_SESSION['korisnik'])) {
                $query = "SELECT COUNT(id_user) AS DA FROM respect
                  WHERE id_track = {$assoc['id_track']} AND id_user = {$korisnik->get_id()}";
                $dodao_respect = mysqli_fetch_assoc(mysqli_query($db, $query));
            }

            include "../view/templates/feedCard.php";

            $brojac_ispisanih_stranica++;
        }
        $brojac_stranica = intval($brojac_stranica);
        $brojac_stranica = $brojac_stranica + 2;
        $query = "SELECT COUNT(trekovi.id_track) AS BROJ_STRANA FROM trekovi WHERE sezona = '$sezona'";
        $BROJ_STRANA = mysqli_fetch_assoc(mysqli_query($db, $query));
        $a = ceil($BROJ_STRANA['BROJ_STRANA'] / 5);
        if ($brojac_ispisanih_stranica > 4 || ($brojac_stranica - 2) * 5 + 1 > 0) {
            $brojac_stranica = floor($brojac_stranica / 5);
            if ($brojac_ispisanih_stranica > 4) {
                $brojac_stranica += 2;
                echo "<a href = '?stranica=feed&sezona=$sezona&i={$brojac_stranica}'>Sledeca stranica</a>";
                $brojac_stranica -= 2;
            }
            if ($brojac_stranica <= $a && $brojac_stranica >= 1) {
                echo "<a href = '?stranica=feed&sezona=$sezona&i={$brojac_stranica}'>Prethodna stranica</a>";
            }
        } else {
            $brojac_stranica = floor($brojac_stranica / 5);
        }
        $brojac_stranica = $brojac_stranica + 1;
        echo "<p>Strana {$brojac_stranica} od {$a}</p>";
        echo "<p>Prikazuje se 5 disseva po stani</p>";
    }
}

function ubaci_beef($db, $id_beef)
{
    $id_beef = intval(mysqli_real_escape_string($db, $id_beef));
    $korisnik = $_SESSION['korisnik'];
    $query = "SELECT trekovi.id_track,naslov,sadrzaj,trekovi.id_user AS id_user,
                   trekovi.id_dissovan AS id_dissovan,trekovi.sezona,trekovi.id_beef,datum
            FROM trekovi
            WHERE trekovi.id_beef = $id_beef
            ORDER BY datum DESC";
    $rez = mysqli_query($db, $query);
    if (mysqli_num_rows($rez) < 1) {
        echo "<p>Nepostojeci beef</p>";
        return null;
    }
    $assoc = mysqli_fetch_assoc($rez);

    $query = "SELECT username FROM korisnik WHERE id_user = {$assoc['id_user']}";
    $disser = mysqli_fetch_assoc(mysqli_query($db, $query));

    $query = "SELECT username FROM korisnik WHERE id_user = {$assoc['id_dissovan']}";
    $dissovan = mysqli_fetch_assoc(mysqli_query($db, $query));
    echo "<div class = 'naslov-beefa'>";
    echo "<p>
          <a href = '?stranica=profil&profil={$assoc['id_user']}'>
            {$disser['username']}
          </a>
          >
          <a href = '?stranica=profil&profil={$assoc['id_dissovan']}'>
            {$dissovan['username']}
          </a>
        </p>";
    echo "<p><a href = '?stranica=beef&id={$assoc['id_beef']}'>Beef iz sezone {$assoc['sezona']}</a></p>";
    echo "</div>";
    do {
        $query = "SELECT COUNT(id_user) AS RESPECT FROM respect WHERE id_track = {$assoc['id_track']}";
        $respect = mysqli_fetch_assoc(mysqli_query($db, $query));

        $query = "SELECT COUNT(id_user) AS DA FROM respect
              WHERE id_track = {$assoc['id_track']} AND id_user = {$korisnik->get_id()}";
        $dodao_respect = mysqli_fetch_assoc(mysqli_query($db, $query));
        echo "<div class = 'beef-parent-{$assoc['id_track']}'>";
        echo "<h4 style = 'color:white;'>{$assoc['naslov']}</h4>";
        echo "<p>{$assoc['sadrzaj']}</p>";
        echo "<p class = '{$assoc['id_track']}'>respect: {$respect['RESPECT']}</p>";
        if (isset($_SESSION['ulogovan'])) {
            if (isset($_SESSION['ulogovan'])) {
                echo "<button id = '{$assoc['id_track']}' class = 'respect-dodaj'
            onclick = 'dodaj_respect(this.id);'>";
                if ($dodao_respect['DA'] > 0) {
                    echo "diss-respect";
                } else echo "respect";
                echo "</button>";
                if ($korisnik->get_id() === $assoc['id_user']) {
                    echo "<button id = 'obrisi-{$assoc['id_track']}' style = 'margin:15px;'
                 onclick='obrisi_diss(this.id)'>
                  Obrisi diss
                </button>";
                }
            }
        }
        echo "</div>";
        echo "<hr>";
    } while ($assoc = mysqli_fetch_assoc($rez));
}

function ubaci_profil($db, $id_korisnik)
{
    $id_korisnik = intval(mysqli_real_escape_string($db, $id_korisnik));
    $query = "SELECT COUNT(id_user) AS UKUPNO_RESPECTA
            FROM respect
            WHERE id_track IN(SELECT id_track
                              FROM trekovi
                              WHERE id_user = $id_korisnik)";
    $rez = mysqli_fetch_assoc(mysqli_query($db, $query));

    $query = "SELECT username FROM korisnik WHERE id_user = '$id_korisnik'";
    $rez2 = mysqli_fetch_assoc(mysqli_query($db, $query));

    $ukupno_respecta = $rez['UKUPNO_RESPECTA'];
    echo "<div class = 'container alert alert-warning'>Korisnik: {$rez2['username']}</div>";
    if ($ukupno_respecta < 1) {
        echo "<div class = 'container alert alert-warning'>Nema respecta</div>";
    } else {
        echo "<div class = 'container alert alert-warning'>Respect: {$ukupno_respecta}</div>";
    }

    $query = "SELECT id_beef
            FROM beef
            WHERE id_beef IN (SELECT id_beef FROM beef WHERE id_disser = $id_korisnik)";
    $rez3 = mysqli_query($db, $query);
    $brojac = 1;
    $query = "SELECT username
            FROM korisnik
            WHERE id_user IN (SELECT id_dissovan FROM beef
                              WHERE id_disser = $id_korisnik)";
    $rez4 = mysqli_query($db, $query);
    while ($assoc = mysqli_fetch_assoc($rez3)) {
        $assoc2 = mysqli_fetch_assoc($rez4);
        $brojac++;
        echo "<div class = 'container alert alert-warning'>";
        echo "<p style='color:#151617;'>
            <a>
              {$rez2['username']}
            </a>
            >
            <a>
              {$assoc2['username']}
            </a>
          </p>";
        echo "<p><a href = '?stranica=beef&id={$assoc['id_beef']}'>Pogledaj beef</a></p>";
        echo "</div>";
    }
}

function promeni_sezonu($db, $id_track)
{
    $query = "SELECT COUNT(id_user) AS UKUPNO_RESPECTA
            FROM respect WHERE id_track IN (SELECT id_track FROM trekovi
                                            WHERE id_user IN (SELECT id_user FROM trekovi
                                                              WHERE id_track = $id_track))";
    $rez = mysqli_fetch_assoc(mysqli_query($db, $query));
    if ($rez['UKUPNO_RESPECTA'] > 10) {
        if ($rez['UKUPNO_RESPECTA'] > 25) {
            $query = "UPDATE korisnik
                SET sezona = 'mainstream'
                WHERE id_user IN (SELECT id_user
                                  FROM trekovi
                                  WHERE id_track = $id_track)";
            $rez = mysqli_query($db, $query);
        } else {
            $query = "UPDATE korisnik
                SET sezona = 'underground'
                WHERE id_user IN (SELECT id_user
                                  FROM trekovi
                                  WHERE id_track = $id_track)";
            $rez = mysqli_query($db, $query);
        }
    }
}
