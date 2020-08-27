<?php
session_start();
require_once 'config.php';

/*if (!isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] != TRUE) || $_SESSION['role'] != 'Admin') {
    header('location: login.php');
}
*/

$get_location_sql = "SELECT location_name, coordinate, description, minimum_time FROM m02_location WHERE location_id= ?";

$location_stmt = mysqli_prepare($conn, $get_location_sql);

mysqli_stmt_bind_param($location_stmt, 'i', $_GET['id']);
mysqli_stmt_execute($location_stmt);
mysqli_stmt_store_result($location_stmt);
mysqli_stmt_bind_result($location_stmt, $location_name, $location_coordinate, $location_description, $location_min_time);
mysqli_close($location_stmt);

while(mysqli_stmt_fetch($location_stmt)) {
    $location[] = array('LocationName'=>$location_name, 'Coordinate'=>$location_coordinate, 'MinTime'=>$location_min_time, 'Description'=>$location_description);
}

echo json_encode($location);
?>