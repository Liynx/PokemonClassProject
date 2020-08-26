<?php
/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 9/16/2019
 * Time: 10:35 AM
 */

abstract class Character{

    protected $name;
    protected $image;
    protected $lat;
    protected $long;

    public function __construct($name, $image, $lat, $long)
    {
        $this->name = $name;
        $this->image = $image;
        $this->lat = (float)$lat;
        $this->long = (float)$long;
    }

    function getJSON(){
        // each Pokemon or Character needs to return a string something similar to:
        // "lat":  49.159720,"long":  -123.907773,"name": "Paras","image": "paras.png" }

        //$json = json_encode($this);

        $json = '{"lat": ' . $this->lat;
        $json .= ',"long": ' . $this->long;
        $json .= ',"name": ' . '"' . $this->name . '"';
        $json .= ',"image": ' . '"' . $this->image . '" }';
        return $json;


    }

    public function __toString() {
        return $this->name;
    }


}