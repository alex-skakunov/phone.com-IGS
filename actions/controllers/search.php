<?php


if (empty($_POST['number'])) {
    return;
}

foreach ($_POST as $key => $value) {
    if(empty($value)) {
        $_POST[$key] = null; //make sure the database saves exactly "NULL" for everything that looks empty
    }
}

$searchResultsCount = search($_POST, true);
