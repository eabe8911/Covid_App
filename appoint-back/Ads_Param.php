<?php
namespace appoint;

/**
 * 共用參數讀取用
 * Class Ads_Param
 * @package appoint
 */
class Ads_Param
{
    public $TOKEN;
    public $errorMessage;
    public $CMSMemberURL;
    public $CMSLoyaltyURL;
    public $MySQLServerHost;
    public $ServerHostPort;
    public $MySQLPassword;
    public $MySQLUser;
    public $Reference_DB;
    public $Loyalty_DB;
    public $KMS_DB;
    public $LOG_DB;
    public $CMS_DB;
    public $TMS_DB;

    public function __construct()
    {
        try {
            $this->TOKEN = "sqlkey";
            $this->MySQLServerHost = "maxcheng.tw";
            $this->ServerHostPort = "3307";
            $this->MySQLUser = "root";
            $this->MySQLPassword = ",-4,4p-2";

            // if($_SERVER['HTTP_HOST'] == 'localhost'){
            //     // $this->MySQLServerHost = "192.168.2.115";
            //     $this->MySQLServerHost = "192.168.2.126";
            //     $this->ServerHostPort = "3306";
            //     $this->MySQLUser = "root";
            //     $this->MySQLPassword = "password";
            // }else{
                //  $this->MySQLServerHost = "localhost";
                //  $this->ServerHostPort = "3306";
                //  $this->MySQLUser = "libo_user";
                //  $this->MySQLPassword = "xxx";
            // }


            $this->CMS_DB = "CMS_DB";
            $this->TMS_DB = "TMS_DB";
            $this->LOG_DB = "TransLog";
            $this->KMS_DB = "KMS_DB";
            $this->Loyalty_DB = "Loyalty";
            $this->Reference_DB = "Reference";
            $this->LIBOBIO_DB = "libodb";
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->__destruct();
            return false;
        }
        return true;
    }
}