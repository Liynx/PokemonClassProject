<?php 
session_start();

// for development, show all the errors!
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Simple test script to test loading pokemon from files and 
// the getJSON() function
// croftd: modified to loop through and test the battle function
// 
function __autoload ($class_name) {
	require_once $class_name . '.php';
}

$world = World::getInstance();

// croftd TODO: need to implement file parsing to load pokemon from text files
echo "<br>Loading pokemon from the two text files...";

$loadedPokemon = $world->load();

$i = 0;

echo "<br>Starting looping through to test the world battle function!<br>";
for ($i=0; $i < 10; $i++) {

	echo "<br>Round[" . $i . "] Here is the current JSON:<br>";
	echo "<pre>";
	echo $world->getJSON();
	echo "</pre>";

	echo "<br>Round[" . $i . "] battle()";
	$world->battle();
	echo "<br>Round[" . $i . "]  messages from the world: " . $world->getMessage();
	$world->clearMessage();
}
