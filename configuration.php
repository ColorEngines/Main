<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$processor = $config['processors']['default'];

$files = file::folder_with_extension($config[$processor]['folder'],$config['data']['extension'] , "/", true);

foreach ($files as $key => $value) 
{
    echo '<button class="process_button" onclick="process(\''.$key.'\',\''.$value.'\')">'.$key.'</button>';
}


?>