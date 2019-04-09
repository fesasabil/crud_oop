<?php

include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$data = $db->escapeString("name1@email.com");//escape any input before insert
$db->insert('crud_class',array('name'=>'Name 1','email' =>$data));// Table name, column names and respective values
$res = $db->getResult();
print_r($res);