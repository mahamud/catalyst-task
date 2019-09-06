<?php

/**
 *
 */
function processDataFile(){
    $output = array('clean' => array(), 'errors' => array());
    $row = 1;
    if (($handle = fopen(DATA_FILE_PATH, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if($row == 1){ $row++; continue; } //Skip the first record as it is a header titles
            if(sizeof($data) != COLUMN_NUMBERS){
                $output['errors'][] = 'Record number '.$row.' has invalid column numbers.';
                $row++; continue;
            }
            $row++;
        }
        fclose($handle);
    }
}


/**
 * @param $data
 */
function validateRecord($data){

}
