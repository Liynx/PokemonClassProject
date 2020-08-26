<?php

// croftd: since we registered an __autoload we shouldn't need to require these
//require_once "Paras.php";
//require_once "Pikachu.php";
//require_once "Bulbasaur.php";
//require_once "Pidgey.php";

include_once("Database.php");
/**
 * Main class for our Pokemon program that stores:
 *
 * A List of Wild Pokemon
 * A Single Trainer (who has a list of Pokemons on a Pokedex)
 *
 * Note this is a Singleton and there should only every be one World object.
 */
class World
{
    static $instance;

    private $trainer; // Trainer
    private $message = "";
    private $wildPokemon = array(); // Array to store WildPokemon

    /**
     * @return World object - this is a Singleton.
     * Note with languages such as PHP that load everything on each request
     * Singleton not as important...
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new World();
        }
        return self::$instance;
    }

    /**
     * Used to reset the World (reset to null), for use with SESSION management (see getPokemon.php)
     */
    public static function reset()
    {
        self::$instance == null;
    }

    private function __construct()
    {
        $this->trainer = new Trainer('Liam', 'trainer.png', 49.159706, -123.907757);
    }

    // Also required for Singleton
    private function __clone()
    {
    }

    /**
     * @return array of the Wild Pokemon in the world.
     */
    public function getWildPokemon()
    {
        return $this->wildPokemon;
    }

    /**
     * @return array of the Trainer's pokemon
     */
    public function getTrainersPokemon()
    {
        return $this->trainer->getPokemon();
    }

    /**
     *
     */
    public function removePokemon(Pokemon $pokemon)
    {
        if (($key = array_search($pokemon, $this->wildPokemon)) !== false) {
            unset($this->wildPokemon[$key]);
        }
    }

    /**
     * Call this method before battle or getJSON to load the wild and trainer pokemon into the World
     */

    //without database

    public function loadPokemon($filename)
    {

        $lines = file($filename);

        $pokemons = array();

        foreach ($lines as $line) {

            if ($line[0] == "#") {

                continue;
            }


            list($name, $weight, $HP, $lat, $long) = explode(",", $line);


            $pokemon = new $name($weight, $HP, $lat, $long);

            $pokemons[] = $pokemon;
        }

        return $pokemons;
    }

    public function loadFile()
    {
        $this->wildPokemon = $this->loadPokemon('wildPokemon.txt');

        //echo "<br>Finished loading the wild pokemons!";

        $trainerPokemon = $this->loadPokemon('trainerPokemon.txt');

        foreach ($trainerPokemon as $poke) {
            $this->trainer->add($poke);
        }
    }


    //For Database -----------

    public function load() {
        $this->wildPokemon = $this->loadWildPokemon();
        $trainerPokemon = $this->loadTrainerPokemon();

        foreach($trainerPokemon as $poke) {
            $this->trainer->add($poke);
        }
    }

    public function loadWildPokemon(): array {

        try {
            $db = Database::connect();
            $result = $db->query("SELECT * FROM `pokemons` WHERE wild='yes'");
            $all = $result->fetchAll();
            //var_dump($all);

            $pokemons = array();
            foreach ($all as $entry) {

                // format is ($weight, $HP, $lat, $long)
                $poke = new $entry["name"]($entry["weight"], $entry["hp"], $entry["lat"], $entry["long"] );
                $pokemons[] = $poke;
            }

            return $pokemons;
        } catch(PDOException $e){
            echo "<br>Error loading pokemon from database, mesage is: ". $e->getMessage();
        }
    }

    public function loadTrainerPokemon() : array {

        try {
            $db = Database::connect();
            $result = $db->query("SELECT * FROM `pokemons` WHERE wild='no'");
            $all = $result->fetchAll();

            $pokemons = array();
            foreach($all as $entry) {

                $poke = new $entry["name"]($entry["weight"], $entry["hp"], $entry["lat"], $entry["long"]);
                $pokemons[] = $poke;
            }
            return $pokemons;
        }catch(PDOException $e){
            echo "<br>Error Oopsie No Pokemon". $e->getMessage();
        }
    }



