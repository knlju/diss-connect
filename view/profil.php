<?php
$db = $_SESSION['baza'];
$id_profil = $_GET['profil'];
ubaci_profil($db,$id_profil);
$db = null;
