<?php
/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 9/9/2019
 * Time: 10:37 AM
 */

require_once('Character.php');
require_once('Database.php');

abstract class Pokemon extends Character
{
    //protected $name;
    //protected $image;
    protected $weight;
    protected $HP;
    //protected $latitude;
    //protected $longitude;
    protected $type;


    public function __construct($name, $image, $weight, $HP, $lat, $long, $type ){

        parent::__construct($name, $image, $lat, $long);
        //$this->name = $name;
        //$this->image = $image;
        $this->weight = $weight;
        $this->HP = $HP;
        //$this->latitude = $latitude;
        //$this->longitude = $longitude;
        $this->type = $type;

    }

    public function attack(Pokemon $target){
        $target->HP = $target->HP - $this->getDamage();
    }

    abstract function getDamage();

    public function __toString()
    {
        //$temp = "Name: " . $this->name;

        //$temp .= $this->name;

        return "Name: " . $this->name . " Image: " . $this->image . " Weight: " . $this->weight . " HP: " . $this->HP . " Latitude: " . $this->lat . " Longitude: " . $this->long . " Type: " . $this->type;
        // TODO: Implement __toString() method.
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }


    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }


    /**
     * @return mixed
     */
    public function getHP()
    {
        return $this->HP;
    }

    public function setHP($hp){
        $this->hp = $hp;
    }


    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->lat;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->long;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($long)
    {
        $this->long = $long;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }




}