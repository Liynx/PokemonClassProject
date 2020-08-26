<?php
/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 9/9/2019
 * Time: 11:13 AM
 */
require_once("Pokemon.php");

class Bulbasaur extends Pokemon
{

    function __construct($weight, $HP, $lat, $long)
    {
        parent::__construct("Bulbasaur", "bulbasaur.png", $weight, $HP, $lat, $long, "Grass");
    }

    function attack(Pokemon $target)
    {
        //echo "Bulbasaur is Vine Whipping<br>";
        $target->HP = $target->HP - $this->getDamage();
    }

    function getDamage(){
        return $this->getWeight()*0.3;
    }

    function setLatitude($lat)
    {
        parent::setLatitude($lat); // TODO: Change the autogenerated stub
    }

    function setLongitude($long)
    {
        parent::setLongitude($long); // TODO: Change the autogenerated stub
    }

    function getLatitude()
    {
        return parent::getLatitude(); // TODO: Change the autogenerated stub
    }

    function getLongitude()
    {
        return parent::getLongitude(); // TODO: Change the autogenerated stub
    }
}

