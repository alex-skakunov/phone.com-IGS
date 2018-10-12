<?php
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);
//date_timezone_set('GMT');

ini_set("arg_separator.output", "&amp;");
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("error_log", 'temp/error.log');
ini_set("memory_limit", '258M');

//database settings
define("DB_HOST"    , '127.0.0.1');
define("DB_LOGIN"   , 'ratedeck');
define("DB_PASSWORD", '');
define("DB_NAME"    , 'igs');


define("CURRENT_DIR"  , getcwd() . DIRECTORY_SEPARATOR );   //stand-alone classes
define("CLASSES_DIR"  , CURRENT_DIR . 'classes' .  DIRECTORY_SEPARATOR);   //stand-alone classes
define("ACTIONS_DIR"  , CURRENT_DIR . 'actions' .  DIRECTORY_SEPARATOR);   //controllers processing sumbitted data and preparing output
define("TEMP_DIR",  CURRENT_DIR . 'temp' . DIRECTORY_SEPARATOR); //all uploaded files will be copied here so that they won't be deleted between requests
