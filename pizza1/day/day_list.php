<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Today is day <?php echo $current_day; ?></h1>
        
        <div>
            <form action="?action=change_day" method="POST">
                <button type="submit" name="">Change To day <?php echo $current_day+1; ?></button>
<!--                <input type="hidden" name="action" value="change_day">     -->  
            </form>
        </div>
        <div class="div1">
            <form action="." method="POST">
                <button type="submit" name="">Initialize DB(making day = 1)</button>
                <input type="hidden" name="action" value="initial_db">
            </form>
        </div>
        
        <h2>Today's Orders</h2>
        <table>
            <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Room No</th>
                <th scope="col">Status</th>
            </tr>
            
            <?php foreach ($pizzaOrders as $pizzaOrder) : ?> 
            <tr>
                <td><?php echo $pizzaOrder['id']; ?></td> 
                <td><?php echo $pizzaOrder['room_number']; ?></td>
                <td><?php echo change_status($pizzaOrder['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>
<?php include '../view/footer.php'; ?>