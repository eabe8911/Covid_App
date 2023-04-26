<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/* Exception class. */
require 'PHPMailer/src/Exception.php';
/* The main PHPMailer class. */
require 'PHPMailer/src/PHPMailer.php';
/* SMTP class, needed if you want to use SMTP. */
require 'PHPMailer/src/SMTP.php';
require_once('Log.php');
require_once('DBConnect.php');
class Emailer
{
    private $_log;
    private $_mail;
    private $_conn;
    private $_mailList;

    function __construct()
    {
        $this->_log = new Log();
        //$this->_mail = new PHPMailer(true);
        try {
            $objDb = new DBConnect;
            $this->_conn = $objDb->connect();
        } catch (PDOException $th) {
            $this->_log->SaveLog('ERROR', 'Emailer_Construct', __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__, $th->getMessage());
            throw new Exception($th->getMessage());
        }
    }

    function SendEmail(array $data = []): bool
    {
        // TODO: 每次寄信都要新建一次，才不會造成傳送多次
        try {
            $this->_mail = new PHPMailer(true);                              // Passing `true` enables exceptions
            //Server settings
            //$this->_mail->setLanguage('fr', '/optional/path/to/language/directory/');
            //$this->_mail->SMTPDebug = 0; // Enable verbose debug output
            //$this->_mail->isSMTP(); // Set mailer to use SMTP
            //$this->_mail->Host = 'mail.maxcheng.tw'; // Specify main and backup SMTP servers
            //$this->_mail->SMTPAuth = true; // Enable SMTP authentication
            //$this->_mail->Username = 'mailer'; // SMTP username
            //$this->_mail->Password = 'G~Sy1O'; // SMTP password
            //$this->_mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            //$this->_mail->Port = 587; // TCP port to connect to
            //$this->_mail->setFrom('mailer@maxcheng.tw', 'Libobio');

            // Init Office 365 email setting
            $this->_mail->isSMTP();
            $this->_mail->Host = 'smtp.office365.com';
            $this->_mail->Port = 587;
            $this->_mail->SMTPSecure = 'tls';
            $this->_mail->SMTPAuth = true;
            $this->_mail->Username = 'Report@libobio.com';
            $this->_mail->Password = 'Report0607';
            $this->_mail->setFrom('Report@libobio.com', '麗寶生醫');
            $this->_mail->CharSet = 'UTF-8';  

            //Recipients
            //$this->_mail->addAddress($data['Email'], $data['Name']); // Add a recipient
            //$this->_mail->addAddress('ellen@example.com');               // Name is optional
            //$this->_mail->addReplyTo('info@example.com', 'Information');
            //$this->_mail->addCC('cc@example.com');
            $this->_mail->addBCC($data['Email']);
            //Attachments
            //$this->_mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$this->_mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            $this->_mail->isHTML(true); // Set email format to HTML
            $this->_mail->Subject = $data['Subject'];
            $message = str_replace("xxxx", $data['id'], $data['Message']);
            // $message = $data['Message'];
            $this->_mail->Body = $message;
            // $this->_mail->AltBody = "您的臨時密碼為$tmpPassword, 請在手機輸入臨時密碼後，修改成您的密碼。";
            $this->_mail->send();
            $this->_log->SaveLog('SEND', 'SendMail', $data['id'], json_encode($data));
            $this->_mail = null;
        } catch (Exception $e) {
            $this->_log->SaveLog('ERROR', 'POST_Error', __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__, $e->getMessage());
            return false;
        }
        return true;

    }

    public function SaveEmailAnswer(array $payload): bool
    {
        try {
            $now = date('Y-m-d H:i:s');
            $sql = "INSERT INTO EmailTransaction (
                MailListID, Answer1, CreatedAt
                ) VALUES (
                :MailListID,:Answer1,:CreatedAt
                )";
            $stmt = $this->_conn->prepare($sql);
            $id = intval($payload['MemberID']);
            $stmt->bindParam(':MailListID',$id);
            $stmt->bindParam(':Answer1', $payload['Answer1']);
            $stmt->bindParam(':CreatedAt', $now);
            if (!$stmt->execute()) {
                throw new Exception("Save Email Answer Error", 1);
            }
        } catch (Exception $th) {
            $this->_log->SaveLog('ERROR', 'SaveEmailAnswer', __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__, $th->getMessage());
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }
    public function GetEmailList(string $WHERE = ''): bool
    {
        try {
            $now = date('Y-m-d H:i:s');
            $sql = "SELECT * FROM MailList_1 " . $WHERE;
            $stmt = $this->_conn->prepare($sql);
            if (!$stmt->execute()) {
                throw new Exception("Get Mail List Error.", 1);
            } else {
                $this->_mailList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $th) {
            $this->_log->SaveLog('ERROR', 'GetMailList', __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__, $th->getMessage());
            throw new Exception($th->getMessage(), $th->getCode());
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function get_mailList()
    {
        return $this->_mailList;
    }
}
?>