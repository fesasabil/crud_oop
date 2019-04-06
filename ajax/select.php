<?php

header('content-type: application/json; charset=utf-8');
include('../class/mysql_crud.php');
$db = new Database();
$db->connect();
$db->select('crud_class','name');//table name, column names, WHERE conditions, ORDER BY conditions
$res = $db->getResult();
echo json_encode($res);