    /**
     * When called, this function will find the nearest wild Pokemon, move the Trainer and his Pokemon to this
     * location, and attack. See the image created by @matthewt for the flow chart (in the REW301_code repo)
     */
    public function battle()
    {

        $nearestWild = null;
        $nearestDistance = 0;

        // step 1 - find the nearest wild pokemon
        foreach ($this->wildPokemon as $wild) {

            $distance = $this->distance($this->trainer->getLatitude(), $this->trainer->getLongitude(),
                $wild->getLatitude(), $wild->getLongitude());

            // the first time through, we will assume this distance is the closest one, as we haven't checked the others...
            if ($nearestWild == null) {
                $nearestWild = $wild;
                $nearestDistance = $distance;
            } elseif ($distance < $nearestDistance) {
                // this $wild is closer than the last calculation...
                $nearestWild = $wild;
                $nearestDistance = $distance;
            }
        }

        if ($nearestWild == null) {
            $this->addMessage("No wild pokemon found! Battle over!!");
            return;
        }

        $this->addMessage("Found the next nearest wild pokemon: " . $wild->getName());


        // step 2 - move the trainer and take turns attacking

        $this->addMessage("Trainer is: " . $this->trainer);

        // update the Trainer and the Trainer's pokemon to these co-ordinates
        $this->trainer->setLatitude($nearestWild->getLatitude());
        $this->trainer->setLongitude($nearestWild->getLongitude());

        $i = 0.001;

        foreach ($this->trainer->getPokemon() as $tPoke) {
            // same idea - update the lat/long for each of the trainer's $tPoke
            $tPoke->setLatitude($nearestWild->getLatitude());
            $tPoke->setLongitude($nearestWild->getLongitude());
            //$tPoke->setLatitude($tPoke->getLatitude()+0.002);
            $tPoke->setLatitude($tPoke->getLatitude()+$i);
            $i = $i + 0.002;
            // etc...
        }

        foreach ($this->trainer->getPokemon() as $tPoke) {

            while ($tPoke->getHP() > 0 && $nearestWild->getHP() > 0) {
                $tPoke->attack($nearestWild);
                $this->addMessage("Trainer_" . $tPoke->getName() . " attacked Wild " . $nearestWild->getName() . " HP:" . $nearestWild->getHP());
                if ($nearestWild->getHP() > 0) {
                    $nearestWild->attack($tPoke);
                }
            }

            if ($nearestWild->getHP() <= 0) {
                $this->removePokemon($nearestWild);
                $this->addMessage("Wild Pokemon " . $nearestWild->getName() . " is dead" . "<br>");
                break;
            }
            if ($tPoke->getHP() <= 0) {
                $this->trainer->removePokemon($tPoke);
                $this->addMessage("Trainer Pokemon " . $nearestWild->getName() . " is dead" . "<br>");
                continue;
            }

        }

        // the next time through, you'll need an else if statement to check if the next $wild's distance is less than $nearestDistance, and if so set this as $nearestWild

        //$this->addMessage("Battling... ");

        // Does nothing yet...
    }

    /**
     * Helper function to calculate distance between two points
     *
     * @param $lat1 - first lat coord
     * @param $lon1 - first long coord
     * @param $lat2 - second lat coord
     * @param $lon2 - second long coord
     * @return float - distance in kilometers between the two coords
     */
    function distance($lat1, $lon1, $lat2, $lon2)
    {

        //echo "<br>Calculating distance between $lon1 and $lon2";

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        $dist = acos($dist);
        $dist = rad2deg($dist);

        $miles = $dist * 60 * 1.1515;

        // return value in kilometers – or maybe we want meters precision?
        return $miles * 1.609344;
    }

