<?php
require('../../util/main.php');
require('../../model/database.php');
require('../../model/order_db.php');
require('../../model/inventory_db.php');
require('../../model/day_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_orders';
    }
}

if ($action == 'list_orders') {
    try {
        $baked_orders = get_baked_orders($db);
        $preparing_orders = get_preparing_orders($db);

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

        include('order_list.php');
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include ('../../errors/database_error.php');
        exit();
    }
} else if ($action == 'change_to_baked') {
    try {
        $next_id = get_oldest_preparing_id($db);
        change_to_baked($db, $next_id);
        header("Location: .");
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include ('../../errors/database_error.php');
        exit();
    }
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