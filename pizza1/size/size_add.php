<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Add Size</h1>
        <form action="?action=add_size" method="POST">
            <label>Size Name: 
                <input type='text' name='size_name'>
            </label>
            <p><input type='submit' value="Add"></p>
            <p id='link'><a href=".">View Size List</a></p>           
        </form>
    </section>
</main>
<?php include '../view/footer.php'; ?>