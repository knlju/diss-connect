<div class='feed-card'>
    <h4 style='color:white;'>
        <?= $assoc['naslov'] ?>
    </h4>
    <div>
        <?= $assoc['sadrzaj'] ?>
    </div>
    <div>
        <a href="index.php?stranica=profil&profil=<?= $assoc['id_user'] ?>">
            <?= $disser['username'] ?>
        </a>
        >
        <a href="index.php?stranica=profil&profil=<?= $assoc['id_dissovan'] ?>">
            <?= $dissovan['username'] ?>
        </a>
    </div>
    <div class='<?= $assoc['id_track'] ?>'>
        respect: <?= $respect['RESPECT'] ?>
    </div>
    <?php
    if (isset($_SESSION['ulogovan'])) {
        if (isset($_SESSION['ulogovan'])) {
            echo "
                <button id='{$assoc['id_track']}' class = 'respect-dodaj'
                onclick = 'dodaj_respect(this.id);'>
            ";
            if ($dodao_respect['DA'] > 0) {
                echo "diss-respect";
            } else
                echo "respect";
            echo "</button>";
        }
    }
    ?>
    <p><a href='?stranica=beef&id=<?= $assoc['id_beef'] ?>'>Beef iz sezone <?= $sezona ?></a></p>
</div>
<hr>