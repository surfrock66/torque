<?php
require_once("./db.php");

if (isset($_GET["sid"])) {
    $session_id = $_GET['sid'];
    // Get data for session
    $output = "";
    $tableYear = date( "Y", $session_id/1000 );
    $tableMonth = date( "m", $session_id/1000 );
    $db_table_full = "{$db_table}_{$tableYear}_{$tableMonth}";
    if (isset($_GET["from"]) && isset($_GET["to"])) {
	// if a selection on the graph has been made, only export that selection
	$sql = mysqli_query($con, "SELECT * FROM $db_table_full join $db_sessions_table on $db_table_full.session = $db_sessions_table.session WHERE $db_table_full.session=".quote_value($session_id)." AND $db_table_full.time > ".$_GET['from']." AND $db_table_full.time < ".$_GET['to']." ORDER BY $db_table_full.time DESC;") or die(mysqli_error($con));
    }
    else {
	// export full session
	$sql = mysqli_query($con, "SELECT * FROM $db_table_full join $db_sessions_table on $db_table_full.session = $db_sessions_table.session WHERE $db_table_full.session=".quote_value($session_id)." ORDER BY $db_table_full.time DESC;") or die(mysqli_error($con));
    }

    if ($_GET["filetype"] == "csv") {
        $columns_total = mysqli_num_fields($sql);

        // Get The Field Name
	$counter = 0;
        while ($property = mysqli_fetch_field_direct($sql, $counter)) {
            $output .='"'.$property->name.'",';
            $counter++;
        }
        $output .="\n";

        // Get Records from the table
        while ($row = mysqli_fetch_array($sql)) {
            for ($i = 0; $i < $columns_total; $i++) {
                $output .='"'.$row["$i"].'",';
            }
            $output .="\n";
        }

        mysqli_free_result($sql);

        // Download the file
        $csvfilename = "torque_session_".$session_id.".csv";
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$csvfilename);

        echo $output;
        exit;
    }
    else if ($_GET["filetype"] == "json") {
        $rows = array();
        while($r = mysqli_fetch_assoc($sql)) {
            $rows[] = $r;
        }
        $jsonrows = json_encode($rows);

        mysqli_free_result($sql);

        // Download the file
        $jsonfilename = "torque_session_".$session_id.".json";
        header('Content-type: application/json');
        header('Content-Disposition: attachment; filename='.$jsonfilename);

        echo $jsonrows;
    }
}

mysqli_close($con);

?>
