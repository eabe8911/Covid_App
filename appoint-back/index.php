<?php
    /**
    * 功能名稱 : 帳號密碼驗證
    * 建立日期 : 2020/05/13 15:44:48
    * 建立人員 : Max Cheng
    * 修改日期 :
    * 修改人員 : Max Cheng
    */
    use appoint\Ads_Param;
    session_start();
    // if($_SERVER["HOST"]=="app.maxcheng.tw"){
    //     $Logo = "";
    //     $LogoName = "後台管理系統";
    // }else{
    $Logo = "<img src='./images/Libobio-Logo@2x.png' alt='LiboBio Logo' height='70'>";
    $LogoName = "";
    // }
    try {
        require_once("./Ads_Param.php");
        $param = new Ads_Param();
        //檢查是否第二次進入
        // echo("第一次進入");
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST["FormName"] == "Login") {
            // echo("第二次進入");
            // echo($param->MySQLServerHost."<br>");
            // echo($param->LIBOBIO_DB."<br>");
            // echo($param->ServerHostPort."<br>");
            // echo($param->MySQLUser."<br>");
            // echo($param->MySQLPassword."<br>");
            // echo("OK_1");
            $conn = new PDO("mysql:host=$param->MySQLServerHost;dbname=$param->LIBOBIO_DB;port=$param->ServerHostPort",
                $param->MySQLUser, $param->MySQLPassword);
            // $conn = new PDO("mysql:host=192.168.2.115;dbname=libodb;port=3306",
            //     "root", "password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo("OK_2");
            $Username = filter_input(INPUT_POST,"LoginName");
            $Password = filter_input(INPUT_POST, "Password");
            // echo "User Name = ".$Username." Password = ".$Password;die();
            // check Hospital and username
            $sql="select * from users where password=:password and username=:username";
            $sth = $conn->prepare($sql);
            $sth->bindValue(":password", $Password);
            $sth->bindValue(":username", $Username);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            $Count = count($result);
            //echo($Count);die();
            //如果有找到資料，表示有找到帳號密碼
            if ($Count > 0) {
                $image = $result[0]['Picture'];
                $now=date("Y-m-d H:i:s");
                $TerminalID = $result[0]['TerminalID'];
                $UserName = $result[0]['User_Name'];
                $Password = $result[0]['Password'];
                // setcookie('User_Name', $Username, time() + (3600)); // 3600 = 1 Hour
                //登入成功，先記錄登入資料

                // $sql = "INSERT INTO TerminalLogin
                //     (UserName,Trans_DT)
                //     VALUES 
                //     (:UserName, :Trans_DT)";
                // $sth = $conn->prepare($sql);
                // $sth->bindValue(":UserName", $Username);
                // $sth->bindValue(":Trans_DT", $now);
                // $sth->execute();
                //echo "<script type='text/javascript'>alert('Login success');</script>";
                //echo($MemberRegister_URL);die();
                $_SESSION['username'] = $Username;
                $_SESSION['loggedin'] = true;
                header("Location: home.php");
            }else{
                //沒有登入成功
                $_SESSION['username'] = "";
                $_SESSION['loggedin'] = false;
                echo "<script type='text/javascript'>alert('User name or password not correct.');</script>";
            }
        }else{
            $_SESSION['username'] = "";
            $_SESSION['loggedin'] = false;
        }
    } catch (PDOException | Exception $e) {
        echo($e->getMessage());die();
    }

    require 'libs/Smarty.class.php';

    $smarty = new Smarty;
    
    /**LOGIN**/
    $smarty->assign("Logo", $Logo);
    $smarty->assign("LogoName", $LogoName);
    $smarty->assign("Form", "index.php", true);
    $smarty->assign("Username", "LoginName", true);
    $smarty->assign("Username_Value", "", true);
    $smarty->assign("Password", "Password", true);
    $smarty->assign("Password_Value", "", true);
    $smarty->assign("Rememberme", "Checked", true);
    $smarty->assign("Access", "submit", true);
    $smarty->assign("Signup", "SignUp()", true);
    $smarty->assign("Notmyhospital", "ChangeHospital()", true);
    // $smarty->assign("Hospitalname", $hosp_name, true);
    $smarty->assign("Hiddenfield", "<input type='hidden' id='FormName' name='FormName' value='Login'>", true);

    $smarty->display('index.tpl');