<?php
use appoint\Ads_Param;

header('Content-type: application/json');
try {
    require_once ("./Ads_Param.php");
    $param = new Ads_Param();
    $json = file_get_contents('php://input');
    $data = json_decode($json, true); 
    //require("./dbconfig.php");
    $conn = new PDO("mysql:host=$param->MySQLServerHost;dbname=$param->TMS_DB;port=$param->ServerHostPort",
        $param->MySQLUser, $param->MySQLPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT Picture FROM Advertisement
        WHERE ID = :ID AND LENGTH(Picture)>0
    ";
    $sth = $conn->prepare($sql);
    $sth->bindValue(":ID", $data['ID']);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $Count = count($result);
    if( $Count > 0 ) {
        header("HTTP/1.0 200 Rows");
        $image = $result[0]['Picture'];
        if($image !== ''){
            //echo("data:image/png;base64,$image");
            echo($image);

        }else{
            echo("");
        }
    }else{
        header("HTTP/1.0 202 Data not found");
        echo("");
    }
} catch (PDOException | Exception $e) {
    header("HTTP/1.0 400 Bad Request");
    echo($e->getMessage());
}
//closes the DB
$conn = null;                                          
