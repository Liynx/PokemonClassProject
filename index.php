<?php
/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 9/9/2019
 * Time: 11:57 AM
 */

function __autoload($class_name){
    require_once $class_name . '.php';
}
//require_once("map.php");



//$trainer1->printAll();
echo "<br>";
//$trainer1->attackAll();
echo "<br>";

// get a World variable
$world = World::getInstance();

$loadedPokemon = $world->load();

echo "<img src=images/pidgey.png>";

$i = 0;

echo "<br>Starting looping through to sest the world battle function!<br>";

for($i=0; $i < 10; $i++) {
    //echo "<br>Round[" . $i . "] Here is the current JSON:<br>";
    //echo "<pre>";
    //echo $world->getJSON();
    //echo "</pre>";
    echo "<br>Round[" . $i . "] battle()";
    $world->battle();
    echo "<br>Round[" . $i . "]  messages from the world: " . $world->getMessage();
    $world->clearMessage();


};
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
//print_r($world->getTrainersPokemon());

//echo "$trainer->getLatitude()";
//print_r($world->getJSON());














//echo $world->getJSON();

//print_r($world->getTrainersPokemon());



//$world->loadPokemon('wildPokemon.txt');





//$trainer1->add($bulba1);
//$trainer1->add($bulba2);
//$trainer1->add($paras1);
//$trainer1->add($paras2);
//$trainer1->add($pika);


//$trainer1 = new Trainer("Ash", "trainer.png", "47.01", "49.13");

//$bulba1 = new Bulbasaur("15.2 lbs", "60", "49.13", "47.8");
//$bulba2 = new Bulbasaur("15.6 lbs", "70", "47.23", "49.13");
//$bulba3 = new Bulbasaur("16 lbs", "50", "40.65", "42.95");

//$pika = new Pikachu("13.2 lbs", "50", "49.18", "48.19");

//$paras1 = new Paras("11.9 lbs", "40", "39.11", "42.75");
//$paras2 = new Paras("11.2 lbs", "39", "42.11", "45.73");

//echo "<br>Here is the json for Paras2";

//echo $paras2->getJSON();

//echo "<br>Here is the var_dump";
//var_dump($paras2);


//$classes = get_declared_classes();

//foreach($classes as $class) {
//    echo $class . "<br>";
//}


