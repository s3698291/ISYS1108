<?php 
session_start();
require_once 'config.php';

if (!isset($_SESSION['m02_loggedIn']) && $_SESSION['m02_loggedIn'] != 'TRUE') {
	header('location: /m02/login');
} 

?>

<html>

<head>
    <title>View Account | Tour Management</title>

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

    <!-- View Account field -->

    <div class="container sticky-footer">
		<h2> View Existing Accounts </h2>
		
		<?php
        if ($_SESSION['m02_account_not_found'] === TRUE) {
            echo '
            <div class="alert alert-warning my-4 alert-dismissible fade show" role="alert">
                Account not found.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            
            unset($_SESSION['m02_account_not_found']);
        }
		
		$get_account_sql = 'SELECT * FROM m02_account ORDER BY is_deleted, account_id';
		$get_account = mysqli_query($conn, $get_account_sql);
		
		if (mysqli_num_rows($get_account) > 0) {
			echo '
			<div class="table-responsive">
				<table class="table table-hover text-center">
					<thead>
						<tr>
							<th scope="col" class="align-middle">ID</th>
							<th scope="col" class="align-middle">Full Name</th>
							<th scope="col" class="align-middle">Username</th>
							<th scope="col" class="align-middle">Role</th>
						</tr>
					</thead>
			';
			
			while ($account_list = mysqli_fetch_assoc($get_account)) {
				$get_account_role_sql = 'SELECT role_type FROM m02_role WHERE role_id = ' . $account_list['role'];
				$get_account_role = mysqli_query($conn, $get_account_role_sql);
				
				while ($account_role = mysqli_fetch_assoc($get_account_role)) {
					$role = $account_role['role_type'];
				}
				
				echo '
						<tr>
							<td class="align-middle">' . $account_list['account_id'] . '</td>
							<td class="align-middle">' . $account_list['full_name'] . '</td>
							<td class="align-middle">' . $account_list['username'] . '</td>
							<td class="align-middle">' . $role . '</td>
						</tr>
				';
			}
			
			echo '
					<tbody>
				</table>
			</div>
			';
		}
		?>

    </div>

    <!-- View Account field -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>