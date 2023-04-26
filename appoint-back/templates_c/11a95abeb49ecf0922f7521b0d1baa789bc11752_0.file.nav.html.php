<?php
/* Smarty version 3.1.34-dev-7, created on 2023-04-12 10:27:46
  from 'C:\Users\tina.xue\Documents\Tina\appoint-back\appoint-back\nav.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_64366b8222e2f7_66665388',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '11a95abeb49ecf0922f7521b0d1baa789bc11752' => 
    array (
      0 => 'C:\\Users\\tina.xue\\Documents\\Tina\\appoint-back\\appoint-back\\nav.html',
      1 => 1681107127,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_64366b8222e2f7_66665388 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-fluid">
    <a class="navbar-brand" style="color: #ffffe6;" href="menu.php">麗寶生醫新冠肺炎檢測系統</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <!-- <li class="nav-item">
                <a class="nav-link active" style="color: #ffffe6;" aria-current="page" href="menu_version1.html">舊版首頁</a>
            </li> -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" style="color: #ffffe6;" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    營業部
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="import.php">上傳/下載資料</a></li>
                    <li><a class="dropdown-item" href="checkin.php">客戶報到</a></li>
                    <li><a class="dropdown-item" href="checkinnew.php">新版客戶報到</a></li>
                    <li><a class="dropdown-item" href="modified.php">修改客戶資料</a></li>
                    <!-- <li><a class="dropdown-item" href="modifiednew.php">新版修改客戶資料</a></li> -->
                    <li><a class="dropdown-item" href="../appoint-back/home.php">新版修改客戶資料</a></li>
                    <li><a class="dropdown-item" href="generate_report.php">重新製作報告</a></li>
                    <li><a class="dropdown-item" href="generate_reportO.php">製作咽喉報告</a></li>
                    <li><a class="dropdown-item" href="search_info.php">查詢/寄送報告</a>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" style="color: #ffffe6;" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    檢驗部
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="import_result.php">上傳 ABI7500 PCR 結果</a></li>
                    <li><a class="dropdown-item" href="import_result_vita.php">上傳 VitaPCR 結果</a></li>
                    <li><a class="dropdown-item" href="import_result_ftest.php">輸入快篩報告</a></li>
                    <li><a class="dropdown-item" href="update_report.php">修改報告</a></li>
                    <li><a class="dropdown-item" href="search_info2.php">查詢報告</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" style="color: #ffffe6;" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    下載資料
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="excel_report_download_1.php">健保VPN資料下載</a></li>
                    <li><a class="dropdown-item" href="excel_report_download_3.php">下載麗寶月報表</a></li>
                    <li><a class="dropdown-item" href="excel_report_download_4.php">下載麗寶陽性月報表</a></li>
                    <li><a class="dropdown-item" href="excel_report_download_2.php">下載診所月報表</a></li>
                    <li><a class="dropdown-item" href="excel_report_download_99.php">下載客戶資料報表</a></li>
                    <li><a class="dropdown-item" href="excel_report_download_date.php">下載每日資訊檢核表</a></li>
                    <li><a class="dropdown-item" href="excel_report_download_5.php">下載陽性查詢表(五日內)</a></li>
       		    <li><a class="dropdown-item" href="excel_report_download_twrpturgency.php">下載報告時效性表</a></li>
       		    <li><a class="dropdown-item" href="excel_report_download_name.php">下載採編來賓表</a></li>
	 	   <li><a class="dropdown-item" href="excel_report_download_data.php">下載每日工作表</a></li>
     		
                </ul>
                <!-- <a class="nav-link active" style="color: #ffffe6;" aria-current="page" href="excel_report_download.php">Excel 報表下載</a> -->
            </li>
            <li class="nav-item">
                <a class="nav-link active" style="color: #ffffe6;" aria-current="page" href="read_log.php">SQL 紀錄查詢</a>
            </li>
                        <li class="nav-item">
                <a class="nav-link active" style="color: #ffffe6;" aria-current="page" href="generate_report1.php">查詢檢測狀況</a>
            </li>
        </ul>
    </div>
    <a class="navbar-brand" style="color: #ffffe6;" href="logout.php">登出</a>
</div>
<?php }
}
