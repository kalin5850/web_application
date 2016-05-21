<?php
function get_orders($db) { //
    $query = 'SELECT * FROM pizza_orders group by room_number'; // where day=:day
    $statement = $db->prepare($query);
    //$statement->bindValue(':day',$day);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;
}

function get_current_ingredient($db, $id) {
    $query = 'SELECT * FROM inventory where productID=:id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->execute();
    $units = $statement->fetch();
    $statement->closeCursor();
    //$unit = $units['unit'];
    return $units;
}

function new_supplies_order($db, $order_id, $flour, $cheese){
    $query = 'INSERT INTO undelivered_orders VALUES (:orderId, :flour, :cheese) ';
    $statement = $db->prepare($query);
    $statement->bindValue(':orderId',$order_id);
    $statement->bindValue(':flour',$flour);
    $statement->bindValue(':cheese',$cheese);
    $statement->execute();
    $statement->closeCursor();
}

function add_into_inventory($db, $flour, $cheese){
    $query = "UPDATE inventory SET unit=unit+:flour WHERE productID=11;
              UPDATE inventory SET unit=unit+:cheese WHERE productID=12;";
    echo $query;
    $statement = $db->prepare($query);
    $statement->bindValue(':flour',$flour);
    $statement->bindValue(':cheese',$cheese);
    $statement->execute();
    $statement->closeCursor();
}

function delete_undelivered_order($db, $id){
    $query = 'DELETE FROM undelivered_orders WHERE orderid=:id ';
    $statement = $db->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->execute();
    $statement->closeCursor();
}

function decrease_inventory($db, $flour, $cheese){
    $query = 'UPDATE inventory SET unit=unit-:flour WHERE productID=11;
              UPDATE inventory SET unit=unit-:cheese WHERE productID=12;';
    $statement = $db->prepare($query);
    $statement->bindValue(':flour',$flour);
    $statement->bindValue(':cheese',$cheese);
    $statement->execute();
    $statement->closeCursor();
}

/*function order_inventory($db, $flour, $cheese) {
    $query = 'INSERT INTO pizza_orders(room_number, size, day, status) '
        . 'VALUES (:room,(select size_name from pizza_size where id = :size),:current_day,:status)';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->bindValue(':size',$size);
    $statement->bindValue(':current_day',$current_day);
    $statement->bindValue(':status',$status);
    $statement->execute();
    $statement->closeCursor();
}*/

?>