<?php

header('content-type: application/json; charset=utf-8');
include('../class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->select('crud_class', 'id,name','name="Name 1"','id DESC');//table name, column names, WHERE conditions, ORDER BY conditions
$res = $db->getResult();
echo json_encode($res);