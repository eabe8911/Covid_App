<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("DBConnect.php");
// require_once("Log.php");
class Positive{
    private $_checkDate;
    private $_conn;
    // private $_log;
    function __construct(){
        // TODO: read check date
        // TODO: need a setting UI
        $this->_checkDate = 5;
        $dbobj = new DBConnect();
        $this->_conn = $dbobj ->connect();
        // $this->_log = new Log();
    }

    public function QueryUser($covid_trans, $QueryDate){
        // TODO: find userid, passportid, mtpid, pcrtest
        try {
            $sql = "SELECT * FROM covid_trans WHERE
            (userid=:userid OR
            passportid=:passportid OR
            mtpid=:mtpid )AND
            pcrtest='positive' AND
            uuid<>:uuid
            ";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':userid',     $covid_trans['userid']);
            $stmt->bindParam(':passportid', $covid_trans['passportid']);
            $stmt->bindParam(':mtpid',      $covid_trans['mtpid']);
            $stmt->bindParam(':uuid',       $covid_trans['uuid']);
           $stmt->execute();
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($response){
                // TODO: check query date < check date send error
                foreach ($response as $item) {

                    $date2=date_create($item['apdat']);
                    $date1=date_create($QueryDate);
                    $diff=date_diff($date1,$date2);
                    if($this->_checkDate - $diff->format('%a') > 0){
                        return true;
                    }
                }
            }
            return false;
        } catch (PDOException | Exception $th) {
            // $this->_log->SaveLog('ERROR', 'positive', __FUNCTION__." in ".__FILE__." at ".__LINE__, $th->getMessage());
        }

    }
    public function QueryAddUser($covid_trans, $QueryDate){
      // TODO: find userid, passportid, mtpid, pcrtest
      try {
          $sql = "SELECT * FROM covid_trans WHERE
         ( userid=:userid OR
          passportid=:passportid OR
          mtpid=:mtpid )AND
          pcrtest='positive' 
          ";
          $stmt = $this->_conn->prepare($sql);
          $stmt->bindParam(':userid',     $covid_trans['userid']);
          $stmt->bindParam(':passportid', $covid_trans['passportid']);
          $stmt->bindParam(':mtpid',      $covid_trans['mtpid']);
          $stmt->execute();
          $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if($response){
              // TODO: check query date < check date send error
              foreach ($response as $item) {

                  $date2=date_create($item['apdat']);
                  $date1=date_create($QueryDate);
                  $diff=date_diff($date1,$date2);
                  if($this->_checkDate - $diff->format('%a') > 0){
                      return true;
                  }
              }
          }
          return false;
      } catch (PDOException | Exception $th) {
          // $this->_log->SaveLog('ERROR', 'positive', __FUNCTION__." in ".__FILE__." at ".__LINE__, $th->getMessage());
      }

  }

    public function QueryAll($QueryDate){
        $result = [];
   
        try {
            $sql = "SELECT * FROM covid_trans
                WHERE pcrtest='positive' AND
                DATEDIFF(:QueryDate, rdat) < 7 AND
                rdat <= :QueryDate 
                ORDER BY  rdat;
            ";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindParam(':QueryDate', $QueryDate);
           $stmt->execute();
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $response;
        } catch (PDOException | Exception $th) {
            // $this->_log->SaveLog('ERROR', 'positive', __FUNCTION__." in ".__FILE__." at ".__LINE__, $th->getMessage());
        }
    }

    public function SendMail($UserInfo){
        // Import PHPMailer classes into the global namespace
        // These must be at the top of your script, not inside a function
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        //Load composer's autoloader
        //require '/vendor/autoload.php';
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        $name = $UserInfo['cname'].' '.$UserInfo['fname'];
        try {
            //Server settings
            //$mail->setLanguage('fr', '/optional/path/to/language/directory/');
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.maxcheng.tw';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'mailer';                           // SMTP username
            $mail->Password = 'G~Sy1O';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            //Recipients
            $mail->setFrom('mailer@maxcheng.tw', 'Libobio System');
            // $mail->addAddress($UserInfo['uemail'], $name); // Add a recipient
            $mail->addAddress('max.cheng@libobio.com', $name); // Add a recipient

            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('mailer@maxcheng.tw');
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '我們收到您的檢測預約資訊';
            $message = "
            <html>
            <head>
              <meta charset=utf-8>
              <title>臨時密碼</title>
            </head>
            <body>
              <p><?php echo($name)?>，您好</p>
              <table>
                <tr>
                  <td>
                    <h3>已收到您的檢測預約訊息，依中央疫情指揮中心公告陽性確診者需進行隔離。</h3>
                  </td>
                </tr>
                <tr>
                  <td>
                    <h3>因您報名檢測日之前六日內，於法定傳染病系統中，通報檢驗結果為陽性，恕本所無法受理此次預約。</h3>
                  </td>
                </tr>
                <tr>
                  <td>
                    <h3>請您重新利用系統預約六日之後的採檢日期。</h3>
                  </td>
                </tr>
                <tr>
                  <td><h4>有任何問題，歡迎隨時與我們聯絡</h4></td>
                </tr>
                <tr>
                  <td><h4>諮詢專線 02-2503-1392</h4></td>
                </tr>
                <tr>
                  <td><h4>麗寶醫事檢驗所關心您</h4></td>
                </tr>
                <tr>
                  <td>此封信件由系統產生，請勿回覆。</td>
                </tr>
              </table>
            </body>
            </html>
            ";
            $mail->Body    = $message;
            // $mail->AltBody = "您的臨時密碼為$tmpPassword, 請在手機輸入臨時密碼後，修改成您的密碼。";
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            $this->_errorMessage = $e->getMessage();
            return false;
        }
        return true;
    }

}
?>