    /**
     * @param $message - Add a String message to send back with the next call to getJSON()
     */
    public function addMessage($message)
    {
        $this->message = $this->message . ", " . $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function clearMessage()
    {
        // reset the messeage that is sent with JSON back to blank
        $this->message = "";
    }

    /**
     * @return string - a valid JSON String containing a list of all the Trainer and Pokemon Google Map markers.
     *
     * The format should look like:
     *
     * <pre>
     *
     *{"markers": [{"lat":  49.159720,"long":  -123.907773,"name": "Paras","image": "paras.jpg" },{"lat":  49.171154,"long":  -123.971443
     * ,"name": "Pidgey","image": "pidgey.jpg" },{"lat":  49.152864,"long":  -123.94873
     * ,"name": "Paras","image": "paras.jpg" },{"lat":  49.1350026,"long":  -123.9220046
     * ,"name": "Paras","image": "paras.jpg" },{"lat":  49.178561,"long":  -123.857631
     * ,"name": "Bulbasaur","image": "bulbasaur.jpg" },{"lat":  49.162736,"long":  -123.892478
     * ,"name": "Bulbasaur","image": "bulbasaur.jpg" },{"lat":  49.1790103,"long":  -123.9199447
     * ,"name": "Pidgey","image": "pidgey.jpg" },{"lat":  49.1675630,"long":  -123.9383125,"name": "Pidgey","image": "pidgey.jpg" } ],
     * "message": "BattleCount[9] Server Messages: Trainer starting with pokemon index"}
     *
     * </pre>
     */
    public function getJSON()
    {


        // croftd: This is just an example to make the initial map and getPokemon.php
        // talk to each other
        // To complete the lab you will have to loop through all the wild pokemon
        // and all the trainer's pokemon and add build a JSON string to return

        $jsonToReturn = '{"markers": [{"lat":  49.159720,"long":  -123.907773,"name": "Paras","image": "paras.png" },{"lat":  49.171154,"long":  -123.971443
    ,"name": "Pidgey","image": "pidgey.png" },{"lat":  49.152864,"long":  -123.94873
    ,"name": "Paras","image": "paras.png" },{"lat":  49.1350026,"long":  -123.9220046
    ,"name": "Paras","image": "paras.png" },{"lat":  49.178561,"long":  -123.857631
    ,"name": "Bulbasaur","image": "bulbasaur.png" },{"lat":  49.162736,"long":  -123.892478
    ,"name": "Bulbasaur","image": "bulbasaur.png" },{"lat":  49.1790103,"long":  -123.9199447
    ,"name": "Pidgey","image": "pidgey.png" },{"lat":  49.1675630,"long":  -123.9383125,"name": "Pidgey","image": "pidgey.png" } ],
    "message": "BattleCount[0] <br>Server Messages: This is just test data!"}';

        $jsontry2 = '{"markers": [{"lat":  49.159720,"long":  -123.907773
    ,"name": "Paras","image": "paras.jpg" },{"lat":  49.171154,"long":  -123.971443
    ,"name": "Pidgey","image": "pidgey.png" },{"lat":  49.152864,"long":  -123.94873
    ,"name": "Paras","image": "paras.jpg" },{"lat":  49.1350026,"long":  -123.9220046
    ,"name": "Paras","image": "paras.jpg" },{"lat":  49.178561,"long":  -123.857631
    ,"name": "Bulbasaur","image": "bulbasaur.png" },{"lat":  49.162736,"long":  -123.892478
    ,"name": "Bulbasaur","image": "bulbasaur.png" },{"lat":  49.1790103,"long":  -123.9199447
    ,"name": "Pidgey","image": "pidgey.png" },{"lat":  49.1675630,"long":  -123.9383125,"name": "Pidgey","image": "pidgey.png" } ], "message": "Some Message , Battling..." }';
        // Start with the initial json String
        $test = '{"markers": [';
        // rather than return the hard coded json, lets build it from the actual pokemon
        foreach ($this->getWildPokemon() as $poke) {

            $individualJSON = $poke->getJSON();
            $test .= $individualJSON . ',';
        }

        // next add the JSON for the trainer marker
        $test .= $this->trainer->getJSON() . ",";

        // finally add the json markers for the trainer's pokemon
        foreach ($this->getTrainersPokemon() as $poke) {
            $poke->setLatitude($poke->getLatitude()+0.002);
            $individualJSON = $poke->getJSON();
            $test .= $individualJSON . ',';
        }

        $test = substr($test, 0, -1);
        // before we return $test, we also need to add the 'message' ket
        $test .= '], "message": ' . '"' . $this->getMessage() . '"' . '}';
        return $test;
        //return $jsonToReturn;
        //return $jsontry2;
    }

    /**
     * Function to load Pokemon objects from a csv file
     *
     * @param $filename
     * @return array containing Pokemon objects
     */
    //public function loadPokemon($filename)
    //{
    // croftd: currently does nothing
    //    return null;

    //}
}
