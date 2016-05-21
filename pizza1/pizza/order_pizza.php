<?php include '../view/header.php'; ?>
<main>
    <section>
        <form action="?action=add_order" method="POST">
            <h1>Order Pizza </h1>
            
            <div class="div2">
            <h2 class="h2">Pizza Size:</h2>
                <?php foreach ($getSizes as $getSize) : ?>
            <label>
                <input id="i1" type="radio" name="pizzaSize" value="<?php echo $getSize['size_name']; ?>">
                <?php echo $getSize['size_name']; ?>
            </label>
                <?php endforeach; ?>
            </div> 
            
            <h2 class="h3">Toppings:</h2>
                <?php foreach ($getToppings as $getTopping) : ?>
            <div>
                <label>
                    <input id="i2" type="checkbox" name="pizzaTopping[]" value="<?php echo $getTopping['topping_name'];?>">
                    <?php echo $getTopping['topping_name'];?>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="div3">
                <label>Room No:
                <select name="roomNumber">
                <?php for($i = 1; $i <= 100; $i++){?>
                    <option value="<?php echo $i ;?>"><?php echo $i ;?></option>
                <?php }?>
                </select>
                </label>
            </div>    
            <div class="div4">   
                <input id="i3" type="submit" value="order_pizza">
            </div>
        </form>
    </section>
</main>
<?php include '../view/footer.php'; ?>