<?php
/*
    csv-combiner.php
    combines all rows of n input csv files 
        with new column containing the file name
        and outputs to stdout
    stdout does not need to be fopened or fclosed, just writted into

    TO EXECUTE on GIT BASH:
    $ php.exe csv-combiner.php *input_file_path > output_file.csv
*/

// flag for outputting the csv header only once 
$has_header = FALSE; 


// $argv: array containing the command line arguments
// [0]: csv-converter, [1]+ file names as inputs 
foreach ($argv as $csv_path) {
    // skip the first value of the php file
    if (substr_count($csv_path, "csv-combiner.php") == 0) {
        // split file path
        $csv_file = explode("/", $csv_path);
        // get file name from last position
        $csv_file = $csv_file[count($csv_file) - 1];


        // open csv file
        // assumption: this file exists on the path
        $f_input = fopen($csv_path, "r");
        $first_row = TRUE; 

        // Output one line at a time until end-of-file
        while(!feof($f_input)) {

            // remove eol chars
            $row = rtrim(fgets($f_input));

            // skip first row of second+ file
            if ( ($has_header && $first_row) || ($row == "") ){
                $first_row = FALSE; 
                continue; 
            }

            // output header first
            if(!$has_header) {
                $has_header = TRUE;
                $row = $row .",filename\n";
                fwrite(STDOUT, $row);
            }
            // output the data rows 
            else {   
                $row = $row . ",". $csv_file . "\n";
                fwrite(STDOUT, $row);
            }
        }
        // end of a csv input
        fclose($f_input);
    }
}
/* for the |...|...| formatting, replace or insert 
    line:11 $has_header = FALSE; 
            //seperator line between the header and rows for |...| formatting
            $seperator = "|";

    replace line:34
            $row = explode(",",trim(fgets($f_input))); 
            // format with |...|...| 
            $new_row = "|";
            foreach( $row as $col) {
                $new_row = $new_row . $col . "|"; 
                // for the seperator row
                if(!$has_header) {
                    $seperator = $seperator . str_repeat("-", count($col)) . "|";
                }
    replace line 45: 
            $new_row = $new_row ."|filename|\n";
    insert after line 47: 
            // output the seperator line
            fwrite(STDOUT, $seperator."\n");
    replace line 50: 
            $new_row = $new_row . $csv_file . "|\n";
*/
?>
