<?php

$stmt = query('SELECT COUNT(*) AS "cnt"  FROM `logs`');
$totalCount = $stmt->fetchColumn();

if (!empty($_POST['erase_database'])) {
    query('TRUNCATE TABLE logs');
    $totalCount = 0;
    $message = 'Logs records have been successfully erased';
}
