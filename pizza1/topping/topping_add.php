<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Add Toppings</h1>
        <form action="?action=add_topping" method="POST">
            <label>Topping Name: 
                <input type='text' name='topping_name'>
            </label>
            <p><input type='submit' value="Add"></p>
            <p id='link'><a href=".">View Topping List</a></p>           
        </form>    
    </section>
</main>
<?php include '../view/footer.php'; ?>
