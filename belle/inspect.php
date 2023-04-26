<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
ini_set("display_errors","on");
error_reporting(E_ALL);

session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    //header("location: welcome.php");
    //header("location: menu.html");
//    exit;
}
else
{
    header("location: login.php");
}
 
require_once ("php/log.php");

// Connect to local db

$conn = mysqli_connect("localhost","libo_user","xxx");
mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
$stmt=$uuid=$apdat= $userid=$passportid=$cname=$lname=$fname=$mobile=$uemail=$sex=$dob=$ftest=$pcrtest=$vuser1=$mtpid="";
if ($uuid==""){ 
$sampleid1 = $sampleid2 = " ";
$sampleid1_err = $sampleid2_err = $sampleid_err = "";
$ftest_err=$pcrtest_err=$testtype="";
}

// Processing save ftest pcrtest result
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"]))
{
    //echo $_POST["ftest"];
    //echo $_POST["pcrtest"]; 
    //echo $_POST["vuser1"];
    //echo $_POST["uuid"];
    if (empty($_POST["ftest"])&&$_POST['testtype']!=2){
        echo "<h4>";
        echo "請選擇快篩結果!";
        echo "</h4>";
        $ftest_err="E";
    }
    if (empty($_POST["pcrtest"])&&$_POST['testtype']!=1){
        echo "<h4>";
        echo "請選擇PCR結果!";
        echo "</h4>";
        $pcrtest_err="E";
    }
    if (empty(trim($_POST["vuser1"]))||($ftest_err=="E"||$pcrtest_err=="E")){
        echo "<h4>";
        echo "檢測者ID 不能為空!或至少有一未選擇判定結果";
        echo "</h4>";
        $sampleid1 = $_POST["sampleid1"];
        $sampleid2 = $_POST["sampleid2"];
    }
        else {
        //060921 add record the report date , willieK
        $sql2 = "update covid_trans set ftest= ?,pcrtest=?,vuser1=?,rdat = (select NOW() from dual) where uuid=?";
        if ($stmt2 = mysqli_prepare($conn, $sql2)) {
            //echo "New record created successfully";

            //$count = $count +1;
            mysqli_stmt_bind_param($stmt2, "sssi", $p1,$p2,$p3,$p4);
        
        // Set parameters
        if ($_POST['testtype']!=2){
            $p1 = $_POST["ftest"];
        }
        else
        {$p1 = "";}

        //$p1 = $_POST["ftest"];
        
        if ($_POST['testtype']!=1){
            $p2 = $_POST["pcrtest"];
        }
        else
        {$p2 = "";}

        //$p2 = $_POST["pcrtest"];
        $p3 = $_POST["vuser1"];
        $p4 = $_POST["uuid"];

        $sql_comment=$_SESSION["username"]."_version1: update covid_trans set ftest= {$p1},pcrtest={$p2},vuser1={$p3},rdat = (select NOW() from dual) where uuid={$p4}";
        write_sql($sql_comment);

        }
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt2)){
            //echo "<h1 style="background-color:hsla(9, 100%, 64%, 0.5);">";
            echo "<h4>";
            echo "判讀結果存檔成功!" ;
            echo "</h4>";
            $save_result ="判讀結果存檔成功!" ;
            write_sql($save_result);
        } else {
            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
            $save_result ="Error: " . $sql2 . "<br>" . mysqli_error($conn);
            write_sql($save_result);
        }    
          
    }


    $vuser1 =  $_POST["vuser1"];
    }


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])){
 
    $vuser1 =  $_POST["vuser1"];
    // Check if sampleid1 is empty
    if(empty(trim($_POST["sampleid1"])) && empty(trim($_POST["sampleid2"]))){
        
            
           $sampleid1_err = "Please scan sampleid1.";
           $sampleid2_err = "Please scan sampleid2.";
        
    }
    
    
    $sampleid1 = trim($_POST["sampleid1"]); 
    $sampleid2 = trim($_POST["sampleid2"]); 

    
    //echo $sampleid1;
    //echo $sampleid2;
   //060921 add limit 1 to ensure always select the last row of uuid 
    if(!empty($sampleid1)){
        // Prepare a select statement
        $sql = "SELECT uuid,apdat,userid, passportid,cname,lname,fname,mobile,uemail,sex,dob,ftest,pcrtest,vuser1,sampleid1,sampleid2,testtype,mtpid
                                                                                                            FROM covid_trans WHERE 1=1 
                                                                                                            and sampleid1 = ? 
                                                                                                            order by uuid limit 1";
        
        //echo $sampleid1."<br>";
        //echo $sampleid2."<br>";                                                                                                
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            //mysqli_stmt_bind_param($stmt, "ss", $param1,$param2);
            mysqli_stmt_bind_param($stmt, "s", $param1);
            
            // Set parameters
            
            //echo $sampleid1;

            $param1 = $sampleid1;
            //$param2 = $sampleid2;
        }
    } elseif(empty($sampleid1) && !empty($sampleid2)) {
        
        $sql = "SELECT uuid,apdat,userid, passportid,cname,lname,fname,mobile,uemail,sex,dob,ftest,pcrtest,vuser1,sampleid1,sampleid2,testtype,mtpid 
                                                                                                             FROM covid_trans WHERE 1=1 
                                                                                                             and sampleid2 = ? 
                                                                                                             order by uuid desc limit 1";

    
        if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        //mysqli_stmt_bind_param($stmt, "ss", $param1,$param2);
        mysqli_stmt_bind_param($stmt, "s", $param1);

        // Set parameters


        $param1 = $sampleid2;
        //$param2 = $sampleid2;

        }
    }

            // Attempt to execute the prepared statement
        if (empty($sampleid1_err)&&empty($sampleid2_err)){
            if(mysqli_stmt_execute($stmt)){
                // Store result
                //echo "run";
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt,$uuid,$apdat, $userid, $passportid,$cname,$lname,$fname,$mobile,$uemail,$sex,$dob,$ftest,$pcrtest,$vuser1,$sampleid1,$sampleid2,$testtype,$mtpid);
                    if(mysqli_stmt_fetch($stmt)){
                        //if(password_verify($password, $hashed_password)){
                            //if($password==$hashed_password){
                            // Password is correct, so start a new session
                            //session_start();
                            
                            // Store data in session variables
                            //$_SESSION["loggedin"] = true;
                            //$_SESSION["id"] = $id;"
                            //$_SESSION["username"] = $username;

                            //echo "<br>";
                            //echo "身份證:"."$userid"."<br>";
                            //echo "護照號碼:"."$passportid"."<br>";
                            //echo "中文名:"."$cname"." ";
                            //echo "FirstName:"."$fname".",";
                            //echo "Last Name:"."$lname"."<br>";
                            //echo "生日:"."$dob"."<br>";
                            //echo "性別:"."$sex"."<br>";
                            //echo "手機:"."$mobile"."<br>";
                            //echo "Email:"."$uemail"."<br>";



                                                           
                            // Redirect user to welcome page
                            //header("location: menu.html");
                            //$sampleid1=" ";
                            //$sampleid2=" ";
                            //echo $uuid;
                        } else{
                            // Password is not valid, display a generic error message
                            $sampleid_err = "SQL error ,pls ask IT!."; 
                            //xxecho "die";
                        }
                        //$sampleid_err = "More than 1 sampleid matched!";
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $sampleid_err = "sampleid rows not eq 1.";
                }
            

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } 
    
    // Close connection
    mysqli_close($conn);

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>判讀結果</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>判讀檢驗結果</h2>
        <h3>
        <p>請刷快篩或qPCR條碼</p>
        </h3>
        <?php 
        if(!empty($sampleid1_err)&& !empty($sampleid2_err)){
            echo '<div class="alert alert-danger">' . $sampleid1_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style='display:inline'>
            <div class="form-group">
            
                <label>快篩ID</label>
                <input type="text":focus name="sampleid1" class="form-control <?php echo (!empty($sampleid1_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid1; ?>" tabindex=1>
                <span class="invalid-feedback"><?php echo $sampleid1_err; ?></span>
               
                <label>PCR ID</label>
                <input type="text":focus name="sampleid2" class="form-control <?php echo (!empty($sampleid2_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid2; ?>" tabindex=2>
                <span class="invalid-feedback"><?php echo $sampleid2_err; ?></span>

                
            
                <br>
                <br>

                <input type="submit" name="search" class="btn btn-primary" value="search" tabindex=3>
                <input type="submit"  name="clear" class="btn btn-primary" value="clear" tabindex=4>

                <br>
                <br>
                <br>

                
                           
                
            
            </div>    
            <div class="form-group" style='display:inline'>
                <label>預約日</label>
                <input type="text" name="apdat" class="form-control " value="<?php echo $apdat; ?>" readonly tabindex=5>
                <label>身份證</label>
                <input type="text" name="userid" class="form-control " value="<?php echo $userid; ?>" readonly tabindex=6>
                <label>護照號碼</label>
                <input type="text" name="passportid" class="form-control " value="<?php echo $passportid; ?>" readonly tabindex=7>
                <label>預約類型--1:快篩 only;2:qPCR only;3:兩者都做</label>
                <input type="text" name="testtype" class="form-control " value="<?php echo $testtype; ?>" readonly tabindex=8>
                <label>中文名</label>
                <input type="text" name="cname" class="form-control " value="<?php echo $cname; ?>" readonly tabindex=9>
                <label>English First Name</label>
                <input type="text" name="fname" class="form-control " value="<?php echo $fname; ?>" readonly tabindex=10>
                <label>English Last Name</label>
                <input type="text" name="lname" class="form-control " value="<?php echo $lname; ?>" readonly tabindex=11>
                <label>生日</label>
                <input type="text" name="dob" class="form-control " value="<?php echo $dob; ?>" readonly tabindex=12>
                <label>性別</label>
                <input type="text" name="sex" class="form-control " value="<?php echo $sex; ?>" readonly tabindex=13>
                <label>手機</label>
                <input type="text" name="mobile" class="form-control " value="<?php echo $mobile; ?>" readonly tabindex=14>
                <input type="text" name="uuid" class="form-control " value="<?php echo $uuid; ?>" hidden tabindex=15>
                <input type="text" name="testtype" class="form-control " value="<?php echo $testtype; ?>" hidden tabindex=16>
                
                
                <label>快篩結果</label>
                <label>前次結果</label> <input type="text" name="ftest_display" style="color: Tomato;" class="form-control " value="<?php echo $ftest; ?>" readonly tabindex=17>
                <br>
                <select id="ftest" name="ftest" size="3"  value="<?php echo $ftest; ?>" tabindex=18>
                <option value="NA">未判定</option>
                <option value="positive">陽性 positive</option>
                <option value="negative">陰性 negative</option>
                </select>
                <br>
                
                <label>PCR結果</label>
                <label>前次結果</label> <input type="text" name="pcrtest_display" style="color:Tomato;" class="form-control " value="<?php echo $pcrtest; ?>" readonly tabindex=19>
                <br>
                <select id="pcrtest" name="pcrtest" size="3" value="<?php echo $pcrtest; ?>" tabindex=20>
                <option value="NA">未判定</option>
                <option value="positive">陽性 positive</option>
                <option value="negative">陰性 negative</option>
                </select>

                <br>
            
                <label>判讀者ID</label>
                <input type="text":focus name="vuser1" class="form-control " onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $vuser1; ?>" tabindex=21>
                <br>
                <input type="submit" name="save" class="btn btn-primary" value="save">
                    

                

            </div>    
            
           
        </form>
    </div>
</body>
</html>