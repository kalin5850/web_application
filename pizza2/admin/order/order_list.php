<?php include '../../view/header.php'; ?>
<main>
    <section>
    <h1>Current Orders Report</h1>
        <h2>Orders Baked but not delivered</h2>
        <?php if (count($baked_orders) > 0): ?>
            <?php foreach ($baked_orders as $baked_order) : ?>
                <?php echo " ID:" . $baked_order['id']; ?>
                <?php echo "Room number:" . $baked_order['room_number']; ?><br>  
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Baked orders</p>
        <?php endif; ?>

        <h2>Orders Preparing(in the oven): Any ready now?</h2>
        <?php if (count($preparing_orders) > 0): ?>
            <?php foreach ($preparing_orders as $preparing_order) : ?>
                <?php echo "ID:" . $preparing_order['id']; ?> 
                <?php echo "Room number:" . $preparing_order['room_number']; ?>  
             <?php endforeach; ?>
        <?php else: ?>
            <p>No orders are being prepared in Oven</p>
        <?php endif; ?>
        <br> 
        <form  action="index.php" method="post" >
            <input type="hidden" name="action" value="change_to_baked">
            <input type="submit" value="Mark Oldest Pizza Baked" />
            <br>
        </form>
        <br>
        <h1>Inventory</h1>
        <h2>Supplies on Order</h2>
        <table>
            <tr>
                <th>Order</th>
                <th>Flour</th>
                <th>Cheese</th>
                <th>Status</th>
            </tr>
            <?php for($i = 0; $i<count($data_order); $i++){ ?>
                <tr>
                    <td><?php echo $data_order[$i]['orderID']; ?> </td>
                    <td><?php echo $data_order[$i]['items'][0]['quantity']; ?> </td>
                    <td><?php echo $data_order[$i]['items'][1]['quantity']; ?> </td>
                    <td><?php echo transStatus($data_order[$i]['delivered']); ?></td>
                </tr>
            <?php } ?>
        </table>
        <h2>Current Inventory</h2>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Unit</th>
            </tr>
            <tr>
                <td><?php echo $get_flour['productID']; ?> </td>
                <td><?php echo $get_flour['productCode']; ?> </td>
                <td><?php echo $get_flour['unit']; ?> </td>
            </tr>
            <tr>
                <td><?php echo $get_cheese['productID']; ?> </td>
                <td><?php echo $get_cheese['productCode']; ?> </td>
                <td><?php echo $get_cheese['unit']; ?> </td>
            </tr>
        </table>
        <p>
            <a href=".?action=show_add_form">New Order</a>
        </p>
    </section>
</main>
<?php include '../../view/footer.php'; ?>