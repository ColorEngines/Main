<?php
GLOBAL $ini;
$ini = parse_ini_file("ini/".gethostname().'.ini',true);
foreach ($ini['include'] as $value)  { include_once $value; }
foreach (file::folder_with_extension(".", "class", "/") as $filepath) { include_once $filepath; }    // load all CLASS files
?>
