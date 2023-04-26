<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
// email pdf reports
ini_set("display_errors","off");
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
//header('Content-Type: image/png');
// require package

require 'vendor/autoload.php';

// This will output the barcode as HTML output to display in the browser

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["print"])) {

   // $generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG(); // Vector based SVG
   // $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG(); // Pixel based PNG
   // $generatorJPG = new Picqer\Barcode\BarcodeGeneratorJPG(); // Pixel based JPG
   // $generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML(); // Pixel based HTML
   //$generatorHTML = new Picqer\Barcode\BarcodeGeneratorDynamicHTML(); // Vector based HTML



	$generator = new Picqer\Barcode\BarcodeGeneratorSVG();
	try {
		$userid     = $_SESSION["userid"];
		$passportid = $_SESSION["passportid"];
		$sampleid1  = $_SESSION["sampleid1"];
		$sampleid2  = $_SESSION["sampleid2"];
		$sampleid   = '';

		//$_rpturgency = trim($csv[14]);
		$sample=substr($sampleid2, 1, 2);
        switch ($sample) {
            case 'QH':
            case 'QL':
                $sampleid = substr($sampleid2, 8);
                break;
            default:
				$sampleid = substr($sampleid2, 7);
                break;
        }
		
	} catch(Exception $e) {
		echo 'Message: ' . $e->getMessage();
	}

	$cname    = $_SESSION["cname"];
	$fname    = $_SESSION["fname"];
	$sendname = $_SESSION["sendname"];
	$lname    = $_SESSION["lname"];
	$mobile   = $_SESSION["mobile"];
	$sex =      $_SESSION["sex"];
	$dob =      $_SESSION["dob"];
	$testreason = $_SESSION["testreason"];
	$hicardno = $_SESSION["hicardno"] ;
	$twrpturgency = $_SESSION["twrpturgency"] ;
	$uemail =   $_SESSION["email"];
	$mtpid=     $_SESSION["mtpid"];

	switch ($testreason) {
		case '1':
			$reason = "因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾";
			break;
		case '2':
			$reason = "因工作因素須檢附檢驗證明之民眾";
			break;
		case '3':
			$reason = "短期商務人士";
			break;
		case '4':
			$reason = "出國求學須檢附檢驗證明之民眾";
			break;
		case '5':
			$reason = "外國或中國大陸、香港、澳門人士出境";
			break;
		case '6':
			$reason = "相關出境適用對象之眷屬";
			break;
		default:
			$reason = "因其他因素須檢驗之民眾";
			break;
	}
	// if ($testreason == "1")
	// {
	// 	$reason = "因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾";
	// }
	// elseif ($testreason == "2")
	// {
	// 	$reason = "因工作因素須檢附檢驗證明之民眾";
	// }
	// elseif ($testreason == "3")
	// {
	// 	$reason = "短期商務人士";
	// }
	// elseif ($testreason == "4")
	// {
	// 	$reason = "出國求學須檢附檢驗證明之民眾";
	// }
	// elseif ($testreason == "5")
	// {
	// 	$reason = "外國或中國大陸、香港、澳門人士出境";
	// }
	// elseif ($testreason == "6")
	// {
	// 	$reason = "相關出境適用對象之眷屬";
	// }
	// else
	// {
	// 	$reason = "因其他因素須檢驗之民眾";
	// }

	// print user id
	echo "<body>";
	echo "<h4>列印日期:  ".$date = date('Y-m-d h:i:s a', time())."</h4>";
	//20210902 olive change - start
	echo "<h1>麗寶檢驗所檢驗單 : COVID-19檢測&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$sampleid</h1>";


	if (!empty($sampleid1) && !empty($sampleid2)){
		//echo "<h1>麗寶檢驗所檢驗單 : COVID-19檢測</h1>";
		
		if ($twrpturgency == 'hiurgent')
		{
			echo "<h2>抗原快篩 & qPCR (特急件)</h2>";
		}
		elseif ($twrpturgency == 'urgent')
		{
			echo "<h2>抗原快篩 & qPCR (急件)</h2>";
		}
		else
		{
			echo "<h2>抗原快篩 & qPCR (一般件)</h2>";
		}
	}
	elseif (!empty($sampleid1) && empty($sampleid2)){
		//echo "<h1>麗寶檢驗所檢驗單 : COVID-19檢測&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$sampleid1</h1>";
		if ($twrpturgency == 'hiurgent')
		{
			echo "<h2>抗原快篩 (特急件)</h2>";
		}
		elseif ($twrpturgency == 'urgent')
		{
			echo "<h2>抗原快篩 (急件)</h2>";
		}
		else
		{
			echo "<h2>抗原快篩 (一般件)</h2>";
		}
	}
	elseif (empty($sampleid1) && !empty($sampleid2)){
		//echo "<h1>麗寶檢驗所檢驗單 : COVID-19檢測&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$sampleid2</h1>";
		if ($twrpturgency == 'hiurgent')
		{
			echo "<h2>qPCR (特急件)</h2>";
		}
		elseif ($twrpturgency == 'urgent')
		{
			echo "<h2>qPCR (急件)</h2>";
		}
		else
		{
			echo "<h2>qPCR (一般件)</h2>";
		}
	}
	elseif (empty($sampleid1) && empty($sampleid2)){
		//echo "<h1>麗寶檢驗所檢驗單 : COVID-19檢測&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h1>";
	}
	//20210902 olive  change - end

	//echo "<h2>個案資料</h2>";

	//echo "<h3>姓名:&nbsp".$cname."&nbsp&nbsp".$fname."&nbsp".$lname."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp生日:      ".$dob."</h3>";
	//echo "<h3>性別:&nbsp".$sex."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp手機:&nbsp".$mobile."</h3>";

	if (!empty($sampleid1) && !empty($sampleid2)){
	echo $generator->getBarcode($sampleid1, $generator::TYPE_CODE_128,2,50).
		"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".
		$generator->getBarcode($sampleid2, $generator::TYPE_CODE_128,2,50);
	echo "&nbsp&nbsp快篩ID:&nbsp&nbsp".$sampleid1.
		"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".
		"PCR ID:&nbsp&nbsp".$sampleid2;
	}
	elseif (!empty($sampleid1) && empty($sampleid2)){
	echo $generator->getBarcode($userid, $generator::TYPE_CODE_128,2,50);
	echo "&nbsp&nbsp快篩ID:&nbsp&nbsp".$sampleid1;
	}
	elseif (empty($sampleid1) && !empty($sampleid2)){
	echo $generator->getBarcode($sampleid2, $generator::TYPE_CODE_128,2,50)."<br>";
	echo "PCR ID:&nbsp&nbsp ".$sampleid2;
	}
	
	echo "<h3>經本人確認，本單張所載個人資料 口 無誤　口 有誤，已修正<br>客戶簽名 : ________________</h3>";

	echo "<h3>檢測人員1:      </h3>";
	echo "<h3>檢測結果:&nbsp&nbsp口&nbsp&nbsp陽性(Pos.)&nbsp&nbsp&nbsp&nbsp口&nbsp&nbsp陰性(Neg.)<br>        控制組&nbsp&nbsp&nbsp&nbsp:&nbsp&nbsp口&nbsp&nbsp通過(Pass)&nbsp&nbsp&nbsp口&nbsp&nbsp不通過(Fail)</h3>";

	echo "<h3>檢測人員2:      </h3>";
	echo "<h3>檢測結果:&nbsp&nbsp口&nbsp&nbsp陽性(Pos.)&nbsp&nbsp&nbsp&nbsp口&nbsp&nbsp陰性(Neg.)<br>        控制組&nbsp&nbsp&nbsp&nbsp:&nbsp&nbsp口&nbsp&nbsp通過(Pass)&nbsp&nbsp&nbsp口&nbsp&nbsp不通過(Fail)</h3>";


		
		//0629 move block by willieK

	if (!empty($userid) && !empty($passportid)){
		echo $generator->getBarcode($userid, $generator::TYPE_CODE_128,2,50).
			"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".
			$generator->getBarcode($passportid, $generator::TYPE_CODE_128,2,50)."<br>";
		echo "&nbsp&nbsp身份證:&nbsp&nbsp".$userid.
			"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".
			"護照號碼:&nbsp&nbsp".$passportid;
		}
		elseif (!empty($userid) && empty($passportid)){
		echo $generator->getBarcode($userid, $generator::TYPE_CODE_128,2,50)."<br>";
		echo "&nbsp&nbsp身份證:&nbsp&nbsp".$userid;
		}
		elseif (empty($userid) && !empty($passportid)){
		echo $generator->getBarcode($passportid, $generator::TYPE_CODE_128,2,50)."<br>";
		echo "護照號碼:&nbsp&nbsp".$passportid;
		}
		
		echo "<h4>姓名:&nbsp".$cname."&nbsp&nbsp".$fname."&nbsp".$lname."</h4>";
		echo "<h4>性別:&nbsp".$sex."</h4>";
		echo "<h4>生日:&nbsp".$dob."</h4>";
		echo "<h4>手機:&nbsp".$mobile."</h4>";
		echo "<h4>E-mail:&nbsp".$uemail."</h4>";
		if (!empty($hicardno))
		{
			echo "<h4>健保卡號:&nbsp".$hicardno."&nbsp(若卡號有誤，請通知工作人員)</h4>";
		}
		else
		{
			echo "<h4>健保卡號:&nbspNA</h4>";
		}
		echo "<h4>篩檢原因:</h4>";
		echo "<h4>".$reason."</h4>";
		//20210902 olive change - start
				echo "<h4>台胞證號碼:&nbsp".$mtpid."</h4>";
		//echo "<br><h3>***檢體簽收人簽名&簽收時間***</h3>";
		//echo "<br><h4>採檢批號</h4>";
		if (!empty($sendname)){
		// echo $generator->getBarcode($sendname, $generator::TYPE_CODE_128,2,50)."<br>";
		// echo "採檢批號:      ".$sendname."<br>";
		//20210902 olive change - end

	}
	echo "</body>";
}
$_SESSION["userid"]="";
$_SESSION["passportid"]="";
$_SESSION["sampleid1"]="";
$_SESSION["sampleid2"]="";
$_SESSION["cname"]="";
$_SESSION["lname"]="";
$_SESSION["mobile"]="";
$_SESSION["sex"]="";
$_SESSION["dob"]="";
$_SESSION["mtpid"]="";

//$im = @imagecreate(110, 20)
//or die("Cannot Initialize new GD image stream");
//$background_color = imagecolorallocate($im, 0, 0, 0);
//$text_color = imagecolorallocate($im, 233, 14, 91);
//imagestring($im, 1, 5, 5,  "A Simple Text String", $text_color);
//imagepng($im);
//imagedestroy($im);

?>



