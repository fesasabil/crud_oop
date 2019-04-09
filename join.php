<?php

include('class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->select('crud_class', 'crud_class.id,crud_class.name,crud_class_child.name','crud_class_child ON crud_class.id = parentId','crud_class.name="Name 1"','id DESC');// Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
$res = $db->getResult();
print_r($res);