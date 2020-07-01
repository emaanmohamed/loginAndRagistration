<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <div class="container">
        <a href="index.php" class="navbar navbar-brand"><h3> L$R system </h3></a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Services</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">About</a>
            </li>

            <?php
            if (isset($_SESSION['email']) || isset($_COOKIE['email'])) {
                ?>

                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Logout</a>
                </li>

                <?php
            }
            else {
                ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">Login</a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">Register</a>
                </li>
                <?php
            }
            ?>

        </ul>
        <a href="login.php" class="btn btn-danger mr-1"> Login</a>
        <a href="register.php" class="btn btn-success"> Register</a>
    </div>
</nav>