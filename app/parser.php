<?php

/**
 *
 */
function processDataFile(){
    $row = 1;
    if (($handle = fopen(DATA_FILE_PATH, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if($row == 1){ $row++; continue; } //Skip the first record as it is a header titles
            //todo start processing
        }
        fclose($handle);
    }
}
