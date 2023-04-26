<?php

header('Content-Type: application/json');
// ini_set("display_errors","on");
// error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION)) {
    session_start();
}
 
// Check if the user is already logged in, if yes then redirect him to welcome page
// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

// //    exit;
// }
// else
// {
//     header("location: login.php");
// }

require_once ("php/log.php");

// if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['send_email_ftest']))
// {
//         $value=$_POST['send_email_ftest'];
//         $sampleid = trim(str_replace(", Send E-mail", "", $value));
//         echo $sampleid;
//         send_ftest_email($sampleid);
// }
$value=$_POST['ftestsampleid_post'];
$sampleid = trim(str_replace(", Send E-mail", "", $value));
// echo $sampleid;
$message_back=send_ftest_email($sampleid);

echo json_encode(array(
    'message_back' => $message_back,
    'sampleid_back' => $value,
));

function send_ftest_email($sampleid){

    $message_back="";
    // Connect to local db
    $conn = mysqli_connect("localhost","libo_user","xxx");
    mysqli_select_db($conn, "libodb");

    //use phpmailer to help sending email; 061021 WillieK
    //require '/usr/share/php/vendor/phpmailer/PHPMailerAutoload.php';

    require '/usr/share/php/vendor/phpmailer/phpmailer/src/Exception.php';
    require '/usr/share/php/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '/usr/share/php/vendor/phpmailer/phpmailer/src/SMTP.php';
    

    // Init Office 365 email setting
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->Port       = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth   = true;

    // from user's name and password
    $mail->Username = 'Report@libobio.com';
    $mail->Password = 'Report0607';
    $mail->SetFrom('Report@libobio.com', 'FromEmail');

    // Loop started, fetch the database , grab the emailflag=N, change to =W. 061021 WillieK
    // adding email "to user"
    //$mail->addAddress('willie.kao@libobio.com;angelica.liu@libobio.com;yiting.tsai@libobio.com;teacteam@libobio.com;cally.hsieh@libobio.com;yh.yang@libobio.com', 'ToEmail');
    // if qrptflag is not null or A 062221

    // $sql = "select uuid,userid,passportid,uemail,cname,fname,lname,dob,apdat,tdat, sampleid1 from covid_test where 1=1
    //                                                                           and sampleid1='{$sampleid}'
    //                                                                           and trim(ftest)<>'NA'
    //                                                                           and trim(uemail)<>''
    //                                                                           and  if(frptflag is null,'',frptflag)<>'Y'";
    $sql = "select uuid,userid,passportid,uemail,cname,fname,lname,dob,apdat,tdat, sampleid1 from covid_test where 1=1 
                                                                            and sampleid1='{$sampleid}'
                                                                            and trim(ftest)<>'NA'
                                                                            and trim(uemail)<>''";
                                                                           
                                                                            
    if($stmt = mysqli_prepare($conn, $sql)){                               

        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);
            
            // Check if username exists, if yes then verify password
            if(mysqli_stmt_num_rows($stmt) > 0){ 
                    
                mysqli_stmt_bind_result($stmt,$uuid,$userid,$passportid,$uemail,$cname,$fname,$lname,$dob,$apdat,$tdat,$sampleid1);
                                    
                $row=0 ;

                while(mysqli_stmt_fetch($stmt)){
                    
                    $mail->ClearAddresses();  // each AddAddress add to list
                    $mail->ClearCCs();
                    $mail->ClearBCCs();
                    $mail->Body="";
                    
                if(PHPMailer::validateAddress($uemail)){

                $mail->addAddress($uemail, 'ToEmail');


                    //$mail->SMTPDebug  = 3;
                    //$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";}; //$mail->Debugoutput = 'echo';
                    $mail->IsHTML(true);

                    if(!empty($cname)){
                        //中文版
                        $mail_date=str_replace("-", "", date('Y-m-d', strtotime($tdat)));
                        $mail->Subject = '麗寶醫事檢驗所『核酸檢測電子報告』'.$cname.'/'.$mail_date;
                    
                    
                        $body=$cname."先生/女士 您好, <br>
                        附檔為您本次的COVID-19檢測報告電子檔，請查閱，謝謝您。<br>
                        為了您的個人資料安全，電子報告已加密，密碼輸入規則如下：<br><br>
                        本國人：請輸入身分證號碼（英文字母請以大寫輸入）<br>
                        外籍人士：請輸入居留證號<br>
                        若無以上證件者，請以您的完整護照號碼開啟<br><br>
                        **檢測報告提供時間說明<br>
                        電子報告【一般件次日17:00 / 急件當日17:00(當日10點前採檢完成)】<br>
                        紙本報告(需攜帶身分證件) 【請來電預約領取】<br>
                        郵寄報告需要與您收取200元郵寄費用。<br><br>
                        
                        有任何問題，歡迎隨時與我們聯絡 <br>
                        諮詢專線  0800-885-010 / 0800-081-555<br><br>
                        
                        祝福您 平安健康<br><br>
                        
                        麗寶醫事檢驗所 關心您<br><br>
                        ";
                                        
                        // $body=$body."<br><br><br>麗寶醫事檢驗所關心您<br>";
                        $mail->Body    = $body;

                        function chinese_name($cname){
                            $len = strlen($cname);
                            echo $len;
                            if ($len ==12) {
                                $target= substr($cname, 3, 6);
                                $repeat=str_repeat('O', 2);
                                $final_name= str_replace($target,$repeat, $cname);
                            }else{
                                $target= substr($cname, 3, 3);
                                $repeat=str_repeat('O', 1);
                                $final_name= str_replace($target,$repeat, $cname);
                            }
                            return $final_name;
                        }
                        // $final_name=chinese_name($cname);
                    }
                    else {

                    //英文版
                        $mail->Subject = 'Dear '.$fname.', here is your qPCR report.';
                    
                    
                        $body=$cname."Dear ".$fname.",<BR> <BR>Here is your qPCR report on ".$apdat.".<br>";
                        
                        
                        $body=$body."<br><br>Regards,<br>";
                        $body=$body."Libobio medical examination institution<br>";
                        $mail->Body    = $body;
        
                        function english_name($fname){
                            $pattern = "/[a-zA-Z]+/";
                            preg_match($pattern, $fname, $matches);
                            $final_name=$matches[0];
                            return $final_name;
                        }
                        // $final_name=english_name($fname);
                    }

                    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                    $mail->CharSet = 'UTF-8';  
                    
                    $send_file="";

                    
                    if(!empty($sampleid1)){
                        $valid_date = date('Y-m-d', strtotime($tdat));
                        $send_file = "/var/www/html/belle/test/pdf_reports/{$valid_date}/{$sampleid1}.pdf";
                    }
                    
                    if (file_exists($send_file)){
                        //echo "ya got this";
                        // $file_name="{$sampleid}_{$final_name}_報告覆核完成版.pdf";
                        $mail->AddAttachment($send_file);
                    }else
                    {
                        $send_file="";
                    }
                    

                if (!empty($send_file)){


                    if(!$mail->send()) {
                        $message_back=$message_back. 'Message could not be sent.';
                        $message_back=$message_back. 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {
                        $message_back=$message_back. $userid.' '.$sampleid1.' '.$cname.' on '.$apdat.' Message send ';

                        $sql_comment=$_SESSION["username"].": ".$userid.' '.$sampleid1.' '.$cname.' on '.$apdat.' Message send';
                        write_sql($sql_comment, "BeanCode");

                        //只有發過信才計算
                        $row = $row+1;
                        
                        //update flag to from N to W

                        $sql2 = "update covid_test set frptflag= ? where uuid = ?";
                        if ($stmt2 = mysqli_prepare($conn, $sql2)) {
                            
                            //echo "New record created successfully";
                
                            //$count = $count +1;
                            mysqli_stmt_bind_param($stmt2, "ss", $p1,$p2);
                            $p1="Y";
                            $p2=$uuid;
                            
                            mysqli_stmt_execute($stmt2);

                            $sql_comment=$_SESSION["username"].": update covid_test set frptflag= {$p1} where uuid = {$p2}";
                            write_sql($sql_comment, "BeanCode");
                        }
                    }
                }
            }
        }

                $message_back=$message_back. 'total:'.$row.' send ';
            }
        }

        $message_back=$message_back. 'Done ';

    mysqli_stmt_close($stmt);
    mysqli_close($conn);     

    return $message_back;
    }
}

?>
