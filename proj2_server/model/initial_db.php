<?php

function initial_db() {
    try {
        global $db;
        $query = 'delete from orders;';
        $query.='delete from orderitems;';
        $query.="INSERT INTO orders (orderID, customerID, orderDate, shipAmount, taxAmount, shipDate, shipAddressID, cardType, cardNumber, cardExpires, billingAddressID) VALUES
                (1, 1, '2010-05-30 09:40:28', '5.00', '32.32', '2010-06-01 09:43:13', 1, 2, '4111111111111111', '04/2015', 2),
                (2, 2, '2010-06-01 11:23:20', '5.00', '0.00', NULL, 3, 2, '4111111111111111', '08/2014', 4),
                (3, 1, '2010-06-03 09:44:58', '10.00', '89.92', NULL, 1, 2, '4111111111111111', '04/2014', 2);
                INSERT INTO orderItems (itemID, orderID, productID, itemPrice, discountAmount, quantity) VALUES
                (1, 1, 2, '399.00', '39.90', 1),
                (2, 2, 4, '699.00', '69.90', 1),
                (3, 3, 3, '499.00', '49.90', 1),
                (4, 3, 6, '549.99', '0.00', 1);";
        $query.='ALTER TABLE orders AUTO_INCREMENT = 1;';
        $query.='ALTER TABLE orderitems AUTO_INCREMENT = 1;';
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

