<?php
require_once __DIR__. "\DBConnect.php";

class Receipt
{
    private $_conn;
    private $_errorMessage;
    private $_receipt;

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

    function __destruct(){
        // TODO: nothing to do.
    }

    public function getReceiptLastID($apdat='', $sampleid2=''){
        try {
            $sql = "SELECT * FROM covid_trans WHERE apdat = :apdat ORDER BY receiptid DESC LIMIT 0,1";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':apdat',  $apdat);
            $stmt->execute();
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            if($response){
                $serial_no = substr($response['receiptid'], 8, 3);
                $Count = (int)$serial_no + 1;
                $Count = str_pad($Count, 3, '0', STR_PAD_LEFT); //自動補0
                $receiptid = substr($apdat,0,4).substr($apdat,5,2).substr($apdat,8,2). $Count ;
              
            }else{
    
                $receiptid = date('Ymd') . '001';
            }
            return $receiptid;
        } catch (PDOException | Exception $th) {
            throw new Exception("Cannot get receipt Last ID.", 1);
        }
    }



    public function AddReceipt($payload='')
    {
        try {
            $sql = "UPDATE covid_trans SET receiptid=:receiptid WHERE sampleid2 = :sampleid2";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':receiptid',   $payload['receiptid']);
            $stmt->bindParam(':sampleid2', $payload['sampleid2']);
            $stmt->execute();
            return true;
        } catch (PDOException | Exception $th) {
            throw new Exception("Cannot add receipt.", 1);
        }
    }

    //TODO: check receiptid by uuid
    public function checkReceipt ($uuid){
        try {
            $sql = "SELECT * FROM covid_trans WHERE uuid = :uuid ";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':uuid',  $uuid);
            $stmt->execute();
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            if($response){
                // TODO: check receiptid is null
                if(empty($response['receiptid'])){
                    return false;
                }else{
                    return true;
                }
            }

        } catch (PDOException | Exception $th) {
            throw new Exception("Cannot check receipt.", 1);
        }

    }
    
	/**
	 * @return mixed
	 */
	public function get_errorMessage() {
		return $this->_errorMessage;
	}

	/**
	 * @return mixed
	 */
	public function get_receipt() {
		return $this->_receipt;
	}
}
?>