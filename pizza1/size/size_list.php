<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Size List</h1>
        <table>
            <tr>
                <th scope="col">Size Name</th>
                <th></th>
            </tr>
            
            <?php foreach ($sizes as $size) : ?>
            <tr>
                <td>
                    <?php echo $size['size_name']; ?>
                </td>
                <td>
                    <form action="?action=delete_size" method="POST">
                        <input type="hidden" name="id" value="<?php echo $size['id']; ?>">
                        <input type='submit' value='Delete'>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p>
            <a href="?action=show_add_form">Add Size</a>
        </p>
    </section>
</main>
<?php include '../view/footer.php'; ?>