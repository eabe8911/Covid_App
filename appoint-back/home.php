<?php
session_start();
if($_SESSION["loggedin"]!= TRUE){
    header("Location: index.php");
    session_unset();
    die();
}
$username = $_SESSION['username'];

if(empty(filter_input(INPUT_GET, 'date'))){
    $QueryDate = date('Y-m-d');
}else{
    $QueryDate = filter_input(INPUT_GET, 'date');
}
// TODO: get uuid from $_GET
if(empty(filter_input(INPUT_GET, 'id'))){
    $uuid = '';
}else{
    $uuid = filter_input(INPUT_GET, 'id');
}
//getPromotion();

require 'libs/Smarty.class.php';

$smarty = new Smarty;
/**OTHERS**/
$smarty->assign("UserName", $username, true);
$smarty->assign("Menu", "Menu", true);
$smarty->assign("Title", "麗寶生醫", true);
$smarty->assign("AgentName", "   ", true);
$smarty->assign("AgentPhoto", "assets/images/users/avatar-1.jpg", true);
/*View Form*/
$smarty->assign("FormAction", "home.php", true);
$smarty->assign("Onsubmit", "", true);
$smarty->assign("FormName", "ViewForm", true);
/*Hidden Fields*/
$smarty->assign("Hiddenfield1", "<input type='hidden' id='QueryDate' name='QueryDate' value=$QueryDate>");
$smarty->assign("Hiddenfield2", "<input type='hidden' id='uuid' name='uuid' value=$uuid>");
/**VIEW PROFILE DETAILS**/
$smarty->assign("Membername", "", true);
$smarty->assign("Cardnumber", "", true);
$smarty->assign("Companyname", "", true);
$smarty->assign("HealthcardStatus", "", true);
$smarty->assign("MemberType", "", true);
$smarty->assign("Gender", "Female", true);
$smarty->assign("Status", "Single", true);
$smarty->assign("PrincipalName", "", true);
$smarty->assign("Expiry", "", true);
$smarty->assign("Mcb", "", true);
$smarty->assign("Balance", "", true);

/**BUTTON**/
$smarty->assign("LogoutButton", "Logout.php", true);
$smarty->assign("Iviewbtn", "", true);
$smarty->assign("Approve", "checkbox13", true);
$smarty->assign("Disapprove", "checkbox14", true);
$smarty->assign("SubmitViewBtn", "", true);

/**JQGRID TABLE**/
$smarty->assign("Search", "SearchTable", true);
$smarty->assign("jqGrid", "jqGrid", true);
$smarty->assign("jqGridPager", "jqGridPager", true);

/**ALARM , ELIGIBILITY , CONSULTATION , OUTPATIENT , EMERGENCY**/
$smarty->assign("Alarm", "0", true);
$smarty->assign("Inpatient", "0", true);
//$smarty->assign("Consultation", "0", true);
$smarty->assign("Outpatient", "0", true);
$smarty->assign("Emergency", "0", true);

//MENU OPTIONS
$smarty->assign("SelectionMenuModal", "",true);

/**PAGES***/
$smarty->display('home.tpl');