<nav class="navbar navbar-expand-lg navbar-dark bg-back sticky-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle Navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarLocation" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tour Management</a>

                <div class="dropdown-menu" aria-labelledby="navbarLocation">
                    <a class="dropdown-item" href="tour.php">Add New Tour</a>

                    <a class="dropdown-item" href="manage-tour.php">Manage Existing Tour</a>

                    <hr>

                    <a class="dropdown-item" href="tour-type.php">Add New Tour Type</a>

                    <a class="dropdown-item" href="#">Manage Existing Tour Type</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarLocation" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Location Management</a>

                <div class="dropdown-menu" aria-labelledby="navbarLocation">
                    <a class="dropdown-item" href="location.php">Add New Location</a>

                    <a class="dropdown-item" href="manage-location.php">Manage Existing Location</a>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li> 

            <!-- If User logs in, header changes -->
            <?php 
            if (!isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] != TRUE) {
                echo '
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                ';
            
            } elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == TRUE) {
                echo '
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarAccount" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Account Management</a>
                
                    <div class="dropdown-menu" aria-labelledby="navbarAccount">
                ';

                if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
                    echo '
                    <a class="dropdown-item" href="view-account.php">View Account</a>

                    <a class="dropdown-item" href="register.php">Register Account</a>
                    ';
                }

                echo '
                    <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
                ';
           
            } 
            ?>
        </ul>
    </div>
</nav>