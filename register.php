<?php require_once ('includes/header.php')?>

<!--Navigation Bar-->
<?php require_once ('includes/nav.php') ?>


<div class="container">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card bg-light mt-5 py-2">
<!--                <p class="text-center text-white bg-danger py-2">--><?php //display_message(); ?><!--</p>-->
                <div class="card-title">
                    <h2 class="text-center mt-2"> Registration Form</h2>
                    <hr>
                </div>
                <div class="card-body">
                    <?php user_validation(); ?>
                    <form method="post" action="">
                        <input type="text" name="first-name" placeholder="First Name" class="form-control py-2 mb-2" required>
                        <input type="text" name="last-name" placeholder="Last Name" class="form-control py-2 mb-2" required>
                        <input type="text" name="username" placeholder="Username" class="form-control py-2 mb-2" required>
                        <input type="email" name="email" placeholder="Email" class="form-control py-2 mb-2" required>
                        <input type="password" name="pass" placeholder="Password" class="form-control py-2 mb-2" required>
                        <input type="password" name="cpass" placeholder="Confirm Password" class="form-control py-2 mb-2" required>

                        <button class="btn btn-success float-right"> Register Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<?php require_once ('includes/footer.php')?>
