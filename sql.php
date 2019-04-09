<?php

include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->sql('SELECT id,name FROM crud_class');
$res = $db->getResult();
foreach ($res as $output) {
    echo $output["name"]."<br/>";
}