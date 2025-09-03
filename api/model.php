<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

class model
{
    public $conn = "";
    public $seceret_key = "";
    function __construct()
    {
        //KvQRLn3EIqrPWH2LKeWu


        $this->conn = new mysqli('localhost', 'root', '', 'bs-jewellery');
    }

    //select function

    function select($table)
    {
        $sel = "select * from $table";
        $run = $this->conn->query($sel);
        while ($fetch = $run->fetch_object()) {
            $arr[] = $fetch;
        }
        if (!empty($arr)) {
            return $arr;
        }
    }

    //function select where 
    function select_where($table, $where)
    {
        $col = array_keys($where);
        $val = array_values($where);

        $sel = "select * from $table where 1=1";
        $i = 0;
        foreach ($where as $a) {
            $sel .= " and $col[$i] = '$val[$i]'";
            $i++;
        }

        $run = $this->conn->query($sel);
        return $run;
    }

    //function for insert 
    function insert($table, $data)
    {
        $col_arr = array_keys($data);
        $col = implode(",", $col_arr);

        $val_arr = array_values($data);
        $val = implode("','", $val_arr);

        $insert = "insert into $table($col) values('$val')";
        $run = $this->conn->query($insert);
        return $run;

    }

    //function for delete 
    function delete($table, $where)
    {
        $col_arr = array_keys($where);
        $val_arr = array_values($where);

        $del = "delete from $table where 1=1";
        $i = 0;
        foreach ($where as $w) {
            $del .= " and $col_arr[$i] ='$val_arr[$i]'";
            $i++;
        }

        $run = $this->conn->query($del);
        return $run;
    }

    //function for update 
    function update($tbl, $arr, $where)
    {
        $col_arr = array_keys($arr);
        $value_arr = array_values($arr);
        $j = 0;
        $upd = "update $tbl set";  // query   name="",email="", 
        $count = count($arr);
        foreach ($arr as $d) {
            if ($count == $j + 1) {
                $upd .= " $col_arr[$j]='$value_arr[$j]'";
            } else {
                $upd .= " $col_arr[$j]='$value_arr[$j]',";
                $j++;
            }
        }
        $upd .= " where 1=1";
        $col_where = array_keys($where);
        $value_where = array_values($where);
        $i = 0;
        foreach ($where as $w) {
            $upd .= " and $col_where[$i]='$value_where[$i]'";
            $i++;
        }
        $run = $this->conn->query($upd);  // run on db
        return $run;
    }

    //function for join 
    function join_where($tbl1, $tbl2, $on, $where)
    {
        $sel = "select * from $tbl1 join $tbl2 on $on where $where";
        $run = $this->conn->query($sel);
        while ($fetch = $run->fetch_assoc()) {
            $arr[] = $fetch;
        }
        return $arr;
    }

    //function for simple join
    function simple_join($tbl1, $tbl2, $on)
    {
        $sel = "select * from $tbl1 join $tbl2 on $on";
        $run = $this->conn->query($sel);
        while ($fetch = $run->fetch_assoc()) {
            $arr[] = $fetch;
        }
        return $arr;
    }

    //Function for simple joins 3
     function simple_joins($tbl1, $tbl2, $tbl3,$on1,$on2)
    {
        $sel = "select $tbl1.*,$tbl2.*,$tbl3.* from $tbl1 join $tbl2 on $on1 join $tbl3 on  $on2";
        $run = $this->conn->query($sel);
        while ($fetch = $run->fetch_assoc()) {
            $arr[] = $fetch;
        }
        return $arr;
    }

    //Function for 3 table join 
    function joins_where($tbl1, $tbl2, $tbl3, $on1, $on2, $where)
    {
        $join = "select $tbl1.*,$tbl2.*,$tbl3.* from $tbl1 join $tbl2 on $on1 join $tbl3 on  $on2 where 1=1 ";
        $col_where = array_keys($where);
        $value_where = array_values($where);
        $i = 0;
        foreach ($where as $w) {
            $join .= " and $col_where[$i]='$value_where[$i]'";
            $i++;
        }
        $run = $this->conn->query($join);
        while ($fetch = $run->fetch_assoc()) {
            $arr[] = $fetch;
        }
        return $arr;
    }

    //Function for 5 tables 
    function join_more($tables, $joinConditions, $where)
    {
        $mainTable = array_shift($tables);
        $mainAlias = key($tables);
        $sql = "SELECT * FROM {$mainTable} {$mainAlias} ";

        $i = 0;
        foreach ($joinConditions as $joinTable => $condition) {
            $alias = array_keys($tables)[$i];
            $sql .= "JOIN {$joinTable} {$alias} ON {$condition} ";
            $i++;
        }
        $sql .= "WHERE $where ";
        $run = $this->conn->query($sql);

        while ($fetch = $run->fetch_assoc()) {
            $arr[] = $fetch;
        }
        return $arr;
    }
}

$obj = new model;

?>