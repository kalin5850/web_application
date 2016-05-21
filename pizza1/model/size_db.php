<?php
    function add_size($db, $size_name){
        $query = 'INSERT INTO pizza_size(id, size_name) VALUES(null, :size_name)';
        $statement = $db->prepare($query);
        $statement->bindValue(':size_name', $size_name);
        $statement->execute();
        $statement->closeCursor();
    }
    
    function get_size($db){
        $query = 'SELECT * FROM pizza_size';
        $statement = $db->prepare($query);
        $statement->execute();
        $sizes = $statement->fetchall();
        return $sizes;
    }
    
    function delete_size($db, $size_id){
        $query ='DELETE FROM pizza_size WHERE id = :size_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':size_id', $size_id);
        $statement->execute();
        $statement->closeCursor();
    }
?>