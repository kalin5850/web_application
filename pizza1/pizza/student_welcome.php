<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Welcome Student!</h1>
        
        <h2>Available Sizes</h2>
        <div class="div5">
            <?php foreach ($getSizes as $size) : ?>
            <?php echo $size['size_name'];?>
            <?php endforeach; ?>
        </div>
           
        <h2>Available Toppings</h2>
        <div class="div6">
            <?php foreach ($getToppings as $topping) : ?>
            <?php echo $topping['topping_name'];?>
            <?php endforeach; ?>
        </div>
        
        <form action="?action=change_roomNumber" method="POST">
            <div class="div7">
                Room No:<select name="roomNumber">                   
                    <?php if($get_room != ""){ ?>
                        <option><?php echo $get_room; ?></option>
                    <?php } ?>
                            <?php foreach ($getRoomNumber as $roomNumber) : ?>
                    <option value="<?php echo $roomNumber['room_number'];?>">
                                <?php echo $roomNumber['room_number']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                <input type="submit" value="Select Room">
            </div>        
         </form>
        
        <h2>Orders in progress for room <?php echo $get_room ?></h2>
        <div>
            <table>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Room No</th>
                    <th scope="col">Toppings</th>
                    <th scope="col">Status</th>
                </tr>
                <?php foreach ($getRoomOrders as $getRoomOrder) : ?>
                <tr>
                    <td><?php echo $getRoomOrder['id'];?></td>
                    <td><?php echo $getRoomOrder['room_number'];?></td>
                    <td><?php echo $getRoomOrder['topping'];?></td>
                    <td><?php echo change_status($getRoomOrder['status']);?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div>
            <form action="?action=change_status" method="POST">
                <input type="hidden" name="roomNumber" value="<?php echo $get_room; ?>">
                <input type="submit" value="Acknowledge Delivery of Baked Pizzas">
            </form>
        </div>
       
        <p><a href="?action=order_pizza&room=1">Order Pizza</a></p>
    </section>
</main>
<?php include '../view/footer.php'; ?>