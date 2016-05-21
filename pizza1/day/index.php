<?php

require('../model/database.php');
require('../model/day_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

try{
    $current_day = get_day($db);  // TODO: get day from DB
} catch(PDOException $e){
    $error_message = $e->getMessage();
    include ('../errors/database_error.php');
    exit();
}

if ($action == 'initial_db') {
    try {
        initial_database($db);
        header("Location: .");
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include ('../errors/database_error.php');
        exit();
    }
}

else if ($action == 'change_day'){
        try{
            update_day($db);
            header("Location: .");
        }
        catch(PDOException $e){
            $error_message = $e->getMessage();
            include ('../errors/database_error.php');
            exit();
        }
}

$pizzaOrders = get_pizza_orders($db);
include 'day_list.php';

function change_status($value){
    if($value == 1){
        return "PREPARING";
    }
    else if($value == 2){
        return "BAKED";
    }
    else if($value == 3){
        return "FINISHED";
    }
}
?>