<?php
namespace Advertisement;

use PDO;
use PDOException;
use Advertisement\Ads_Global;

header("Content-type: application/json");
//$json = file_get_contents('php://input');
try {
    require ("./class/Ads_Global.php");
    $adsGlobal = new Ads_Global();
    //$data = json_decode($json, true);
    $conn = new PDO("mysql:host=$adsGlobal->MySQLServerHost;dbname=$adsGlobal->TMS_DB;port=$adsGlobal->ServerHostPort", $adsGlobal->MySQL_User, $adsGlobal->MySQL_Password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT ID, PromotionName FROM Promotion ";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    if (empty($result)) {
        header("HTTP/1.0 202 Data not found");
    } else {
        header("HTTP/1.0 200 OK");
        echo json_encode($result);
    }
} catch (PDOException | Exception $e) {
    header("HTTP/1.0 400 Bad Request");
    echo($e->getMessage());
}
