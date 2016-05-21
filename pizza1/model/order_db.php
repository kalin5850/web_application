<?php
    function get_BakedStatus($db){
        $query = 'SELECT * FROM pizza_orders where status = 2';
        $statement = $db->prepare($query);
        $statement->execute();
        $baked = $statement->fetchAll();
        return $baked;
    }
    
    function get_PreparingStatus($db){
        $query = 'SELECT * FROM pizza_orders where status = 1';
        $statement = $db->prepare($query);
        $statement->execute();
        $preparingStatus = $statement->fetchAll();
        return $preparingStatus;
    }
    
    function update_Preparing_to_Baked($db){
        $query = 'UPDATE pizza_orders SET status = 2 where status = 1';
        $statement = $db->prepare($query);
        $statement->execute();
    }
    
    function update_OldestPizza($db){
        $query = 'UPDATE pizza_orders SET status = 2
                  WHERE status = 1 and id = (
                        SELECT MIN(a.id) FROM (SELECT * FROM pizza_orders) as a 
                        WHERE status = 1)';
        $statement = $db->prepare($query);
        $statement->execute();
    }
    
    
?>