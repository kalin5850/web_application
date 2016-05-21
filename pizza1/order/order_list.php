<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Current Orders Report</h1>
        <h2>Orders Baked but not delivered</h2>
            <?php foreach ($bakedStatus as $baked) : ?>
            <div>
                ID: <?php echo $baked['id']; ?> Room number: <?php echo $baked['room_number'];?>
            </div>
            <?php endforeach; ?>
        <h2>Orders Preparing(in the oven): Any ready now?</h2>
            <?php foreach ($prepareStatus as $prepare) : ?>
            <div>
                ID: <?php echo $prepare['id']; ?> Room number: <?php echo $prepare['room_number'];?>
            </div>
            <?php endforeach; ?>
        <br> 
        <form  action="?action=change_to_baked" method="post" >
<!--            <input type="hidden" name="action" value="change_to_baked">  -->
            <input type="submit" value="Mark Oldest Pizza Baked (NYI)">
            <br>
        </form>
        <br>  

        <form  action="?action=initial_db" method="post">
<!--            <input type="hidden" name="action" value="initial_db">       -->     
            <input type="submit" value="Initialize DB">
            <br>
        </form>      

    </section>
</main>
<?php include '../view/footer.php'; ?>