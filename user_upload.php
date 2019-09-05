<?php

require_once ('config/config.php');
require_once ('app/helper.php');

echo 'Starting the Catalyst Task ... please wait ...'.PHP_EOL;
sleep(2);

echo 'Options passed are : '.PHP_EOL.PHP_EOL;

$arguments = processArguments($argv);

var_dump(print_r($arguments, true));

// Validate arguments and display error message accordingly
if(is_array($arguments) == false || sizeof($arguments) < 1 || sizeof($arguments) > 6){
    displayErrorMessage("Error: Invalid arguments passed. Execute php user_upload --help for details.", true);
}

//Get all argument keys
$argumentArrayKeys = array_keys($arguments);
$result = array_diff($argumentArrayKeys, ARGUMENTKEYS); //Validate against valid argument keys
if(is_array($result) == false || sizeof($result) > 0){
    displayErrorMessage("Error: Invalid argument key passed. Execute php user_upload --help for details.", true);
}

//var_dump(print_r($result), true);

//Validate against valid argument keys


endScript();

