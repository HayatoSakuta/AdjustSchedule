<?php

require('dbconnect.php');
$dbh = db_connect();

$sql = 'DELETE FROM schedule WHERE pk_schedule_id = :delete_scheid';
$stmt = $dbh->prepare($sql);
$stmt->execute(array((':delete_scheid') => $_REQUEST['scheid']));

header("Location:index.php?id=".$_REQUEST['id']);
exit();

?>