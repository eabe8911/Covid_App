<?php
namespace Advertisement;

use Exception;

class Ads_Global
{
    public $CMSMemberURL = "";
    public $CMSLoyaltyURL = "";
    public $MySQLServerHost = "";
    public $ServerHostPort = "";
    public $MySQLPassword = "";
    public $MySQLUser = "";
    public $Reference_DB = "";
    public $Loyalty_DB = "";
    public $KMS_DB = "";
    public $TMS_DB;
    public $LOG_DB = "";
    public $CMS_DB = "";
    private $_errorMessage = "";
    private $_token = "";

    public function __construct()
    {
        $this->_token = "sjenkey";
        try {
            // if($_SERVER["HOST"]=="cms.maxcheng.tw"){
            //     $this->CMSMemberURL = "https://cms.maxcheng.tw/CMS_Member.php";
            //     $this->CMSLoyaltyURL= "https://cms.maxcheng.tw/CMS_Loyalty.php";
            //     $this->MySQLServerHost = "maxcheng.tw";
            //     $this->ServerHostPort = "3307";
            //     $this->MySQLUser = "root";
            //     $this->MySQLPassword = ",-4,4p-2";
            // }else{
            //     $this->CMSMemberURL = "https://app.sjen.com.tw/cms/CMS_Member.php";
            //     $this->CMSLoyaltyURL= "https://app.sjen.com.tw/cms/CMS_Loyalty.php";
            //     $this->MySQLServerHost = getenv('MYSQL_HOST', true);
            //     $this->ServerHostPort = getenv('MYSQL_PORT', true);
            //     $this->MySQLUser = "root";
            //     $this->MySQLPassword = "55wrtv5u";
            // }

            $this->CMSMemberURL = "https://localhost/CMS_Member.php";
            $this->CMSLoyaltyURL= "https://localhost/CMS_Loyalty.php";
            $this->MySQLServerHost = "localhost";
            $this->ServerHostPort = "3306";
            $this->MySQLUser = "root";
            $this->MySQLPassword = ",-4,4p-2";

            $this->CMS_DB = "CMS_DB";
            $this->LOG_DB = "TransLog";
            $this->TMS_DB = "TMS_DB";
            $this->KMS_DB = "KMS_DB";
            $this->Loyalty_DB = "Loyalty";
            $this->Reference_DB = "Reference";

        } catch (Exception $e) {
            $this->_errorMessage = $e->getMessage();
            $this->__destruct();
            return false;
        }
        return true;
    }

    public function __destruct()
    {
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->_errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->_errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->_token;
    }
}