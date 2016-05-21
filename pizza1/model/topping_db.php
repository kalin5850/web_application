<?php
// the try/catch for these actions is in the caller
function add_topping($db, $topping_name)  
{
    $query = 'INSERT INTO toppings(id, topping_name) VALUES(null, :topping_name)';
    $statement = $db->prepare($query);
    $statement->bindValue(':topping_name', $topping_name);
    $statement->execute();
    $statement->closeCursor();
}

function get_toppings($db) {
    $query = 'SELECT * FROM toppings';
    $statement = $db->prepare($query);
    $statement->execute();
    $toppings = $statement->fetchAll();
    return $toppings;    
}

function delete_topping($db, $topping_id){
    $query ='DELETE FROM toppings WHERE id = :topping_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':topping_id', $topping_id);
    $statement->execute();
    $statement->closeCursor();
}
?>