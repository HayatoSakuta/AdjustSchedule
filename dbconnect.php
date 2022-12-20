<?php

function db_connect(){
    $dsn = 'mysql:host=localhost;dbname=adjust_schedule;';
    $user = 'hayatosakuta';
    $password = 'hayato0212';
    try{
        $dbh = new PDO($dsn, $user, $password);
        $dbh->query('SET NAMES utf8');
    }catch(PDOException $e){
        echo 'Error:'.$e;
        die();
    }
    return $dbh;
}

?>