<?php 
session_start();
require_once 'config.php';

if (isset($_SESSION['m02_loggedIn']) && $_SESSION['m02_loggedIn'] == 'TRUE') {
	// Manage Account functionalities: Activate, Deactivate and Edit
	if (isset($_GET['action']) && !empty($_GET['action'])) {
		switch ($_GET['action']) {
			case 'activate':
				$activate_account_sql = 'UPDATE m02_account SET is_deleted = 0 WHERE account_id = ' . $_GET['id'];
				
				if (mysqli_query($conn, $activate_account_sql)) {
					$activated = TRUE;
				} else {
					echo 'Error: ' . $activate_account_sql . '</br>' . mysqli_error($conn);
				}
				
			break;
				
			case 'deactivate':
				$deactivate_account_sql = 'UPDATE m02_account SET is_deleted = 1 WHERE account_id = ' . $_GET['id'];
				
				if (mysqli_query($conn, $deactivate_account_sql)) {
					$deactivated = TRUE;
				} else {
					echo 'Error: ' . $deactivate_account_sql . '</br>' . mysqli_error($conn);
				}
				
			break;
		}
	}
	
} else {
	header('location: /m02/login');
}

?>

<html>

<head>
    <title>Manage Account | Tour Management</title>

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
		<h2"> Manage Account </h2>
		
		<!-- Activate/Deactivate/Edit Account Validation -->
		<?php 
		if ($_SESSION['m02_edit_account_success'] === TRUE) {
			echo '
			<div class="alert alert-success my-4 alert-dismissible fade show" role="alert">
				Account edited successfully.
				
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			';
			
			unset($_SESSION['m02_edit_account_success']);
		}
		
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
		
		if ($activated === TRUE){
			echo '
			<div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
			Account activated successfully.
			
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				
			</div>
			';
		}
		
		elseif ($deactivated === TRUE){
			echo '
			<div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
			Account deactivated successfully.
			
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				
			</div>
			';
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
                            <th scope="col" class="align-middle">Action</th>
                        </tr>
                    </thead>

                    <tbody>
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
							
							<td class="align-middle">
				';
				
				if ($account_list['is_deleted'] == 1) {
					echo '
					<a class="btn btn-primary" href="manage-account?action=activate&id=' .$account_list['account_id'] . '">Activate</a>
					';
				} elseif ($account_list['is_deleted'] == 0) {
					echo '
					<a class="btn btn-primary" href="edit-account?id=' . $account_list['account_id'] . '">Edit</a>
					
					<a class="btn btn-primary" href="manage-account?action=deactivate&id=' . $account_list['account_id'] . '">Deactivate</a>
					
					';
				}
				
				echo '
				</td>
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

    <!-- View Account field -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>