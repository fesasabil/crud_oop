<?php

include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->update('crud_class',array('name'=>"Name 1",'email'=>"name1@gmail.com"),'id="1" AND name="name1"');
$res = $db->getResult();
print_r($res);