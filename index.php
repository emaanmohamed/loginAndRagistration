<?php require_once ('includes/header.php')?>
    <!--Navigation Bar-->
 <?php require_once ('includes/nav.php') ?>

<?php

$sql = "SELECT * FROM users";
$query = Query($sql);
confirm($query);
$row = fetch_data($query);
echo $row['email'];


?>

<div class="container">
    <div class="row">
        <div class="col">
           <div class="card bg-light mt-5 py-2">
               <?php display_message(); ?>
               <h3 class="text-center"> Index php page</h3>
           </div>
        </div>
    </div>

</div>

<?php require_once ('includes/footer.php')?>



