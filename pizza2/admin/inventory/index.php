<?php
require('../../util/main.php');
require('../../model/database.php');
require('../../model/inventory_db.php');
require('../../model/day_db.php');
require('../../model/initial_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list';
    }
}
$warning = "";

if ($action == 'list') {
    try {
        $get_flour = get_current_ingredient($db, 11);
        $get_cheese = get_current_ingredient($db, 12);
        $current_day = get_current_day($db);

        $request_uri = $_SERVER['REQUEST_URI'];
        $server = $_SERVER['HTTP_HOST'];
        $proto = isset($_SERVER['HTTPS']) ? 'https:' : 'http:';
        $path = explode("/", $request_uri);
        $url = $proto . '//'. $server.'/'.$path[1].'/'.$path[2].'/'.$path[3].'/proj2_server/rest/orders';
        $ch = curl_init();
        $result = file_get_contents($url);
        curl_close($ch);
        $data_order = json_decode($result, true);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('../../errors/database_error.php');
        exit();
    }
    include('inventory_list.php');
} else if ($action == 'show_add_form') {
    include("inventory_order.php");
} else if ($action == 'new_order') {
    try {
        // check current inventory //
        $get_flour = get_current_ingredient($db, 11);
        $get_cheese = get_current_ingredient($db, 12);

        // get data from form //
        $customer = filter_input(INPUT_POST, 'customer');
        $flour = filter_input(INPUT_POST, 'flour', FILTER_VALIDATE_INT);
        $cheese = filter_input(INPUT_POST, 'cheese', FILTER_VALIDATE_INT);

        if($flour%40==0 && $cheese%20==0){
            $undelivered_flour = 0;
            $undelivered_cheese = 0;

            // check undelivered order //
            $ch = curl_init();
            $request_uri = $_SERVER['REQUEST_URI'];
            $server = $_SERVER['HTTP_HOST'];
            $proto = isset($_SERVER['HTTPS']) ? 'https:' : 'http:';
            $path = explode("/", $request_uri);
            $url = $proto . '//'. $server.'/'.$path[1].'/'.$path[2].'/'.$path[3].'/proj2_server/rest/orders';
            $result = file_get_contents($url);
            $data_order = json_decode($result, true);
            for($i = 0; $i<count($data_order); $i++){
                if($data_order[$i]['customerID'] == $customer && $data_order[$i]['delivered'] == 'false') { // undelivered
                    $undelivered_flour += $data_order[$i]['items'][0]['quantity'];
                    $undelivered_cheese += $data_order[$i]['items'][1]['quantity'];
                }
            }

            // calculate //
            $sum_flour = $get_flour['unit'] + $flour + $undelivered_flour;
            $sum_cheese = $get_cheese['unit'] + $cheese + $undelivered_cheese;
            //echo $sum_flour.' , '.$sum_cheese;

            if($sum_flour > 250 || $sum_cheese >250){
                $warning = "The inventory can only store 250 units of each.";
                $icon_warning = "warning_error";
            }else if($sum_flour < 150 || $sum_cheese < 150){
                $warning = "The supplies order need to have at least 150 units of each on hand.";
                $icon_warning = "warning_error";
            }else{ // Insert by JSON //
                $icon_warning = "warning_success";
                $item1 = array('productID'=>11, 'quantity'=>$flour);
                $item2 = array('productID'=>12, 'quantity'=>$cheese);
                $order = array('customerID'=> $customer, 'items' => array($item1, $item2));

                $base_url = $_SERVER['HTTP_HOST'].'/cs637/nichapu/proj2/proj2_server/rest/orders';
                $data_json = json_encode($order);
                curl_setopt($ch, CURLOPT_URL, $base_url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
                $response = curl_exec($ch);
                $result = file_get_contents(trim($response));
                $data = json_decode($result, true);

                if($data['orderID'] != ''){
                    $warning = "Order succeed. New Order ID: ".$data['orderID'];
                    new_supplies_order($db, $data['orderID'], $flour, $cheese);
                }else{
                    $warning = "Error! Please try again";
                    $icon_warning = "warning_error";
                }
            }
        }else{
            $warning = "Flour is sold in 40-unit bags, and cheese is sold in 20-unit containers only.";
            $icon_warning = "warning_error";
        }
        $request_uri = $_SERVER['REQUEST_URI'];
        $server = $_SERVER['HTTP_HOST'];
        $proto = isset($_SERVER['HTTPS']) ? 'https:' : 'http:';
        $path = explode("/", $request_uri);
        $url = $proto . '//'. $server.'/'.$path[1].'/'.$path[2].'/'.$path[3].'/proj2_server/rest/orders';
        $ch = curl_init();
        $result = file_get_contents($url);
        curl_close($ch);
        $data_order = json_decode($result, true);

    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('../../errors/database_error.php');
        exit();
    }

    include('inventory_list.php');
}

function transStatus($val) {
    if($val=='true'){
        $st = "Delivered";
    }else{
        $st = "Undelivered";
    }
    return $st;
}
?>