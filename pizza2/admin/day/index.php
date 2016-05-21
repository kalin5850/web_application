<?php
require('../../util/main.php');
require('../../model/database.php');
require('../../model/day_db.php');
require('../../model/inventory_db.php');
require('../../model/initial_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list';
    }
}
 $current_day = get_current_day($db);
if ($action == 'list') {
    try {
        $todays_orders = get_todays_orders($db, $current_day);
    } catch (PDOException $e) {
    $error_message = $e->getMessage(); 
    include('../../errors/database_error.php');
    exit();
    }
    include('day_list.php');
} else if ($action == 'change_to_nextday') {
    try {
        finish_orders_for_day($db, $current_day);
        increment_day($db);
        $current_day = get_current_day($db);

        $base_url = $_SERVER['HTTP_HOST'].'/cs637/nichapu/proj2/proj2_server/rest/day';
        $data = array('day'=>$current_day);
        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
        $response  = curl_exec($ch);
        curl_close($ch);
        $data_order = json_decode($response, true);

        // delete in undelivered_orders table & add in inventory//
        for($i = 0; $i<count($data_order); $i++){
            //echo $data_order[$i]['orderID'].' , '.$data_order[$i]['items'][0]['quantity'].' , '.$data_order[$i]['items'][1]['quantity'];
            delete_undelivered_order($db, $data_order[$i]['orderID']);
            add_into_inventory($db, $data_order[$i]['items'][0]['quantity'], $data_order[$i]['items'][1]['quantity']);
        }

        header("Location: .");
    } catch (PDOException $e) {
        $error_message = $e->getMessage(); 
        include('../../errors/database_error.php');
        exit();
    }
} else if ($action == 'initial_db') {
    try {
        initial_db($db);

        $base_url = $_SERVER['HTTP_HOST'].'/cs637/nichapu/proj2/proj2_server/rest/initial';
        $data = array('day'=>1);
        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
        $response  = curl_exec($ch);
        curl_close($ch);

        header("Location: .");
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include ('../../errors/database_error.php');
        exit();
    } 
}
?>