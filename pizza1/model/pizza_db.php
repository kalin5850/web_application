<?php
    function get_RoomNumber($db){
        $query = 'select distinct room_number from pizza_orders';
        $statement = $db->prepare($query);
        $statement->execute();
        $roomNumber = $statement->fetchAll();
        return $roomNumber;
    }
    
    function get_RoomOrders($db, $roomNumber){
        $query = 'select * from pizza_orders p, order_topping o 
                  where p.id = o.order_id and p.room_number = :roomNumber';
        $statement = $db->prepare($query);
        $statement->bindValue(':roomNumber', $roomNumber);
        $statement->execute();
        $roomOrders = $statement->fetchAll();
        return $roomOrders;
    }
    
    function add_pizza_order($db, $pizzaSize, $roomNumber, $getDay){
        $status = '1';
        $query = 'INSERT INTO pizza_orders VALUES (null, :roomNumber, :size, :day, :status)';
        $statement = $db->prepare($query);
        $statement->bindValue(':roomNumber', $roomNumber);
        $statement->bindValue(':size', $pizzaSize);
        $statement->bindValue(':day', $getDay);
        $statement->bindValue(':status', $status);
        $statement->execute();
    }
    
    function get_id($db){
        $query ='select max(id) from pizza_orders';
        $statement = $db->prepare($query);
        $statement->execute();
        $getID = $statement->fetch();
        return $getID;
    }
    
    function add_order_topping($db, $getID, $value){
        $query = 'insert into order_topping values(:getID, :value)';
        $statement = $db->prepare($query);
        $statement->bindValue(':getID', $getID);
        $statement->bindValue(':value', $value);
        $statement->execute();
    }
    
    function update_status_to_finished($db, $roomNumber){
        $query = 'update pizza_orders set status = 3 where room_number = :roomNumber;';
        $statement = $db->prepare($query);
        $statement->bindValue(':roomNumber', $roomNumber);
        $statement->execute();
    }
    
    function get_minRoomNumber($db){
        $query = 'select min(room_number) from pizza_orders;';
        $statement = $db->prepare($query);
        $statement->execute();
        $min = $statement->fetch();
        $roomnumber = $min['0'];
        return $roomnumber;
    }
?>
