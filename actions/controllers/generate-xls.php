<?php

if (empty($_GET['number'])) {
    exit('No data');
}

$_GET['duration'] = (int)$_GET['duration'];
$searchResults = search($_GET);

//group the records by Caller ID
$searchResultsByCaller = array();
foreach ($searchResults as $record) {
    $callerId = $record['number_from'];
    $searchResultsByCaller[$callerId][] = $record;
}
unset($searchResults);

//sort the each caller's records by the duration
foreach ($searchResultsByCaller as $callerId => $callerRecords) {
    if (sizeof($callerRecords) < 2) {
        continue;
    }
    usort($callerRecords, 'sort_by_duration');
    $searchResultsByCaller[$callerId] = $callerRecords;
}

$records = array(
    'billable' => array(),
    'non-billable' => array(),
    'duplicates' => array()
);

foreach ($searchResultsByCaller as $callerId => $callerRecords) {

    //all private callers are treated separate
    if (in_array(strtolower($callerId), array('private', 'unknown', '(000) 000-0000'))) {
        foreach ($callerRecords as $record) {
            $sheet = ($record['duration_in_seconds'] > $_GET['duration'])
                ? 'billable'
                : 'duplicates';

            $record['duration_in_seconds'] = gmdate('H:i:s', $record['duration_in_seconds']);
            $records[$sheet][] = $record;
        }
        continue; //end of this special case
    }

    //now let's process regular records. The first one has the longest duration and is special.
    $firstRecord = current($callerRecords);

    foreach ($callerRecords as $record) {
        //check if it's long enough
        $isEligible = ($record['duration_in_seconds'] > $_GET['duration']);

        if ($isEligible) {
            //if so, the 1st record goes to "billable", all other eligible ones go to "non-billable"
            $sheet = ($record == $firstRecord)
                ? 'billable'
                : 'duplicates';
        }
        else {
            //all the ones that are too short, go to duplicates
            $sheet = 'non-billable';
        }

        $record['duration_in_seconds'] = gmdate('H:i:s', $record['duration_in_seconds']);
        $records[$sheet][] = $record;
    }
}
unset($searchResultsByCaller);

$filename = str_replace(' ', '', $_GET['number']). '.xlsx';
$fullname = TEMP_DIR . $filename;
generate_excel_file($records, $fullname);
unset($records);
header('Content-Type: application/vnd.ms-excel');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fullname));

ob_clean();
flush();
readfile($fullname);
unlink($fullname);
exit;