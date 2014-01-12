<?php
if (file_exists('utilities/includes.php')) include_once "utilities/includes.php"; 
if (file_exists('../utilities/includes.php')) include_once "../utilities/includes.php"; 
$config = parse_ini_file(util::hostname().".ini", true);
?>