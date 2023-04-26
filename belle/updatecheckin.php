<?php
date_default_timezone_set('Asia/Taipei');

// Retrieve the selected option from the POST data
$payflag = $_POST['option'];
$uuid = $_POST['uuid'];
require_once __DIR__ . "../class/DBConnect.php";// Connect to the database using PDO


    // // constructor for appointment class
    // public function __construct()
    // {
    //     // make a object for database
        try {
            $objDb = new DBConnect;
            $conn = $objDb->connect();

        } catch (PDOException | Exception $th) {
            // throw new Exception($th->getMessage(), 1);
            
        }
    // }
    // public function __destruct()
    // {
    //     // make a object for database
    //     $this->_conn = null;
    // }

    try {
        $now = date("Y-m-d H:i:s");
        $sql = "UPDATE covid_trans SET payflag = :payflag , tdat = :tdat WHERE uuid = :uuid";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':payflag', $payflag);
        $stmt->bindParam(':tdat', $now);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $reponse = array(
            "payflag"=>"$payflag",
            "tdat"=>"$now"
        );
        header("HTTP/1.0 200");
        echo (json_encode($reponse));
        
    } catch (PDOException | Exception $th) {
        header("HTTP/1.0 404 Not Found");
    }
    


// Close the database connection

?>