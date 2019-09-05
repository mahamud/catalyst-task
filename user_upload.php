<?php

require_once ('config/config.php');
require_once ('app/helper.php');

echo 'Starting the Catalyst Task ... please wait ...'.PHP_EOL;
sleep(2);

echo 'Options passed are : '.PHP_EOL.PHP_EOL;

$arguments = processArguments($argv);

var_dump(print_r($arguments, true));

if(is_array($arguments) == false or sizeof($arguments) < 1){
    displayErrorMessage("Error: Invalid arguments passed", true);
}

$argumentArrayKeys = array_keys($arguments);

endScript();

