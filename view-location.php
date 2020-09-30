<?php 
session_start();
require_once 'config.php';

if (!isset($_SESSION['m02_loggedIn']) && $_SESSION['m02_loggedIn'] != 'TRUE') {
	header('location: /m02/login');
}

?>

<html>

<head>
    <title>View Location | Tour Management</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- JavaScript from Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!-- CSS from Bootstrap -->
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">

    <!-- Individualised CSS -->
    <link rel="stylesheet" type="text/css" href="style/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>

<body>
    <?php include 'header.php'; ?>
 
    <div class="container sticky-footer">
		<h1 class="text-center mt-3"> View Existing Locations </h1>
		
        <?php
        if ($_SESSION['m02_location_not_found'] === TRUE) {
            echo '
            <div class="alert alert-warning my-4 alert-dismissible fade show" role="alert">
                Location not found.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            
            unset($_SESSION['m02_location_not_found']);
        }
        
        $get_location_sql = 'SELECT * FROM m02_location ORDER BY is_deleted, location_id';
        $get_location = mysqli_query($conn, $get_location_sql);

        if (mysqli_num_rows($get_location) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
							<th scope="col" class="align-middle">ID</th>
							<th scope="col" class="align-middle">Location Name</th>
							<th scope="col" class="align-middle">Coordinate</th>
							<th scope="col" class="align-middle">Minimum Time</th>
							<th scope="col" class="align-middle">Description</th>
                        </tr>
                    </thead>

                    <tbody>
            '; 

            while ($location_list = mysqli_fetch_assoc($get_location)) {
                echo '
                		<tr>
							<td class="align-middle">' . $location_list['location_id'] . '</td>
							<td class="align-middle">' . $location_list['location_name'] . '</td>
							<td class="align-middle">' . $location_list['coordinate'] . '</td>
							<td class="align-middle">' . $location_list['minimum_time'] . '</td>
							<td class="align-middle">' . $location_list['description'] . '</td>
						</tr>
                '; 
            }


            echo '
                    </tbody>
                </table>
            </div>
            ';
        } 

        ?>

	</div>

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>