<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

GLOBAL $ini;
$ini = parse_ini_file("ini/".gethostname().'.ini',true);

foreach ($ini['include'] as $iniIncludes)  
    include_once $iniIncludes; 

foreach (file::folder_with_extension($ini['application']['root_folder'], "class", "/") as $filepath)  include_once $filepath;     // load all CLASS files
        
?>