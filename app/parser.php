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

            //Sanitize the Record now
            $record[] = array('data_type' => 'string', 'capitalized' => true, 'value' => $data[0]);
            $record[] = array('data_type' => 'string', 'capitalized' => true, 'value' => $data[1]);
            $record[] = array('data_type' => 'email', 'value' => $data[2]);
            $record = sanitizeAndCleanRecord($record);

            //Validate for Business Rules

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
function sanitizeAndCleanRecord($record){
    $sanitizedRecord = [];
    foreach($record as $key => $value){
        if(!empty($value['data_type'])) {
            switch ($value['data_type']) {
                case 'string':
                    $cleanValue = strtolower(filter_var($value['value'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
                    $sanitizedRecord[] = !empty($value['capitalized'] && $value['capitalized'] == true) ? ucfirst($cleanValue) : $cleanValue;
                    break;
                case 'email':
                    $sanitizedRecord[] = strtolower(filter_var($value['value'], FILTER_SANITIZE_EMAIL));
                    break;
            }
        }
    }
    return $sanitizedRecord;
}
