<?php

include('class/mysq_crud.php');
$db = new Database();
$db->connect();
$db->delete('crud_class', 'id=1');
$res = $db->getResult();
print_r($res);