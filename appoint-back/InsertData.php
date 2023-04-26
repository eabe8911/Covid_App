
<?php
header('Content-type: application/json');
try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true); 
    require("./dbconfig.php");
    // TODO 如果要放到客戶端時, 需要把資料庫換成 MiddleWare 上的資料表
    $conn = new PDO("mysql:host=$MySQLServerHost;dbname=$TMS_DB;port=$ServerHostPort", $MySQL_User, $MySQL_Password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare sql and bind parameters
    $sql = "INSERT INTO Advertisement (
    Name, Type, StoreID, Image, Start_Date, End_Date, PromotionID, Create_Date, PID, Picture)
    VALUES (
    :Name, :Type, :StoreID, :Image, :Start_Date, :End_Date, :PromotionID, :Create_Date, :PID ,:Picture)";
    $sth = $conn->prepare($sql);
    $sth->bindValue(':Name', $data['Name']);
    $sth->bindValue(':Type', $data['Type']);
    $sth->bindValue(':StoreID', $data['StoreID']);
    $sth->bindValue(':Image', $data['Image']);
    $sth->bindValue(':Start_Date', $data['Start_Date'].' 00:00:00', PDO::PARAM_STR);
    $sth->bindValue(':End_Date', $data['End_Date'].' 00:00:00', PDO::PARAM_STR);
    $sth->bindValue(':PromotionID', $data['PromotionID']);
    $sth->bindValue(':Create_Date', date("Y-m-d H:i:s"));
    $sth->bindValue(':PID', $data['PID']);
    $sth->bindValue(':Picture', $data['Picture']);
    $sth->execute();
    header("HTTP/1.0 200");
    echo('OK');
}
catch(PDOException | Exception $e){
    header("HTTP/1.0 400");
     echo(" Bad Request " . $e->getMessage());
}
//closes the DB
$conn = null;
/* TODO 要放到客戶端時, 因為 Advertisement 是放在 MiddleWare, 所以就需要把資料存回 TMS
try {
    $advertisement = new Advertisement();
    if($advertisement->setAdvertisement($data)){
        // 存檔成功
    }else{
        // 存檔失敗
    }          
} catch (Exception $e) {
    //throw $th;
}
      */
?>