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

            //Validate the Record now
            $record = sanitizeRecord($data);
            $output['clean'][] = $record;
            $row++;
        }
        fclose($handle);
    }
    var_dump(print_r($output, true));
}


/**
 * @param $record
 * @return mixed
 */
function sanitizeRecord($record){
    $record[0] = ucfirst(strtolower(filter_var($record[0], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)));
    $record[1] = ucfirst(strtolower(filter_var($record[1], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)));
    $record[2] = strtolower(filter_var($record[2], FILTER_SANITIZE_EMAIL));
    return $record;
}
