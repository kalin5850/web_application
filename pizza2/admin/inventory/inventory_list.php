<?php include '../../view/header.php'; ?>
    <main>
        <section>
            <h1>Inventory</h1>
            <?php if($warning != ''){ ?><div class="warning <?php echo $icon_warning; ?>"><?php echo $warning; ?></div><?php } ?>
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