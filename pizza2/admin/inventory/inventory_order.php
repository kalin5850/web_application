<?php include '../../view/header.php'; ?>
    <main>
        <section>
            <h1>Inventory Order</h1>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="new_order">
                <table class="table_blank">
                    <tr>
                        <td>Flour:</td>
                        <td><input type="text" name="flour"/> bags</td>
                    </tr>
                    <tr>
                        <td>Cheese:</td>
                        <td><input type="text" name="cheese"/> containers</td>
                    </tr>
                </table>
                <input type="submit" value=" Order "/>
                <input type="hidden" name="customer" value="<?php echo rand(1,3); ?>">
                <br>
                <br>
            </form>
            <p><a href="index.php">View Inventory List</a></p>
        </section>
    </main>
<?php include '../../view/footer.php'; ?>