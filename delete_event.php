<?php

require('dbconnect.php');
$dbh = db_connect();

$sql = 'DELETE FROM event WHERE pk_event_id = :delete_id';
$stmt = $dbh->prepare($sql);
$stmt->execute(array((':delete_id') => $_REQUEST['id']));

header("Location:index.php");
exit();

?>