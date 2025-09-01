<?php
include_once('model.php');

class control extends model
{
    function __construct()
    {
        model::__construct();
        $url = $_SERVER['PATH_INFO'];

        switch ($url) {
            case '/Register':

                if (isset($_FILES['profile_image'])) {
                    $tempfile = $_FILES['profile_image']['tmp_name'];
                    $filename = uniqid() . "_" . $_FILES['profile_image']['name'];
                    $uploadfiledir = "gallery/customers";
                    $destpath = $uploadfiledir . $filename;
                    if (move_uploaded_file($tempfile, $destpath)) {
                        echo json_encode([
                            "status" => true,
                            "message" => "Image Uploaded"
                        ]);
                    } else {
                        echo json_encode([
                            "status" => false,
                            "message" => "Error in Image Uploading"
                        ]);
                    }
                }
                $arr = array(
                    "name" => $_POST["name"],
                    "email" => $_POST['email'],
                    "address" => $_POST['address'],
                    "mobile" => $_POST['mobile'], 
                    "password" => md5($_POST['password']),
                    "gender" => $_POST['gender'],
                    "profile_image" => $filename
                );

                $insert = $this->insert("customers", $arr);
                if ($insert or die("Insert Query Failed")) {
                    echo json_encode(array("message" => "User Inserted Successfully", "status" => true));
                } else {
                    echo json_encode(array("message" => "User Contacts Not Inserted ", "status" => false));
                }
                break;

            case '/Login':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Recived" . file_get_contents("php://input"));
                }
                $email = $data['email'];
                $password = md5($data['password']);

                $arr = array("email" => $email, "password" => $password);
                $login = $this->select_where("customers", $arr);
                $chk = $login->num_rows;
                if ($chk == 1) {
                    $row = $login->fetch_assoc();
                    echo json_encode(["message" => "Login Success", "data" => $row, "session_variable"=>$row['email'],"status" => true]);
                } else {
                    echo json_encode(["message" => "Login Failed", "status" => false]);
                }
                break;


            case '/Fetch_all_user':
                $res = $this->select("customers");
                $count = count($res);
                if ($count > 0) {
                    echo json_encode(array("data" => $res, "status" => true));
                } else {
                    echo json_encode(array("message" => "No Record Found", "status" => false));
                }
                break;
        }
    }
}


$obj = new control;

?>