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

$name = $data["name"];
$email = $data["email"];
$address = $data["address"];
$mobile = $data["mobile"];
$password = $data["password"];
$profile_image = $data["profile_image"];

$insert = "INSERT INTO register (name,email,address,mobile,password,profile_image) 
           VALUES ('".$name."','".$email."','".$address."','".$mobile."','".$password."','".$profile_image."')";

if (mysqli_query($conn, $insert)) {
    echo json_encode(["message" => "Account Created", "status" => "true"]);
} else {
    echo json_encode([
        "message" => "Account not created",
        "status" => "false"
        
    ]);
}
?>
