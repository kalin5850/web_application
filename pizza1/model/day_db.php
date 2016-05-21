<?php
    function get_day($db){
        $query = 'SELECT current_day FROM pizza_sys_tab';
        $statement = $db->prepare($query);
        $statement->execute();
        $day = $statement->fetch();
        $current_day = $day['current_day'];
        return $current_day;
    }
    
    function update_day($db){
        $query = 'UPDATE pizza_sys_tab SET current_day = current_day + 1';
        $statement = $db->prepare($query);
        $statement->execute();
    }
    
    function initial_database($db){
        $query = 'UPDATE pizza_sys_tab SET current_day = 1';
        $statement = $db->prepare($query);
        $statement->execute();
    }
    
    function get_pizza_orders($db){
        $query = 'SELECT * FROM pizza_sys_tab pst, pizza_orders po 
                  where pst.current_day = po.day';
        
        $statement = $db->prepare($query);
        $statement->execute();
        $orders = $statement->fetchAll();
        return $orders;
    }
?>