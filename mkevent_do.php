<?php
require('dbconnect.php');
$dbh = db_connect();

if(!empty($_POST['event'])){
    $event = $_POST['event'];
    try{
        $sql = "INSERT INTO event (eventname) VALUES ('".$event."')";
        $dbh->query($sql);
    }catch(PDOException $e){
        echo 'Error:'.$e;
        die();
    }
}
header("Location:index.php");

?>