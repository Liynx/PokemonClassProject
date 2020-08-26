<?php
/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 9/9/2019
 * Time: 11:36 AM
 */

require_once("Pokemon.php");

class Paras extends Pokemon{

    function __construct($weight, $HP, $lat, $long)
    {
        parent::__construct("Paras", "paras.png", $weight, $HP, $lat, $long, "Bug");
    }

    function attack(Pokemon $target){
        //cho "Paras is Stun Sporing<br>";
        $target->HP = $target->HP - $this->getDamage();
    }

    function getDamage(){
        return $this->getWeight()*0.8;
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

    function setHP($hp){
        parent::setHP($hp);
    }

    function getHP(){
        return parent::getHP();
    }
}