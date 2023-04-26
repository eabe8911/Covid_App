<?php
header('Content-type: application/json');
try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true); 
    //echo(var_dump($data));die();
    require("dbconfig.php");
    $conn = new PDO("mysql:host=$MySQLServerHost;dbname=$TMS_DB;port=$ServerHostPort", $MySQL_User, $MySQL_Password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare sql and bind parameters
    $sql = "DELETE FROM Advertisement
    WHERE 
    ID = :ID ";
    $sth = $conn->prepare($sql);
    $sth->bindValue(':ID', $data['ID'], PDO::PARAM_STR);
    $sth->execute();
    header("HTTP/1.0 200 OK");
    echo('OK');
}
catch(PDOException | Exception $e){
    header("HTTP/1.0 400 Bad Request");
    echo($e->getMessage());
}
//closes the DB
$conn = null;                                          