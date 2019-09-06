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
            $record[] = array('data_type' => 'string', 'capitalized' => true, 'value' => $data[0], 'key' => 'first_name');
            $record[] = array('data_type' => 'string', 'capitalized' => true, 'value' => $data[1], 'key' => 'last_name');
            $record[] = array('data_type' => 'email', 'value' => $data[2], 'key' => 'email');
            $record = sanitizeAndCleanRecord($record);

            //Validate for Business Rules
            try {
                validateBusinessRules($record);
            }
            catch (Exception $exception){
                $output['errors'][] = 'Record number '.$row.' has error. '.$exception->getMessage();
                $row++; continue;
            }

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
                    $sanitizedRecord[$value['key']] = !empty($value['capitalized'] && $value['capitalized'] == true) ? ucfirst($cleanValue) : $cleanValue;
                    break;
                case 'email':
                    $sanitizedRecord[$value['key']] = strtolower(filter_var($value['value'], FILTER_SANITIZE_EMAIL));
                    break;
            }
        }
    }
    return $sanitizedRecord;
}


/**
 * @param $record
 * @throws Exception
 */
function validateBusinessRules($record){
    foreach($record as $key => $value){
        switch ($key) {
            case 'email':
                if (filter_var($value, FILTER_VALIDATE_EMAIL) != true) {
                    throw new Exception('Invalid email address provided.');
                }
                break;
        }
    }
}
