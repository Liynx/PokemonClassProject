<?php
/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 9/9/2019
 * Time: 11:38 AM
 */

require_once("Pokemon.php");

class Trainer extends Character {

    protected $pokedex;

    function __construct($name, $image ,$lat, $long){

        parent::__construct($name, "trainer.png" ,$lat, $long);
        $this->pokedex = array();

    }

    public function removePokemon(Pokemon $pokemon)
    {
        if (($key = array_search($pokemon, $this->pokedex)) !== false) {
            unset($this->pokedex[$key]);
        }
    }

    public function getPokemon() {
        return $this->pokedex;
    }

    public function add(Pokemon $pokemon){
        $this->pokedex[] = $pokemon;
    }

    public function printAll(){

        foreach($this->pokedex as $pokemon){
            echo $pokemon . "<br>";
        }
    }

    public function attackAll(){

        foreach($this->pokedex as $pokemon){
            $pokemon->attack();
        }
    }

    public function getJSON()
    {
        return parent::getJson();
    }

    public function getLatitude() {
        return $this->lat;
    }

    public function setLatitude($lat) {
        $this->lat = $lat;
    }

    public function getLongitude() {
        return $this->long;
    }

    public function setLongitude($long) {
        $this->long = $long;
    }


}