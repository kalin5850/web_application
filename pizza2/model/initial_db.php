<?php

function initial_db($db) {
    try {
        $query = 'delete from order_topping;';
        $query.='delete from pizza_orders;';
        $query.='delete from pizza_size;';
        $query.='delete from toppings;';
        $query.='delete from pizza_sys_tab;';
        $query.='delete from undelivered_orders;';
        $query.='insert into pizza_sys_tab values (1);';
        $query.="insert into toppings values (1,'Pepperoni');";
        $query.="insert into pizza_size values (1,'Small');";
        $query.='update inventory set unit=100 where productID=11;';
        $query.='update inventory set unit=120 where productID=12;';
        $statement = $db->prepare($query);
        $statement->execute();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('errors/database_error.php');
        exit();
    }
    return $statement;
}
?>

