<?php

//Returns the first non-empty value in the list, or an empty line if there are no non-empty values.
function coalesce()
{ 
  for($i=0; $i < func_num_args(); $i++)
  {
    $arg = func_get_arg($i);
    if(!empty($arg))
      return $arg;
  }
  return "";
}

//go to new location (got from Fusebox4 source)
function Location($URL, $addToken = 1)
{
  $questionORamp = (strstr($URL, "?"))?"&":"?";
  $location = ( $addToken && substr($URL, 0, 7) != "http://" && defined('SID') ) ? $URL.$questionORamp.SID : $URL; //append the sessionID ($SID) by default
  //ob_end_clean(); //clear buffer, end collection of content
  if(headers_sent()) {
    print('<script type="text/javascript" type="text/javascript">( document.location.replace ) ? document.location.replace("'.$location.'") : document.location.href = "'.$location.'";</script>'."\n".'<noscript><meta http-equiv="Refresh" content="0;URL='.$location.'" /></noscript>');
  } else {
    header('Location: '.$location); //forward to another page
    exit; //end the PHP processing
  }
}

//checks that we have all modules we need or exit() will be called
function check_necessary_functions()
{ 
  for($i=0; $i < func_num_args(); $i++)
  {
    $func_name = func_get_arg($i);
    if( !function_exists($func_name) )
    {
      exit ( "Function [" . $func_name . "] is not accessable. Please check that correspondent PHP module is installed at your web-server." );
    }
  }
  return true;
}

//writes data in a file
function write_file($filename, $data)
{
  $fp = fopen($filename, 'w');
  if($fp)
  {
    fwrite($fp, $data);
    fclose($fp);
    return true;
  }
  return false;
}

//writes data in the end of a file
function append_file($filename, $data)
{
  $fp = fopen($filename, 'a');
  if($fp)
  {
    fwrite($fp, $data);
    fclose($fp);
    return true;
  }
  return false;
}

//OS independent deletion of a file
function delete_file($filename)
{
  if(file_exists($filename))
  {
    $os = php_uname();
    if(stristr($os, "indows")!==false)
      return exec("del ".$filename);
    else
      return unlink($filename);
  }
  return true;
}


//returns all fields of [tableName]
function get_table_fields($db, $tableName )
{
  $arrFields = array();
  if( empty($tableName) )
  {
    return false;
  }
  
  $db->query("SHOW TABLES LIKE '".$tableName."'");
  
  if( 0 == $db->getRowsCount())
  {
    return false;
  }
  
  $db->query("SHOW COLUMNS FROM ".$tableName);
  
  
  while( $row = mysql_fetch_array($db->fResult) )
  {
    $arrFields[] = trim( $row[0] );
  }
  
  return $arrFields;
}

function detect_line_ending($file)
{
    $s = file_get_contents($file);
    if( empty($s) ) return null;
    
    if( substr_count( $s,  "\r\n" ) ) return '\r\n'; //Win
    if( substr_count( $s,  "\r" ) )   return '\r';   //Mac
    return '\n'; //Unix
}

function startsWith( $str, $token ) {
    $_token = trim( $token );
    $_str = trim( $str );
    if( empty( $_token ) || empty( $str ) ) return false;
    
    $tokenLen = strlen( $_token );
    // $tokenFromStr = substr( $_str, 0, $tokenLen );
    // return strtolower( $_token ) == strtolower( $tokenFromStr );
    
    return !strncasecmp($_str, $token, $tokenLen );
}

function query($sql, $replacements=null) {
    global $db;
    $stmt = $db->prepare($sql);
    if (false === $stmt->execute($replacements)) {
      new dBug($sql);
      throw new Exception($stmt->errorInfo()[2], $stmt->errorInfo()[1]);
      
    }
    return $stmt;
}

function tokenize($name) {
  return str_replace(' ', '_', strtolower($name));
}

function sort_by_duration($a, $b) {
    return (int)$b['duration_in_seconds'] - (int)$a['duration_in_seconds'];
}

function generate_excel_file(array $data, $fullname) {
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

    $spreadsheet->getProperties()->setCreator('Chad Philip')
        ->setLastModifiedBy('Chad Philip')
        ->setTitle('Filtered calls log')
        ->setSubject('Filtered calls log')
        ->setDescription('Invoice Generation System has filtered the call logs and generated this file')
        ->setKeywords('Invoice Generation System')
        ->setCategory('Invoice Generation System');

    $spreadsheet->setActiveSheetIndex(0);

    $billableSheet = $spreadsheet->getActiveSheet();
    write_excel_sheet_rows(
        $billableSheet,
        array('From', 'Forwarded To', 'Date', 'Time', 'Duration'),
        1
    );
    foreach (range('A', $billableSheet->getHighestDataColumn()) as $col) {
        $billableSheet
                ->getColumnDimension($col)
                ->setAutoSize(true);
    }

    $duplicateSheet = clone $billableSheet;
    $nonBillaableSheet = clone $billableSheet;

    write_excel_sheet_rows($billableSheet, $data['billable']);
    write_excel_sheet_rows($duplicateSheet, $data['duplicates']);
    write_excel_sheet_rows($nonBillaableSheet, $data['non-billable']);

    $billableSheet->setTitle('Billable calls');
    $duplicateSheet->setTitle('Duplicate calls');
    $nonBillaableSheet->setTitle('Non-Billable calls');

    $spreadsheet->addSheet($duplicateSheet);
    $spreadsheet->addSheet($nonBillaableSheet);

    $billableSheet->calculateColumnWidths();

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($fullname);
}

function write_excel_sheet_rows(PhpOffice\PhpSpreadsheet\Worksheet\Worksheet &$sheet, array $array, $rowIndex=null) {
    if (empty($rowIndex)) {
        $rowIndex = $sheet->getHighestDataRow()+1;
    }
    $sheet->fromArray($array, null, 'A'.$rowIndex);
}

function search(array $searchParams, $onlyCount=false) {
    if ($onlyCount) {
        $sql = 'SELECT COUNT(*) as cnt';
    }
    else {
        $sql = 'SELECT `number_from`, `forwarded_to`,
                DATE_FORMAT(`datetime`, "%a %m/%d/%Y") as `date`,
                DATE_FORMAT(`datetime`, "%r") as `time`,
                `duration_in_seconds`';
    }
    $sql .= ' FROM `logs`';

    $params = array();

    if (!empty($searchParams['number'])) {
        $params[] = '`forwarded_to` LIKE "'.trim($searchParams['number'].'%"');
    }

    if (!empty($searchParams['date_from'])) {
        $params[] = '`datetime` >= "' . $searchParams['date_from'] . ' 00:00:00"';
    }

    if (!empty($searchParams['date_to'])) {
        $params[] = '`datetime` <= "' . $searchParams['date_to'] . ' 23:59:59"';
    }

    if (!empty($params)) {
        $sql .= ' WHERE ';
        $sql .= '(' . implode(') AND (', $params) . ')';
    }
    //new dBug(array('sql' => $sql));
    $stmt = query($sql);

    return $onlyCount
        ? $stmt->fetchColumn()
        : $stmt->fetchAll(PDO::FETCH_ASSOC);
}