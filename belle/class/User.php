<?php
require_once __DIR__ . "/DBConnect.php";
require_once __DIR__ . '/Emailer.php';
/**
 * Summary of User
 */
class User
{
    private $_MemberInfo = array();
    private $_id;
    private $_method;
    private $_RegistrationInfo = array();
    private $_conn;
    private $_errorMessage;
    private $_Message;

    const PASSWORD_NOT_SAME = 1;
    const ACCOUNT_USED = 2;
    const SUCCESSFUL = 3;
    const DBERROR = 0;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        try {
            $objDb = new DBConnect;
            $this->_conn = $objDb->connect();

        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Summary of getMemberInfo
     * @param mixed $MemberID
     * @return bool|string
     */
    public function getUser(string $MemberID = ''): bool
    {
        try {
            $sql = "SELECT * FROM users WHERE id=:id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':id', $MemberID);
            $stmt->execute();
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$response) {
                return false;
            }
            $this->_MemberInfo = $response;
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }

    /**
     * Summary of AddMemberInfo
     * @param array $payload
     * @throws Exception
     * @return bool
     */
    public function AddUserInfo(array $payload): bool
    {
        $MemberID = '';
        try {
            $now = date('Y-m-d H:i:s');
            $sql = "INSERT INTO users(Name, ChineseName, EnglishName, Gender, Birthday, PID, Email, Address,
                PhoneNo, PassportID, HICardID, MTPID, Nationality, TaxIDName, TaxID, CreatedAt, UpdatedAt, SendResult, SendPCR, SendPhone)
                VALUES(:Name, :ChineseName, :EnglishName, :Gender, :Birthday, :PID, :Email, :Address,
                :PhoneNo, :PassportID, :HICardID, :MTPID, :Nationality, :TaxIDName, :TaxID, :CreatedAt, :UpdatedAt, :SendResult, :SendPCR, :SendPhone)";
            $stmt = $this->_conn->prepare($sql);
            $now = date('Y-m-d H:i:s');
            $stmt->bindParam(':Name', $payload['ChineseName']);
            $stmt->bindParam(':ChineseName', $payload['ChineseName']);
            $stmt->bindParam(':EnglishName', $payload['EnglishName']);
            $stmt->bindParam(':Gender', $payload['Gender']);
            $stmt->bindParam(':Birthday', $payload['Birthday']);
            $stmt->bindParam(':PID', $payload['PID']);
            $stmt->bindParam(':Email', $payload['Email']);
            $stmt->bindParam(':Address', $payload['Address']);
            $stmt->bindParam(':PhoneNo', $payload['PhoneNo']);
            $stmt->bindParam(':PassportID', $payload['PassportID']);
            $stmt->bindParam(':HICardID', $payload['HICardID']);
            $stmt->bindParam(':MTPID', $payload['MTPID']);
            $stmt->bindParam(':Nationality', $payload['Nationality']);
            $stmt->bindParam(':TaxIDName', $payload['TaxIDName']);
            $stmt->bindParam(':TaxID', $payload['TaxID']);
            $stmt->bindParam(':CreatedAt', $now);
            $stmt->bindParam(':UpdatedAt', $now);
            $stmt->bindParam(':SendResult', $payload['SendResult']);
            $stmt->bindParam(':SendPCR', $payload['SendPCR']);
            $stmt->bindParam(':SendPhone', $payload['SendPhone']);
            $stmt->execute();
            $MemberID = $this->_conn->lastInsertId();
            $this->_MemberInfo = $payload;
            $this->_MemberInfo['MemberID'] = $MemberID;
            // TODO: Update Registration MemberID
            $sql = "UPDATE users SET MemberID=:MemberID WHERE id=:id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':MemberID', $MemberID);
            $stmt->bindParam(':id', $payload['RegistrationID']);
            $stmt->execute();
        } catch (PDOException $th) {
            throw new Exception($th->getMessage());
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }

    public function UpdateMemberInfo(array $payload): bool
    {
        try {
            // echo var_dump($payload);
            // die();
            $now = date('Y-m-d H:i:s');
            $sql = "UPDATE users SET
                Name=:Name, ChineseName=:ChineseName, EnglishName=:EnglishName, Gender=:Gender, Birthday=:Birthday,
                PID=:PID, Email=:Email, Address=:Address, PhoneNo=:PhoneNo, PassportID=:PassportID, HICardID=:HICardID,
                MTPID=:MTPID, Nationality=:Nationality, TaxIDName=:TaxIDName, TaxID=:TaxID, UpdatedAt=:UpdatedAt,
                SendResult=:SendResult, SendPCR=:SendPCR, SendPhone=:SendPhone
                WHERE id=:id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':Name', $payload['Name']);
            $stmt->bindParam(':ChineseName', $payload['ChineseName']);
            $stmt->bindParam(':EnglishName', $payload['EnglishName']);
            $stmt->bindParam(':Gender', $payload['Gender']);
            $stmt->bindParam(':Birthday', $payload['Birthday']);
            $stmt->bindParam(':PID', $payload['PID']);
            $stmt->bindParam(':Email', $payload['Email']);
            $stmt->bindParam(':Address', $payload['Address']);
            $stmt->bindParam(':PhoneNo', $payload['PhoneNo']);
            $stmt->bindParam(':PassportID', $payload['PassportID']);
            $stmt->bindParam(':HICardID', $payload['HICardID']);
            $stmt->bindParam(':MTPID', $payload['MTPID']);
            $stmt->bindParam(':Nationality', $payload['Nationality']);
            $stmt->bindParam(':TaxIDName', $payload['TaxIDName']);
            $stmt->bindParam(':TaxID', $payload['TaxID']);
            $stmt->bindParam(':UpdatedAt', $now);
            $stmt->bindParam(':id', $payload['MemberID']);
            $stmt->bindParam(':SendResult', $payload['SendResult']);
            $stmt->bindParam(':SendPCR', $payload['SendPCR']);
            $stmt->bindParam(':SendPhone', $payload['SendPhone']);
            $stmt->execute();
        } catch (PDOException $th) {
            throw new Exception($th->getMessage());
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }


    /**
     * Summary of Login
     * @param mixed $payload
     * @return bool|string
     */
    public function Login(string $Account = '', string $Password = ''): bool
    {
        try {
            $sql = "SELECT * FROM users WHERE username=:Account AND password=:Password";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':Account', $Account);
            $stmt->bindParam(':Password', $Password);
            $stmt->execute();
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($response) {
                $this->_RegistrationInfo = $response;
            } else {
                throw new Exception("User not found.", 1);
            }
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }

    /**
     * Summary of Register
     * @param array $payload
     * @return bool
     */
    public function Registration(array $payload = []): bool
    {
        try {
            $Account = trim($payload['Account']);
            $Password1 = trim($payload['Password1']);
            $Password2 = trim($payload['Password2']);
            $DisplayName = $payload['DisplayName'];
            if ($Password1 <> $Password2) {
                throw new Exception("輸入兩次的密碼不相等", 1);
            }

            // Check if email is in the correct format.
            if (!filter_var($Account, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("帳號不符合電子郵件格式", 2);
            }

            if ($this->checkAccount($Account)) {
                throw new Exception("帳號已經存在", 3);
            }

            $sql = "INSERT INTO users(DisplayName, Account, Password, Email, Activate, CreatedAt)
                VALUES (:DisplayName, :Account, :Password, :Email, :Activate, :CreatedAt)";
            $stmt = $this->_conn->prepare($sql);
            $Activate = 1;
            $created_at = date('Y-m-d H:i:s');
            $stmt->bindParam(':DisplayName', $DisplayName);
            $stmt->bindParam(':Account', $Account);
            $stmt->bindParam(':Password', $Password1);
            $stmt->bindParam(':Email', $Account);
            $stmt->bindParam(':Activate', $Activate);
            $stmt->bindParam(':CreatedAt', $created_at);
            $stmt->execute();
            // if($this->sendAuthEmail()){

            // }
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }

    private function sendAuthEmail()
    {
        try {
            // Send user activation e-mail

            $message = "Your activation code is: " . $code . ".";
            $to = $email;
            $subject = "Your activation code for Membership.";
            $from = 'test@membership.com'; // This should be changed to an email that you would like to send activation e-mail from.
            $body = 'Your activation code is: ' . $code . '<br> To activate your account please click on the following link'
                . ' <a href="YOURWEBSITEURL/verify.php?id=' . $email . '&code=' . $code . '">verify.php?id=' . $email . '&code=' . $code . '</a>.'; // Input the URL of your website.
            $headers = "From: " . $from . "\r\n";
            $headers .= "Reply-To: " . $from . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to, $subject, $body, $headers);

            // If registration is successful return user to Registration.php and promt user success pop-up.
            header('Location: Registration.php');
            $_SESSION['SuccessMessage'] = 'User has been created!';
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }

    /**
     * Summary of checkAccount
     * @param string $account
     * @return bool
     */
    private function checkAccount(string $account = ''): bool
    {
        try {
            $sql = "SELECT 1 FROM users WHERE Account=:Account";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':Account', $account);
            $stmt->execute();
            return ($stmt->rowCount() > 0); // Check if query returns any rows. If so, return true. Otherwise, return false. 
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Summary of do
     * @param mixed $method
     * @param mixed $id
     * @param mixed $payload
     * @throws Exception 
     * @return bool|string
     */
    public function do ($method = '', $id = '', $payload = '')
    {
        try {
            $this->_method = $method;
            $this->_MemberInfo = json_decode($payload, true);
            $this->_id = $id;
            // $this->_log->SaveLog('OK', 'Member_do', __FUNCTION__." in ".__FILE__." at ".__LINE__, $payload);
            switch ($method) {
                case "GET":
                    $result = $this->Get();
                    break;
                case "POST":
                    $result = $this->Post();
                    break;
                case "PATCH":
                    $result = $this->Put();
                    break;
                case "PUT":
                    $result = $this->Put();
                    break;
                case "DELETE":
                    $result = $this->Delete();
                    break;
                default:
                    throw new Exception("Error method.", 1);
            }
            return $result;
        } catch (PDOException | Exception $th) {
            return json_encode(['status' => 0, 'message' => 'Error function call.']);
        }
    }

    /**
     * Summary of Get
     * @return bool|string
     */
    private function Get()
    {
        try {
            $sql = "SELECT * FROM users";
            if (isset($this->_id) && is_numeric($this->_id)) {
                $sql .= " WHERE id = :id";
                $stmt = $this->_conn->prepare($sql);
                $stmt->bindParam(':id', $this->_id);
                $stmt->execute();
                $response = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $this->_conn->prepare($sql);
                $stmt->execute();
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            // $this->_log->SaveLog('OK', 'GET', __FUNCTION__." in ".__FILE__." at ".__LINE__, $sql);
            return json_encode($response);
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Summary of Post
     * @return bool|string
     */
    private function Post()
    {
        try {
            $sql = "INSERT INTO users(id, LastName, FirstName, Email, created_at)
                VALUES(null, :LatName, :FirstName, :Email, :created_at)";
            $stmt = $this->_conn->prepare($sql);
            $created_at = date('Y-m-d H:i:s');
            $stmt->bindParam(':LastName', $this->_MemberInfo['LastName']);
            $stmt->bindParam(':FirstName', $this->_MemberInfo['FirstName']);
            $stmt->bindParam(':Email', $this->_MemberInfo['Email']);
            $stmt->bindParam(':created_at', $created_at);

            if ($stmt->execute()) {
                $response = [
                    'status' => 1,
                    'message' => 'Record created successfully.'
                ];
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Failed to create record.'
                ];
            }
            return json_encode($response);
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Summary of Put
     * @return bool|string
     */
    private function Put()
    {
        try {
            $sql = "UPDATE users SET
                LastName= :LastName, FirstName =:FirstName, Email =:Email, updated_at =:updated_at
                WHERE id = :id";
            $stmt = $this->_conn->prepare($sql);
            $updated_at = date('Y-m-d H:i:s');
            $stmt->bindParam(':id', $this->_id);
            $stmt->bindParam(':LastName', $this->_MemberInfo["LastName"]);
            $stmt->bindParam(':FirstName', $this->_MemberInfo["FirstName"]);
            $stmt->bindParam(':Email', $this->_MemberInfo['Email']);
            $stmt->bindParam(':updated_at', $updated_at);

            if ($stmt->execute()) {
                $response = [
                    'id' => $this->_id
                ];
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Failed to update record.'
                ];
            }
            return json_encode($response);
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }
    /**
     * Summary of Delete
     * @return string
     */
    private function Delete()
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':id', $this->_id);

            if ($stmt->execute()) {
                $response = [
                    'status' => 1,
                    'message' => 'Record deleted successfully.'
                ];
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Failed to delete record.'
                ];
            }
            return json_encode($response);
        } catch (PDOException | Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    /**
     * @return mixed
     */
    public function get_MemberInfo()
    {
        return $this->_MemberInfo;
    }

    /**
     * @return mixed
     */
    public function get_RegistrationInfo()
    {
        return $this->_RegistrationInfo;
    }
}
?>