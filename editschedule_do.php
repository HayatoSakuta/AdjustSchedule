<?php
require('dbconnect.php');
$dbh = db_connect();

if(!empty($_POST['date'])){
    $scheaction = $_GET['scheaction'];
    $id = $_GET['id'];
    $scheid = $_GET['scheid'];
    $date = $_POST['date'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $applicant = $_POST['applicant'];
    try{
        if(strcmp($scheaction,"edit")==0){
            $sql = "UPDATE schedule SET date='" .$date. "', time_start='" .$time_start. "', time_end='" .$time_end. "', applicant='" .$applicant. "' WHERE " .$scheid. " = pk_schedule_id";
        }elseif(strcmp($scheaction,"add")==0){
            $sql = "INSERT INTO schedule (fk_event_id,date,time_start,time_end,applicant) VALUES (".$id.",'".$date."','".$time_start."','".$time_end."','".$applicant."')";
        }
        
        $dbh->query($sql);
    }catch(PDOException $e){
        echo 'Error:'.$e;
        die();
    }
}
header("Location:index.php?id=".$id);

?>