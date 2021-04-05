<?php
class Korisnik {
  private $id;
  private $username;
  private $tip;
  private $sezona;

  public function __construct($id,$username,$tip,$sezona){
    $this->id = $id;
    $this->username = $username;
    $this->tip = $tip;
    $this->sezona = $sezona;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_username(){
    return $this->username;
  }

  public function get_tip(){
    return $this->tip;
  }

  public function get_sezona(){
    return $this->sezona;
  }
}
