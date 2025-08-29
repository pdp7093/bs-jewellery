<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

class model
{
    public $conn = "";

    function __construct()
    {

        $this->conn = new mysqli('localhost', 'root', '', 'bs-jewellery');
    }

    //select function

    function select($table)
    {
        $sel = "select * from $table";
        $run = $this->conn->query($sel);
        while($fetch=$run->fetch_object()){
            $arr[]=$fetch;
        }
        if(!empty($arr)){
            return $arr;
        }
    }

    //function select where 
    function select_where($table,$where){
        $col = array_keys($where);
        $val = array_values($where);

        $sel = "select * from $table where 1=1";
        $i=0;
        foreach($where as $a){
            $sel.=" and $col[$i] = '$val[$i]'";
            $i++;
        }

        $run = $this->conn->query($sel);
        return $run;
    }

    //function for insert 
    function insert($table,$data){
        $col_arr=array_keys($data);
        $col=implode(",",$col_arr);

        $val_arr=array_values($data);
        $val=implode("','",$val_arr);

        $insert = "insert into $table($col) values('$val')";
        $run = $this->conn->query($insert);
        return $run;

    }
}

$obj = new model;

?>