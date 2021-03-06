<?php
//echo "<!-- Begin del_session.php at ".date("H:i:s", microtime(true))." -->\r\n";
// this page relies on being included from another page that has already connected to db

if (!isset($_SESSION)) { session_start(); }

if (isset($_POST["deletesession"])) {
    $deletesession = preg_replace('/\D/', '', $_POST['deletesession']);
	$deletefrom = preg_replace('/\D/', '', $_POST['from']);
	$deleteto = preg_replace('/\D/', '', $_POST['to']);
}
elseif (isset($_GET["deletesession"])) {
    $deletesession = preg_replace('/\D/', '', $_GET['deletesession']);
	$deletefrom = preg_replace('/\D/', '', $_GET['from']);
	$deleteto = preg_replace('/\D/', '', $_GET['to']);
}

if (isset($deletesession) && !empty($deletesession)) {
    $tableYear = date( "Y", $deletesession/1000 );
    $tableMonth = date( "m", $deletesession/1000 );
    $db_table_full = "{$db_table}_{$tableYear}_{$tableMonth}";
    if (isset($deletefrom) && !empty($deletefrom) && isset($deleteto) && !empty($deleteto)) $range = " AND time >= " . quote_value($deletefrom) . " AND time <= " . quote_value($deleteto);
	else $range = "";
	$delresult = mysqli_query($con, "DELETE FROM $db_table_full WHERE session=".quote_value($deletesession).$range) or die(mysqli_error($con));
    if (empty($range)) $delresult = mysqli_query($con, "DELETE FROM $db_sessions_table WHERE session=".quote_value($deletesession)) or die(mysqli_error($con));
}
//echo "<!-- End del_session.php at ".date("H:i:s", microtime(true))." -->\r\n";
?>
