<?php include '../view/header.php'; //echo '----'.count($toppings);?>
<main>
    <section>
        <h1>Topping List</h1>
        <table>
            <tr>
                <th scope="col">Topping Name</th>
                <th></th>
            </tr>
            
            <?php foreach ($toppings as $topping) : ?>
            <tr>
                <td>
                    <?php echo $topping['topping_name']; ?>
                </td>
                <td>
                    <form action="?action=delete_topping" method="POST">
<!--                        <input type="hidden" name="action" value="delete_topping" />            -->
                        <input type="hidden" name="id" value="<?php echo $topping['id']; ?>">
                        <input type='submit' value='Delete'>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p>
            <a href="?action=show_add_form">Add Topping</a>
        </p>
    </section>
</main>
<?php include '../view/footer.php'; ?>
