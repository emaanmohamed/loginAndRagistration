<?php require_once ('includes/header.php')?>

<!--Navigation Bar-->

<div class="container">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card bg-light mt-5 py-2">
                <div class="card-title">
                    <h2 class="text-center mt-2"> Recover Password</h2>
                    <hr>
                    <?php
                    recover_password();
                    display_message();
                    ?>

                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <input type="email" name="UEmail" placeholder="User Email" class="form-control py-2 mb-2">
                        <input type="hidden" name="token" value="<?php echo Token_Generator();?>">
                    <button class="btn btn-danger float-right"> Cancel </button>
                    <button class="btn btn-success float-left"> Send Password </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once ('includes/footer.php')?>
