<?php
    require '../model/database.php';
    require '../model/size_db.php';
    
    $action = filter_input(INPUT_POST, 'action');
    if ($action == null){
        $action = filter_input(INPUT_GET, 'action');
        if ($action == null){
            $action = 'list_sizes';
        }
    }
    
    if ($action == 'list_sizes'){
        try{
            $sizes = get_size($db);
            include ('size_list.php');
        }
        catch(PDOException $e){
            $error_message = $e->getMessage();
            include('../errors/database_error.php');
        }
    }
    else if ($action == 'delete_size'){
        $size_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($size_id == NULL || $size_id == FALSE){
            $error = 'Invalid size id';
            include('../errors/error.php');
        }
        else{
            try{
                delete_size($db, $size_id);
                header("Location: .");
            }
            catch (Exception $e) {
                $error_message = $e->getMessage();
                include('../errors/database_error.php');
                exit();
            }
        }
    }
    
    else if ($action == 'show_add_form'){
        include ('size_add.php');
    }
    
    else if ($action == 'add_size'){
        $size_name = filter_input(INPUT_POST, 'size_name');
        if ($size_name == NULL || $size_name == FALSE){
            $error = "Invalid topping name";
            include('../errors/error.php');
        }
        else{
            try{
                add_size($db, $size_name);
                header("Location: .");
            }
            catch (Exception $e) {
                $error_message = $e->getMessage();
                include('../errors/database_error.php');
                exit();
            }
        }
    }
?>