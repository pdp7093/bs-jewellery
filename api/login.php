<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require 'connection.php';
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die("JSON not received: " . file_get_contents("php://input"));

}

$email = $data['email'];
$password = $data['password'];

$login = "select * from customers";
?>