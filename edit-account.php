<?php
session_start();
require_once 'config.php';

$full_name = $username = $password = $confirm_password = $role = "";
$full_name_error = $username_error = $password_error = $confirm_password_error = $role_error = "";

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$get_account_id = $_GET['id'];
	
	$get_account_information_sql = 'SELECT full_name, username, role FROM m02_account WHERE account_id = ?';
	
	if ($get_account_information_stmt = mysqli_prepare($conn, $get_account_information_sql)) {
		mysqli_stmt_bind_param($get_account_information_stmt, 'i', $param_account_id);
		
		$param_account_id = $get_account_id;
		
		if (mysqli_stmt_execute($get_account_information_stmt)) {
			mysqli_stmt_store_result($get_account_information_stmt);
			
			if (mysqli_stmt_num_rows($get_account_information_stmt) > 0) {
				mysqli_stmt_bind_result($get_account_information_stmt, $saved_full_name, $saved_username, $saved_role);
				
				mysqli_stmt_fetch($get_account_information_stmt);
			} else {
				$_SESSION['m02_account_not_found'] = TRUE;
				header('location: manage-account');
			}
		}
	}
	
	mysqli_stmt_close($get_account_information_stmt);

} else {
	// Main functionality of Edit Account
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$id = $_POST['id'];
		
		// Full Name: Validate and submit
		if (empty(trim($_POST['FullName']))) {
			$full_name_error = "Please enter staff\'s' full name.";
		} else {
			$full_name = (trim($_POST['FullName']));
		}
		
		// Username: Validate and submit
		if (empty(trim($_POST['Username']))) {
			$username_error = "Please enter staff\'s username.";
		} else {
			$username = (trim($_POST['Username']));
		}
		
		// Password: Validate and submit
		if (empty(trim($_POST['Password']))) {
			$password_error = "Please enter a password.";
		} else {
			$password = (trim($_POST['Password']));
		}
		
		// Confirm Password: Validate and submit
		if (empty(trim($_POST['ConfirmPassword']))) {
			$confirm_password_error = "Please confirm the password.";
		} else {
			$confirm_password = (trim($_POST['ConfirmPassword']));
			
			if (empty($confirm_password_error) && empty($password_error) && ($password != $confirm_password)) {
                    $confirm_password_error = $password_error = 'Password did not matched.';
                }
		}
		
		// Role: Validate and submit
		if (empty(trim($_POST['Role']))) {
			$role_error = "Please enter staff\s role.";
		} else {
			$role = (trim($_POST['Role']));
		}
		
			if (empty($full_name_error) && empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($role_error)) {
				$updateAccountSql = "UPDATE m02_account SET full_name='" . $full_name . "', username='" . $username . "', password='" . password_hash($password, PASSWORD_DEFAULT) . "', role='" . $role . "' WHERE account_id=" . $id;
				
				if ($stmt = mysqli_prepare($conn, $updateAccountSql)) {
					mysqli_stmt_bind_param($stmt, 'sssi', $param_full_name, $param_username, $param_password, $param_role);
					
					$param_full_name = $full_name;
					$param_username = $username;
					$param_password = password_hash($password, PASSWORD_DEFAULT);
					$param_role = $role;
					
					if (mysqli_stmt_execute($stmt)) {
						$account_Updated = TRUE;
						unset($_POST);
					} else {
						echo 'Error: ' . $updateAccountSql . '</br>' . mysqli_error($conn);
					}
					
				mysqli_stmt_close($stmt);
					
				$_SESSION['m02_edit_account_success'] = TRUE;
				header('location: manage-account');
					
				}
			} else {
				$update_error = TRUE;
			}
	} else {
		header('location: manage-account');
	}
}

?>
<html>
	
<head>
	<title>Edit Account | Tour Management</title>
	
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
	
	<!-- Edit Account Field -->
	<div class="container sticky-footer">
		<h2">Edit Account</h2>
		
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
			<input type="hidden" id="id" name="id" value="<?php echo !empty($_POST['id']) ? $_POST['id'] : $_GET['id']; ?>">
			
			<div id="AccountInfo" class="<?php echo isset($update_error) && $update_error == TRUE ? 'd-block' : ''; ?>">
				<div class="form-group row">
					<label for="FullName" class="col-sm-2 col-form-label">Full Name</label>
					
					<div class="col-sm-10">
						<input type="text" class="form-control <?php echo (!empty($full_name_error)) ? 'border border-danger' : ''; ?>" id="FullName" name="FullName" placeholder="Full Name" value="<?php echo !empty($_POST['FullName']) ? $_POST['FullName'] : $saved_full_name; ?>">
						
						<p class="text-danger">
							<?php echo $full_name_error; ?>
						</p>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="Username" class="col-sm-2 col-form-label">Username</label>
					
					<div class="col-sm-10">
						<input type="text" class="form-control <?php echo (!empty($username_error)) ? 'border border-danger' : ''; ?>" id="Username" name="Username" placeholder="Username" value="<?php echo !empty($_POST['Username']) ? $_POST['Username'] : $saved_username; ?>">
						
						<p class="text-danger">
							<?php echo $username_error; ?>
						</p>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="Password" class="col-sm-2 col-form-label">Password</label>
					
					<div class="col-sm-10">
						<input type="password" class="form-control <?php echo (!empty($password_error)) ? 'border border-danger' : ''; ?>" id="Password" name="Password" placeholder="Enter Password" value="<?php echo !empty($_POST['Password']) ? $_POST['Password'] : ''; ?>">
						
						<p class="text-danger">
							<?php echo $password_error; ?>
						</p>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="ConfirmPassword" class="col-sm-2 col-form-label">Confirm Password</label>
					
					<div class="col-sm-10">
						<input type="password" class="form-control <?php echo (!empty($confirm_password_error)) ? 'border border-danger' : ''; ?>" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password" value="<?php echo !empty($_POST['ConfirmPassword']) ? $_POST['ConfirmPassword'] : ''; ?>">
						
						<p class="text-danger">
							<?php echo $confirm_password_error; ?>
						</p>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="Role" class="col-sm-2 col-form-label">Role</label>
					
					<div class="col-sm-10">
						<select class="form-control <?php echo (!empty($account_role_error)) ? 'border border-danger' : ''; ?>" id="Role" name="Role">
							<option value="<?php echo !empty($_POST['Role']) ? $_POST['Role'] : $saved_role; ?>"> Select Role</option>
							
							<!-- Show dropdown list from Account Role table -->
							<?php
							$get_account_role_sql = "SELECT * FROM m02_role ORDER BY role_type ASC";
							$get_account_role = mysqli_query($conn, $get_account_role_sql);
							
							if (mysqli_num_rows($get_account_role) > 0){
								while ($role = mysqli_fetch_assoc($get_account_role)) {
									$select_role = ((isset($_POST['Role']) && $_POST['Role'] == $role['role_id']) || (isset($saved_role) && $saved_role == $role['role_id'])) ? 'selected="selected" ' : '';
									
									echo '<option value="' . $role['role_id'] . '"' . $select_role . '>' . $role['role_type'] . '</option>';
								}
							}
							?>
						</select>
						
						<p class="text-danger">
							<?php echo $role_error; ?>
						</p>
					</div>
				</div>
				
			</div>
			
			<button id="UpdateButton" type="submit" class="btn btn-primary btn-block">Save Changes</button>
		</form>
	</div>
	
	<!-- Edit Account Field -->
	
	<?php include'footer.php'; ?>
</body>
	
</html>

<?php mysqli_close($conn); ?>