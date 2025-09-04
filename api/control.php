<?php

use function Laravel\Prompts\select;
include_once('model.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class control extends model
{
    function __construct()
    {
        model::__construct();
        $url = $_SERVER['PATH_INFO'];
        $secret_key = "KvQRLn3EIqrPWH2LKeWu";
        switch ($url) {
            //Register User 
            case '/Register':
                $arr = array(
                    "name" => $_POST["name"],
                    "email" => $_POST['email'],
                    "mobile_number" => $_POST['mobile'],
                    "password" => md5($_POST['password']),
                    "terms_cond" => $_POST['terms_cond']

                );
                $sel = $this->select_where("customers", array("email" => $_POST['email']));
                if (!$sel->num_rows) {


                    $insert = $this->insert("customers", $arr);
                    if ($insert or die("Insert Query Failed")) {
                        echo json_encode(array("message" => "User Inserted Successfully", "status" => true));
                    } else {
                        echo json_encode(array("message" => "User Contacts Not Inserted ", "status" => false));
                    }
                } else {
                    echo json_encode(["message" => "Please Select Unique Email", "status" => false]);
                }
                break;

            //Login User
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
                    $payload = [
                        "customer_data" => $row,
                        "iat" => time(),
                        "exp" => time() + 3600 // 1 ghante ke liye valid
                    ];

                    $jwt = JWT::encode($payload, $secret_key, 'HS256');
                    echo json_encode(["message" => "Login Success", "token" => $jwt, "status" => true]);
                } else {
                    echo json_encode(["message" => "Login Failed", "status" => false]);
                }
                break;

            //Customer Profile
            case '/Profile-api':
                $headers = getallheaders();
                $authHeader = $headers['Authorization'] ?? '';

                if ($authHeader) {
                    $arr = explode(" ", $authHeader);
                    $token = $arr[1] ?? '';

                    try {
                        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

                        echo json_encode([
                            "status" => "success",
                            "data" => [
                                "customer_data" => $decoded->customer_data,
                                "message" => "Welcome to your profile!"
                            ]
                        ]);
                    } catch (Exception $e) {
                        echo json_encode([
                            "status" => "error",
                            "message" => "Invalid or expired token"
                        ]);
                    }
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Authorization header missing"
                    ]);
                }

                break;

            case '/View-Product':
                $id = $_GET['id'];
                $where = array("pro_id" => $id);
                $pro = $this->select_where("product", $where);
                $chk = $pro->num_rows;
                if ($chk == 1) {
                    $row = $pro->fetch_assoc();
                }
                $product_size = $this->select("product_sizes");
                $metal = $this->select("metals");
                $diamond = $this->select("diamonds");
                if ($row && $metal && $diamond) {
                    echo json_encode(["message" => "Data Fetch Success", "product" => $row, "metals" => $metal, "diamond" => $diamond, "product_size" => $product_size, "status" => true]);
                } else {
                    echo json_encode(["message" => "Error in Data Fetch", "status" => false]);
                }
                break;

            case "/Add-to-cart":
                break;
            //------------------Admin Side------------------------

            //Login Admin
            case '/Admin/Admin-Login':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Recived" . file_get_contents("php://input"));
                }
                $email = $data['email'];
                $password = md5($data['password']);

                $arr = array("email" => $email, "password" => $password);
                $login = $this->select_where("admin", $arr);
                $chk = $login->num_rows;
                if ($chk == 1) {
                    $row = $login->fetch_assoc();
                    echo json_encode(["message" => "Login Success", "data" => $row, "session_variable" => $row['email'], "status" => true]);
                } else {
                    echo json_encode(["message" => "Login Failed", "status" => false]);
                }
                break;

            // User Add and Manage

            case '/Admin/Manage_user':
                $res = $this->select("customers");
                if (!empty($res)) {
                    echo json_encode(array("data" => $res, "status" => true));
                } else {
                    echo json_encode(array("message" => "No Record Found", "status" => false));
                }

                break;

            case '/Admin/Add_user':
                $arr = array(
                    "name" => $_POST["name"],
                    "email" => $_POST['email'],
                    "mobile_number" => $_POST['mobile'],
                    "password" => md5($_POST['password']),
                    "terms_cond" => $_POST['terms_cond']

                );

                $insert = $this->insert("customers", $arr);
                if ($insert or die("Insert Query Failed")) {
                    echo json_encode(array("message" => "User Inserted Successfully", "status" => true));
                } else {
                    echo json_encode(array("message" => "User Contacts Not Inserted ", "status" => false));
                }
                break;

            case '/Admin/Delete_user':
                $id = $_GET['cust_id'];
                $where = array("cust_id" => $id);
                $res = $this->delete("customers", $where);
                if ($res) {
                    echo json_encode(["message" => "Customer Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Customer Not Delete", "status" => false]);
                }
                break;

            case '/Admin/Update_user':
                $where = array("cust_id" => $_GET['cust_id']);
                $data = json_decode(file_get_contents("php://input"), true);
                $update = array("name" => $data['name'], "email" => $data['email'], "mobile_number" => $data['mobile_number']);

                $res = $this->update("customers", $update, $where);
                if ($res) {
                    echo json_encode(["message" => "User Data Update SuccessFully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Not Update Due to Error", "status" => false]);
                }
                break;
            //Category Add And Manage

            case '/Admin/Add_Category':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Recived" . file_get_contents("php://input"));
                }
                $arr = array("category_name" => $data['category_name']);
                $cat = $this->insert("category", $arr);
                if ($cat or die("Insert Query Failed")) {
                    echo json_encode((["message" => "Category Created", "status" => true]));
                } else {
                    echo json_encode(["message" => "Category Not Created", "status" => false]);
                }

                break;

            //---------------------- View All category ------------

            case '/Admin/view_category':
                $res = $this->select("category");
                $count = count($res);
                if (!empty($res)) {
                    echo json_encode(["data" => $res, "status" => true]);
                } else {
                    echo json_encode(["message" => "No Record Found", "status" => false]);
                }
                break;

            //----------------- Delete Category ------------------
            case '/Admin/Delete_category':
                $id = $_GET['cate_id'];
                $where = array("cate_id" => $id);
                $res = $this->delete("category", $where);
                if ($res) {
                    echo json_encode(["message" => "Category Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Category Not Delete", "status" => false]);
                }
                break;

            //----------------- Update Category ------------------
            case '/Admin/Update_category':
                $id = $_GET['cate_id'];
                $where = array("cate_id" => $id);
                $data = json_decode(file_get_contents("php://input"), true);
                $update = array("category_name" => $data['category_name']);
                $res = $this->update("category", $update, $where);
                if ($res) {
                    echo json_encode(["message" => "Category Update Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Category Not Update", "status" => false]);
                }
                break;
            //------------------------------------------PRODUCT PAGE------------------------------------------

            case '/Admin/Add_product':
                $image_names = [];
                $uploadDir = '../gallery/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (!empty($_FILES['product_image']['name'][0])) {
                    foreach ($_FILES['product_image']['name'] as $key => $value) {
                        $file_name = time() . "_" . basename($_FILES['product_image']['name'][$key]);
                        $target = $uploadDir . $file_name;

                        if (move_uploaded_file($_FILES['product_image']['tmp_name'][$key], $target)) {
                            $image_names[] = $file_name;
                        }
                    }
                }


                $images = implode(",", $image_names); // comma separated store
                $arr = array(
                    "cate_id " => $_POST["cate_id"],
                    "collection_id " => $_POST['collection_id'],
                    "product_name" => $_POST["product_name"],
                    "product_code" => $_POST['product_code'],
                    "product_image" => $images,
                    "product_decsp" => $_POST['product_decsp'],
                    "price" => $_POST['price'],//gender
                    "gender" => $_POST["gender"],
                    "height" => $_POST["height"],//
                    "width" => $_POST['width'],
                    "product_wieght" => $_POST['product_wieght'],
                    "metals_id " => $_POST["metals_id"],
                    "diamonds_id " => $_POST["diamonds_id"],//
                    "product_size" => $_POST['product_size'],
                    "of_stones" => $_POST['of_stones'],
                    "total_diamond_weight" => $_POST['diamond_weight'],
                );
                $insert = $this->insert("product", $arr);
                if ($insert or die("Insert Query Failed")) {
                    echo json_encode(array("message" => "Product Inserted Successfully", "status" => true));
                } else {
                    echo json_encode(array("message" => "Product Not Inserted ", "status" => false));
                }

                break;

            case '/Admin/Manage_product':
                $res = $this->simple_joins(
                    "product",
                    "category",
                    "collection",
                    "product.cate_id =category.cate_id"
                    ,
                    "product.collection_id = collection.collection_id"

                );

                if ($res) {
                    $data = [];

                    foreach ($res as $row) {
                        $images = explode(",", $row['product_image']); // images as array
                        $data[] = [
                            "product" => [
                                "pro_id" => $row['pro_id'],
                                "product_name" => $row['product_name'],
                                "product_code" => $row['product_code'],
                                "product_decsp" => $row['product_decsp'],
                                "product_image" => $images,
                                "price" => $row['price'],
                                "gender" => $row['gender'],
                                "height" => $row['height'],
                                "width" => $row['width'],
                                "product_wieght" => $row['product_wieght'],
                                "of_stones" => $row['of_stones'],
                                "total_diamond_weight" => $row['total_diamond_weight'],

                            ],
                            "category" => [
                                "category_name" => $row['category_name'],
                            ],
                            "collection" => [
                                "collection_name" => $row['collection_name'],
                            ]
                        ];
                    }
                    echo json_encode(["message" => "Fetch Success", "data" => $data, "status" => true]);
                } else {
                    echo json_encode(["message" => "No Record Found", "status" => false]);
                }

                break;


            case '/Admin/Update_product':
                $id = $_POST['pro_id'];
                $where = ["pro_id" => $id];

                // Fetch product row
                $res = $this->select_where("product", $where);
                $row = $res ? mysqli_fetch_assoc($res) : null;
                $images = $row && !empty($row['product_image']) ? $row['product_image'] : "";

                // Handle new uploads
                if (!empty($_FILES['product_image']['name'][0])) {
                    $uploaded = [];
                    foreach ($_FILES['product_image']['name'] as $k => $v) {
                        $file = time() . "_" . basename($v);
                        $path = "gallery/products/" . $file;
                        if (move_uploaded_file($_FILES['product_image']['tmp_name'][$k], $path)) {
                            $uploaded[] = $file;
                        }
                    }
                    $images = $images ? $images . "," . implode(",", $uploaded) : implode(",", $uploaded);
                }

                // Update data
                $arr = [
                    "product_name" => $_POST['product_name'],
                    "product_decsp" => $_POST['product_decsp'],
                    "price" => $_POST['price'],
                    "gender" => $_POST['gender'],
                    "product_image" => $images
                ];

                $update = $this->update("product", $arr, $where);

                echo json_encode([
                    "message" => $update ? "Product Updated Successfully" : "Product Not Updated",
                    "status" => (bool) $update
                ]);
                break;



            case '/Admin/Delete_product':
                $id = $_GET['id'];
                $where = array("pro_id" => $id);
                $res = $this->delete("product", $where);
                if ($res) {
                    echo json_encode(["message" => "Product Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Product Not Delete Successfully", "status" => false]);
                }
                break;

            //------------------------------------------COLLECTION PAGE------------------------------------------
            case '/Admin/Add_collection':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Recived:" . file_get_contents("php://input"));
                }
                $arr = array("collection_name" => $data['collection_name']);
                $cal = $this->insert("collection", $arr);
                if ($cal or die("Insert Query Failed")) {
                    echo json_encode(["message" => "Collection Created.", "status" => true]);
                } else {
                    echo json_encode(["message" => "Collection Not Created.", "status" => false]);
                }
                break;

            //----------------- Delete Collection ------------------
            case '/Admin/Delete_collection':
                $id = $_GET['collection_id'];
                $where = array("collection_id" => $id);
                $res = $this->delete("collection", $where);
                if ($res) {
                    echo json_encode(["message" => "Category Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Category Not Delete", "status" => false]);
                }
                break;



            //---------------------- View All Collection ------------

            case '/Admin/Manage_collection':
                $res = $this->select("collection");
                $count = count($res);
                if (!empty($res)) {
                    echo json_encode(["data" => $res, "status" => true]);
                } else {
                    echo json_encode(["message" => "No Record Found", "status" => false]);
                }
                break;

            case '/Admin/Update_collection':
                $id = $_GET['collection_id'];
                $where = array("collection_id" => $id);
                $data = json_decode(file_get_contents("php://input"), true);
                $update = array("collection_name" => $data['collection_name']);
                $res = $this->update("collection", $update, $where);
                if ($res) {
                    echo json_encode(["message" => "Collection Update Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Collection Not Update", "status" => false]);
                }
                break;

            //----------------------------------- OFFER ADD AND MANAGE ----------------------------------------

            case '/Admin/Add_offer':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Received: " . file_get_contents("php://input"));
                }

                $arr = array(
                    "offer_title" => $data['offer_title'],
                    "offer_description" => $data['offer_description'],
                    "offer_code" => $data['offer_code'],
                    "discount_type" => $data['discount_type'],
                    "discount_value" => $data['discount_value'],
                    "start_date" => $data['start_date'],
                    "end_date" => $data['end_date'],
                    "created_at" => date("Y-m-d H:i:s")
                );

                $off = $this->insert("offer", $arr);

                if ($off) {
                    echo json_encode(["message" => "Offer Created", "status" => true]);
                } else {
                    echo json_encode(["message" => "Offer Not Created", "status" => false]);
                }
                break;


            //------------ View All Offer ------------

            case '/Admin/Manage_offer':
                $res = $this->select("offer");
                $count = count($res);
                if (!empty($res)) {
                    echo json_encode(["data" => $res, "status" => true]);
                } else {
                    echo json_encode(["message" => "No Record Found", "status" => false]);
                }
                break;

            //----------------- Delete Offer ------------------
            case '/Admin/Delete_offer':
                $id = $_GET['offer_id'];
                $where = array("offer_id" => $id);
                $res = $this->delete("offer", $where);
                if ($res) {
                    echo json_encode(["message" => "Offer Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Offer Not Delete", "status" => false]);
                }
                break;
            //----------------- Update Offer ------------------
            case '/Admin/Update_offer':
                $id = $_GET['offer_id'];
                $where = array("offer_id" => $id);
                $data = json_decode(file_get_contents("php://input"), true);
                $update = array(
                    "offer_title" => $data['offer_title'],
                    "offer_description" => $data['offer_description'],
                    "offer_code" => $data['offer_code'],
                    "discount_type" => $data['discount_type'],
                    "discount_value" => $data['discount_value'],
                    "start_date" => $data['start_date'],
                    "end_date" => $data['end_date']
                );
                $res = $this->update("offer", $update, $where);
                if ($res) {
                    echo json_encode(["message" => "Offer Update Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Offer Not Update", "status" => false]);
                }
                break;

            //---------------------- METAL ADD AND MANAGE----------------------

            case '/Admin/Add_Metal':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Recived" . file_get_contents("php://input"));
                }
                $arr = array(
                    "metal_name" => $data['metal_name'],
                    "metal_type" => $data['metal_type'],
                    "metal_weight" => $data['metal_weight'],
                    "metal_price" => $data['metal_price']
                );
                $cat = $this->insert("metals", $arr);
                if ($cat or die("Insert Query Failed")) {
                    echo json_encode((["message" => "Metal Created", "status" => true]));
                } else {
                    echo json_encode(["message" => "Metal Not Created", "status" => false]);
                }

                break;

            // ------------ View All Metal ------------

            case '/Admin/Manage_metal':
                $res = $this->select("metals");
                $count = count($res);
                if (!empty($res)) {
                    echo json_encode(["data" => $res, "status" => true]);
                } else {
                    echo json_encode(["message" => "No Record Found", "status" => false]);
                }
                break;

            //----------------- Delete Category ------------------
            case '/Admin/Delete_metal':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $res = $this->delete("metals", $where);
                if ($res) {
                    echo json_encode(["message" => "Metal Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Metal Not Delete", "status" => false]);
                }
                break;

            //----------------- Update Metal ------------------
            case '/Admin/Update_Metal':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $data = json_decode(file_get_contents("php://input"), true);
                $update = array(
                    "metal_name" => $data['metal_name'],
                    "metal_type" => $data['metal_type'],
                    "metal_weight" => $data['metal_weight'],
                    "metal_price" => $data['metal_price']
                );
                $res = $this->update("metals", $update, $where);
                if ($res) {
                    echo json_encode(["message" => "Metal Update Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Metal Not Update", "status" => false]);
                }
                break;

            //---------------------- DIAMONDS ADD AND MANAGE ----------------------

            case '/Admin/Add_diamonds':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Recived" . file_get_contents("php://input"));
                }
                $arr = array(
                    "diamonds_type" => $data['diamonds_type'],
                    "diamond_price" => $data['diamond_price'],
                    "diamond_shape" => $data['diamond_shape'],
                );
                $cat = $this->insert("diamonds", $arr);
                if ($cat or die("Insert Query Failed")) {
                    echo json_encode((["message" => "diamonds Created", "status" => true]));
                } else {
                    echo json_encode(["message" => "diamonds Not Created", "status" => false]);
                }

                break;
            //------------ View All Diamonds ------------

            case '/Admin/Manage_diamonds':
                $res = $this->select("diamonds");
                $count = count($res);
                if (!empty($res)) {
                    echo json_encode(["data" => $res, "status" => true]);
                } else {
                    echo json_encode(["message" => "No Record Found", "status" => false]);
                }
                break;
            //----------------- Update Diamonds ------------------
            case '/Admin/Update_diamonds':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $data = json_decode(file_get_contents("php://input"), true);
                $update = array(
                    "diamonds_type" => $data['diamonds_type'],
                    "diamond_price" => $data['diamond_price'],
                    "diamond_shape" => $data['diamond_shape']
                );
                $res = $this->update("diamonds", $update, $where);
                if ($res) {
                    echo json_encode(["message" => "Metal Update Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Metal Not Update", "status" => false]);
                }
                break;

            //----------------- Delete Diamonds ------------------
            case '/Admin/Delete_diamonds':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $res = $this->delete("diamonds", $where);
                if ($res) {
                    echo json_encode(["message" => "Metal Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Metal Not Delete", "status" => false]);
                }
                break;
            //--------------------------- ADD-TO-CART CASE'S ---------------------------
            case '/Admin/Add-to-cart':

                break;

            case '/Admin/Delete_cart':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $res = $this->delete("addtocart", $where);
                if ($res) {
                    echo json_encode(["message" => "Cart Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Cart Not Delete", "status" => false]);
                }
                break;

            //--------------------------- ADD-TO-CART CASE'S ---------------------------
        

            case '/Admin/Delete_order':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $res = $this->delete("orders", $where);
                if ($res) {
                    echo json_encode(["message" => "Order Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Order Not Delete", "status" => false]);
                }
                break;
            //--------------------------- PRODUCT SIZE CASE'S ---------------------------
            case '/Admin/Add_ProductSize':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data) {
                    die("JSON Not Recived" . file_get_contents("php://input"));
                }
                $arr = array(
                    "product_type" => $data['product_type'],
                    "size_label" => $data['size_label'],
                    "diameter_mm" => $data['diameter_mm'],
                    "circumference_mm" => $data['circumference_mm']
                );
                $cat = $this->insert(" product_sizes", $arr);
                if ($cat or die("Insert Query Failed")) {
                    echo json_encode((["message" => "Product Size Created", "status" => true]));
                } else {
                    echo json_encode(["message" => "Product Size Not Created", "status" => false]);
                }
                break;

            case '/Admin/Manage_ProductSize':
                $res = $this->select("product_sizes");
                $count = count($res);
                if (!empty($res)) {
                    echo json_encode(["data" => $res, "status" => true]);
                } else {
                    echo json_encode(["message" => "No Record Found", "status" => false]);
                }
                break;
            case '/Admin/Delete_ProductSize':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $res = $this->delete("product_sizes", $where);
                if ($res) {
                    echo json_encode(["message" => "Product Size Delete Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Product Size Not Delete", "status" => false]);
                }
                break;

            case '/Admin/Update_ProductSize':
                $id = $_GET['id'];
                $where = array("id" => $id);
                $data = json_decode(file_get_contents("php://input"), true);
                $arr = array(
                    "product_type" => $data['product_type'],
                    "size_label" => $data['size_label'],
                    "diameter_mm" => $data['diameter_mm'],
                    "circumference_mm" => $data['circumference_mm']
                );
                $res = $this->update("product_sizes", $arr, $where);
                if ($res) {
                    echo json_encode(["message" => "Product Size Update Successfully", "status" => true]);
                } else {
                    echo json_encode(["message" => "Product Size Not Update", "status" => false]);
                }
                break;
        }
    }
}


$obj = new control;

?>