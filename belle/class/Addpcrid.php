<?php
require_once __DIR__ . "\DBConnect.php";
// require("DBConnect.php");

class Addpcrid
{
    private $_conn;
    private $_errorMessage;
    private $_pcrid;

    public function __construct()
    {
        try {
            $objDb = new DBConnect();
            $this->_conn = $objDb->connect();
        } catch (PDOException $e) {
            $this->_errorMessage = $e->getMessage();
            $this->__destruct();
        }
    }

    function __destruct()
    {
        // TODO: nothing to do.
    }

    public function getPcrLastID($apdat = '')
    {
        
        try {

            $sql = "SELECT * FROM covid_trans WHERE apdat = :apdat ORDER BY sampleid2 DESC LIMIT 0,1";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':apdat', $apdat);
            $stmt->execute();
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($response) {
                $apdat_str = substr($response['apdat'], 2, 8);
                $apdat_no = str_replace('-', '', $apdat_str);
                if (isset($response['sampleid2'])) {
                    //TODO find sampleid2 last three yards
                    $sampleid2_no = substr($response['sampleid2'], 8, 3);
                    $Count = (int) $sampleid2_no + 1;
                    $Count = str_pad($Count, 3, '0', STR_PAD_LEFT); //自動補0
                    
                    $sampleid2 = 'Q' . $apdat_no . $Count;
                } else {
                    $sampleid2 = 'Q' . $apdat_no . '001';
                }
            }
            return $sampleid2;
        } catch (PDOException | Exception $th) {
            throw new Exception("Cannot get pcr Last ID.", 1);
        }
    }

    //TODO: add sampleid2 by twrpturgency = 4.下午(急件特別診)
    public function getAfternoonPcrLastID($apdat = '', $pnflag = "Y"){
        try {
            $sql = "SELECT * FROM covid_trans WHERE apdat = :apdat AND pnflag = :pnflag ORDER BY sampleid2 DESC LIMIT 0,1";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':apdat', $apdat);
            $stmt->bindParam(':pnflag', $pnflag);
            $stmt->execute();
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            if($response){
                $apdat_str = substr($response['apdat'], 2, 8);
                $apdat_no = str_replace('-', '', $apdat_str);
                if (isset($response['sampleid2'])) {
                    //TODO find sampleid2 last three yards
                    $sampleid2_no = substr($response['sampleid2'], 8, 3);
                    $Count = (int) $sampleid2_no + 1;
                    $Count = str_pad($Count, 3, '0', STR_PAD_LEFT); //自動補0
                    
                    $sampleid2 = 'Q' . $apdat_no . $Count;
                } else {
                    $sampleid2 = 'Q' . $apdat_no . '901';
                }
            }
            return $sampleid2;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    // public function AddPcrid($payload1 = '')
    // {
    //     try {
    //         $sql = "UPDATE covid_trans SET sampleid2=:sampleid2 WHERE apdat = :apdat";
    //         $stmt = $this->_conn->prepare($sql);
    //         $stmt->bindParam(':sampleid2', $payload1['sampleid2']);
    //         $stmt->bindParam(':apdat', $payload1['apdat']);
    //         $stmt->execute();
    //         return true;
    //     } catch (PDOException | Exception $th) {
    //         throw new Exception("Cannot add pcrid.", 1);
    //     }
    // }


    //TODO: check sampleid2 by uuid
    public function checkPcrid($uuid = '')
    {
        try {
            $sql1 = "SELECT * FROM covid_trans WHERE uuid = :uuid ";
            $stmt1 = $this->_conn->prepare($sql1);
            $stmt1->bindParam(':uuid', $uuid);
            $stmt1->execute();
            $response1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            if ($response1) {
                //TODO: check sampleid2 is null
                if (empty($response1['sampleid2'])) {
                    return false;
                } else {
                    return true;
                }

            }
        } catch (PDOException | Exception $th) {
            throw new Exception("Cannot check pcrid.", 1);
        }
    }


    /**
     * @return mixed
     */
    public function get_errorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @return mixed
     */
    public function get_receipt()
    {
        return $this->_receipt;
    }
}
?>