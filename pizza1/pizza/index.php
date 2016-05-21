<?php
    require '../model/database.php';
    require '../model/size_db.php';
    require '../model/topping_db.php';
    require '../model/order_db.php';
    require '../model/day_db.php';
    require '../model/pizza_db.php';

    $action = filter_input(INPUT_POST, 'action');
    if ($action == NULL){
        $action = filter_input(INPUT_GET, 'action');
        if ($action == NULL){
            $action = 'welcome_student';
        }
    }
    
    if ($action == 'welcome_student'){
        try{
            $get_room = get_minRoomNumber($db);
            $getSizes = get_size($db);
            $getToppings = get_toppings($db);
            $getRoomNumber = get_RoomNumber($db);
            $getRoomOrders = get_RoomOrders($db, $get_room);
            include('student_welcome.php');
        }
        catch (PDOException $e) {
            $error_message = $e->getMessage();
            include('../errors/database_error.php');
            exit();
        }
    }
    else if ($action == 'order_pizza'){
            $getSizes = get_size($db);
            $getToppings = get_toppings($db);
            include 'order_pizza.php';
    }
    else if ($action == 'change_roomNumber'){
        try{
            $get_room = filter_input(INPUT_POST, 'roomNumber', FILTER_VALIDATE_INT);
            $getSizes = get_size($db);
            $getToppings = get_toppings($db);
            $getRoomNumber = get_RoomNumber($db);
            $getRoomOrders = get_RoomOrders($db, $get_room);
            include('student_welcome.php');
        }
        catch (PDOException $e) {
            $error_message = $e->getMessage();
            include('../errors/database_error.php');
            exit();
        }
    }
    else if ($action == 'add_order'){
        $pizzaSize = filter_input(INPUT_POST, 'pizzaSize');      
        $pizzaToppings = filter_input(INPUT_POST, 'pizzaTopping', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);       
        $roomNumber = filter_input(INPUT_POST, 'roomNumber', FILTER_VALIDATE_INT);
            
        if ($pizzaSize == NULL || $pizzaSize == FALSE ||
                $pizzaToppings == NULL || $pizzaToppings == FALSE ||
                $roomNumber == NULL || $roomNumber == FALSE){
            $error = "Invalid topping name";
            include('../errors/error.php');
            exit();
        }
        else {
            try{
                $getDay = get_day($db);
                add_pizza_order($db, $pizzaSize, $roomNumber, $getDay);
                $getID = get_id($db);
                $id = $getID['0'];
                $topping;
                foreach ($pizzaToppings as $key => $value) {
                    $value .= " ";
                    $topping .= $value;
                }
                add_order_topping($db, $id, $topping);
                header("Location: .");
            }
            catch (PDOException $e) {
                $error_message = $e->getMessage();
                nclude('../errors/database_error.php');
                exit();  // needed here to avoid redirection of next line
            }
        }
    }
    else if ($action == 'change_status'){
        $roomNumber = filter_input(INPUT_POST, 'roomNumber', FILTER_VALIDATE_INT);
        update_status_to_finished($db, $roomNumber);
        header("Location: .");
    }
    
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
    
    function change_room(){
        $get_room = filter_input(INPUT_POST, 'roomNumber', FILTER_VALIDATE_INT);
        if ($get_room == NULL || $get_room == FALSE){
            return 1;
        }
        else{
            return $get_room;
        }
    }

?>