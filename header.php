<nav class="navbar navbar-expand-lg navbar-dark bg-back sticky-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle Navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarLocation" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tour Management</a>

                <div class="dropdown-menu" aria-labelledby="navbarLocation">
                    <a class="dropdown-item" href="/tour-management-system/tour.php">Add New Tour</a>

                    <a class="dropdown-item" href="/tour-management-system/modify-tour.php">Edit Existing Tour</a>

                    <hr>

                    <a class="dropdown-item" href="/tour-management-system/tour-type.php">Add New Tour Type</a>

                    <a class="dropdown-item" href="/tour-management-system/list-tour-type.php">View Existing Tour Type</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarLocation" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Location Management</a>

                <div class="dropdown-menu" aria-labelledby="navbarLocation">
                    <a class="dropdown-item" href="location.php">Add New Location</a>

                    <a class="dropdown-item" href="edit-location.php">Edit Existing Location</a>

                    <hr>

                    <a class="dropdown-item" href="delete-location.php">Delete Existing Location</a>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li> 
        </ul>
    </div>
</nav>