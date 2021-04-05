<?php
require_once "../includes/classKorisnik.php";
session_start();
require_once "../includes/helpers.php";
$db = mysqli_connect("localhost","root","","dissconnect") or die("rip baza");
mysqli_query($db, "SET NAMES UTF8");
$id_track = $_POST['track_id'];
$korisnik = $_SESSION['korisnik'];
$id_user = $korisnik->get_id();

$query = "SELECT id_user FROM trekovi
          WHERE id_user = $id_user
          AND id_track = $id_track";
$rez = mysqli_query($db,$query);
if($rez->num_rows == 1){

  $query = "DELETE FROM respect
            WHERE id_track = $id_track";
  $rez = mysqli_query($db,$query);

  $query = "DELETE FROM trekovi WHERE id_user = $id_user
            AND id_track = $id_track";
  $rez = mysqli_query($db,$query);

  if($rez){
    if($db->affected_rows > 0){
      echo 1;
    }
    else{
      echo 0;
    }
  }
  else{
    echo 0;
  }
}
else{
  echo 0;
}

$db = null;
