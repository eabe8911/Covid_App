<?php
//echo($_SERVER["HOST"]);die();
try {
    $SQLKEY="sjenkey";
    //echo($_SERVER["HOST"]);die();
    // if($_SERVER["HOST"]=="app.maxcheng.tw"){
        //$CMS_Member_URL = "https://cms.maxcheng.tw/CMS_Member.php";
        $MySQLServerHost = "maxcheng.tw";
        $ServerHostPort = "3307";
        //echo("Old server");
        $MySQL_User = "root";
        $MySQL_Password = ",-4,4p-2";
    // }else{

        //echo("new server");
        //$CMS_Member_URL = "https://app.sjen.com.tw/cms/CMS_Member.php";
    //     $MySQLServerHost = getenv('MYSQL_HOST', true);
    //     $ServerHostPort = getenv('MYSQL_PORT', true);
    //     $MySQL_User = "root";
    //     $MySQL_Password = "55wrtv5u";

    // }
    $CMS_DB = "CMS_DB";
    $TMS_DB = "TMS_DB";
    $LOG_DB = "TransLog";
    $KMS_DB = "KMS_DB";
    $Loyalty_DB = "Loyalty";
    $Reference_DB = "Reference";
} catch (Exception $e) {
    return $e->getMessage();
}
?>