<?php

if(empty($_POST)) {
  return;
}

$uploadResultsList = array();

foreach ($_FILES['file_source']['name'] as $index => $originalFilename) {
    $file = array(
        'name'      => $originalFilename,
        'type'      => $_FILES['file_source']['type'][$index],
        'tmp_name'  => $_FILES['file_source']['tmp_name'][$index],
        'error'     => $_FILES['file_source']['error'][$index],
        'size'      => $_FILES['file_source']['size'][$index]
    );

    $uploadResultsList[$originalFilename] = array();
    $resultRecord =& $uploadResultsList[$originalFilename];

    $errorCode = $file['error'];
    if(empty($file['size'])) {
      $errorCode = UPLOAD_ERR_EMPTY_FILE; //empty file
    }

    if (!is_uploaded_file($file['tmp_name']) || UPLOAD_ERR_OK != $errorCode) {
      $resultRecord['status'] = 'error';
      $resultRecord['error_message'] = coalesce($uploadErrors[$errorCode], 'General upload error.');
      continue;
    }

    $temp_file = $file['tmp_name'];
    $our_file  = TEMP_DIR . basename($temp_file);
    if ( !move_uploaded_file( $temp_file, $our_file ) ) //copy to our folder
    {
      $resultRecord['status'] = 'error';
      $resultRecord['error_message'] = 'Could not copy [' . $temp_file .'] to [' . $our_file . ']';
      continue;
    }

    $zip = new ZipArchive;
    if ($zip->open($our_file) === TRUE) {
        $csvFilename = $zip->getNameIndex(0);
        $zip->extractTo(TEMP_DIR, array($csvFilename));
        $zip->close();
        unlink($our_file); //remove zip
        $our_file = TEMP_DIR . $csvFilename;
    }

    $fQuickCSV = new Quick_CSV_import($db);

    $fQuickCSV->table_name = 'workbench';
    $fQuickCSV->make_temporary = !true;
    $fQuickCSV->file_name = $our_file;
    $fQuickCSV->use_csv_header = true;
    $fQuickCSV->table_exists = true;
    $fQuickCSV->truncate_table = true;
    $fQuickCSV->encoding = 'utf8';
    $fQuickCSV->field_separate_char = ',';
    $fQuickCSV->field_enclose_char = '"';
    $fQuickCSV->field_escape_char = '\\';

    $fQuickCSV->import();
    unlink($our_file);

    if (!empty($fQuickCSV->error)) {
      $resultRecord['status'] = 'error';
      $resultRecord['error_message'] = $fQuickCSV->error;
      continue;
    }
    
    if (0 == $fQuickCSV->rows_count) {
      $resultRecord['status'] = 'error';
      $resultRecord['error_message'] = 'Imported rows count is 0.';
      continue;
    }

    query('UPDATE `workbench` SET
          `number_from` = TRIM(`number_from`),
          `number_to` = TRIM(`number_to`),
          `forwarded_to` = TRIM(`forwarded_to`),
          `date` = TRIM(`date`),
          `time` = TRIM(`time`),
          `duration` = TRIM(`duration`)');

    $uniqueRecords = query('SELECT COUNT(*) as cnt
                   FROM (
                      SELECT DISTINCT `number_from`, `number_to`, `forwarded_to`, `date`, `time`, `duration`
                      FROM `workbench`
                  ) a')
                ->fetchColumn();

    $stmt = query('INSERT IGNORE INTO `logs`
           SELECT NULL as `id`, `number_from`, `number_to`, `forwarded_to`,
              STR_TO_DATE(CONCAT(`date`, " ", `time`), "%a %m/%d/%Y %r") as `datetime`,
              TIME_TO_SEC(`duration`)
           FROM `workbench`');

    $resultRecord['status'] = 'success';
    $resultRecord['csv_rows_count'] = (int)$fQuickCSV->rows_count;
    $resultRecord['unique_records'] = (int)$uniqueRecords;
    $resultRecord['duplicate_records'] = $resultRecord['csv_rows_count'] - $resultRecord['unique_records'];
}

