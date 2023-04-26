<?php
date_default_timezone_set('Asia/Taipei');
require_once __DIR__ . "/DBConnect.php";

// make a class for appointment
class Appointment
{
    private $_conn;
    private $_errorMessage;
    private $_Message;
    private $_AppointmentInfo;

    // constructor for appointment class
    public function __construct()
    {
        // make a object for database
        try {
            $objDb = new DBConnect;
            $this->_conn = $objDb->connect();

        } catch (PDOException | Exception $th) {
            // throw new Exception($th->getMessage(), 1);

        }
    }

    // destructor for appointment class
    public function __destruct()
    {
        // make a object for database
        $this->_conn = null;
    }

    public function searchAppointment($id)
    {
        try {
            $sql = "SELECT * FROM covid_trans WHERE sampleid2 = :id or uuid = :id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$response) {
                echo '<script language="javascript">alert("查不到此筆資料");</script>';
                echo '<script language="javascript">window.location.replace("checkinnew.php");</script>';
            }
            $this->_AppointmentInfo = $response;
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }
    // make a function for checkin appointment
    public function CheckIn($id): bool
    {
        try {
            $now = date("Y-m-d H:i:s");
            $sql = "UPDATE covid_trans SET tdat = :tdat WHERE uuid = :id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':tdat', $now);
            $stmt->bindValue(':id', intval($id));
            $stmt->execute();
        } catch (PDOException | Exception $th) {
            throw new Exception("報到失敗，請稍後再試 " . $th->getMessage(), $th->getCode());
        }
        return true;
    }
    // make a function for appointment
    public function makeAppointment(array $data)
    {

    }
    // make a function for update appointment
    public function updateAppointment(array $data)
    {

    }
    // make a function for delete appointment
    public function deleteAppointment(string $id): bool
    {
        try {
            // TODO: make a query for delete appointment
            $sql = "DELETE FROM covid_trans WHERE uuid = :id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

        } catch (Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }
    // make a function for cancel appointment
    public function cancelAppointment(string $id): bool
    {
        try {
            //TODO : make a query for cancel appointment
            $sql = "UPDATE FROM covid_trans SET cancel = 1 WHERE uuid = :id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function get_AppointmentInfo()
    {
        return $this->_AppointmentInfo;
    }
}
?>