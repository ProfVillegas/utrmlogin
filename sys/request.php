<?php
header('Content-Type:application/json');
session_start();

$data =json_decode(file_get_contents("php://input"),true);
echo json_encode($data[0]);

?>