<?php
/* Smarty version 3.1.34-dev-7, created on 2023-03-28 10:02:47
  from 'C:\Users\asoma\appoint-back\appoint-back\templates\jqgrid_js.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_64229f279faac6_70862022',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c893bce6c62787aacf5dc576daad1f0f078c6738' => 
    array (
      0 => 'C:\\Users\\asoma\\appoint-back\\appoint-back\\templates\\jqgrid_js.tpl',
      1 => 1679990563,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_64229f279faac6_70862022 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">

  //----------------------------------
  //功能名稱 : 廣告維護
  //建立日期 : 2020/05/13 15:44:48
  //建立人員 : Max Cheng
  //修改日期 : 2020/10/19
  //修改人員 : Max Cheng
  //----------------------------------
  var rowData=[];
  var mode="QUERY";
  var query_date = "";
  $(document).ready(function () {

    // put date select value in the date variable
    var appoint_date = document.getElementById('QueryDate').value;
    document.getElementById('query_appoint_date').value = appoint_date;
    query_date = document.getElementById('query_appoint_date').value;
    // set grid selected row from the uuid
    var NumOfRow = Math.floor(($(window).height()-102)/48);
    var Height = Math.floor(($(window).height())-320);
    jQuery("#jqGrid").jqGrid({
      // 連結資料擷取程式
      url: "getOverviewListJson.php?date=" + query_date,
      datatype: "json",
      // height: 'auto',
      width: $(window).width()-85,
      height: Height,      
      // autowidth: true,
      colModel: [
          {label:"uuid", name: "uuid", width: "3%"},
          {label:"中文名", name: "cname", width: "6%"},
          {label:"英文名", name: "fname", width: "10%"},
          {label:"身分證號", name: "userid", width: "7%"},
          {label:"護照號碼", name: "passportid", width: "6%"},
          {label:"台胞證號", name: "mtpid", hidden: true},
          {label:"健保卡號", name: "hicardno", hidden: true},
          {label:"手機號碼", name: "mobile", width: "6%"},
          {label:"電子郵件", name: "uemail", width: "10%"},
          {label:"預約日期", name: "apdat", width: "5%", formatter: "date", formatoptions: { srcformat: "ISO8601Long", newformat: "Y-m-d" }},
          {label:"採檢日期", name: "tdat", width: "7%", formatter: "date", formatoptions: { srcformat: "ISO8601Long", newformat: "Y-m-d H:i:s" }},
          {label:"檢測項目", name: "testtype", hidden: true, width: "6%"},
          {label:"採檢編號", name: "sampleid2", width: "6%"},
          {label:"報告時效", name: "twrpturgency", width: "6%", align:"center"},
          {label:"公司抬頭", name: "companytitle", width: "6%"},
          {label:"公司統編", name: "sendname", width: "6%"},
          {label:"收據編號", name: "receiptid", width: "6%"},
          {label:"篩檢原因", name: "testreason", hidden: true},
          {label:"付款方式", name: "payflag", width: "6%", align:"center"},
          {label:"郵寄報告", name: "qrptflag", width: "6%", align:"center"},
          {label:"報告日期", name: "rdat", hidden: true},
          {label:"檢測結果", name: "pcrtest", hidden: true},
      ],
      // rowNum:NumOfRow,
      rowNum: 1000,
      rowTotal: 100000,
      loadonce:true,
      mtype: "GET",
      gridview: true,
      pager: "#jqGridPager",
      viewrecords: true,
      ondblClickRow: function (id) {
        window.location.href = "../belle/modifiednew.php?id=" + id+"&date=" + query_date;
      },

      loadComplete: function(){
        var uuid = document.getElementById('uuid').value;
        if(uuid === ""){
          return;
        }
        jQuery('#jqGrid').jqGrid('setSelection', uuid);

        // Get the selected row ID
        var selectedRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');

  

        // Get the row element by selected row ID
        var rowElement = $("#jqGrid").find("#" + selectedRowId).get(0);

        // Calculate the row position relative to the grid
        var rowPosition = rowElement.offsetTop;

        // Scroll the grid to the selected row
        $("#jqGrid").closest(".ui-jqgrid-bdiv").scrollTop(rowPosition);

      },  

    });  
  });

  /**THIS FUNCTION IS FOR SEARCH AND TO FILTER ANY CHARACTER**/
  var timer;
  $("#SearchTable").on("keyup", function() {
      var self = this;
      if (timer) { clearTimeout(timer); }
      timer = setTimeout(function() {
          $("#jqGrid").jqGrid('filterInput', self.value);
      }, 0);
  });

  $("#jqGrid").bind("jqGridAfterGridComplete", function () {
    // the event handler will be executed AFTER gridComplete
    var uuid = document.getElementById('uuid').value;
    jQuery('#jqGrid').jqGrid('setSelection', uuid);
    jQuery('#jqGrid').trigger('reloadGrid' );
  });

  $("#BtnQuery").click(function(){
    uuid = "";
    // remove url get parameter

    query_date = $("#query_appoint_date").val();
    window.location.href = "home.php?date=" + query_date;
    jQuery('#jqGrid').jqGrid('clearGridData');
    jQuery('#jqGrid').jqGrid('setGridParam',
    {
      datatype: 'json',
      fromServer: true,
      url: 'getOverviewListJson.php?date=' + query_date,
    });
    jQuery('#jqGrid').trigger('reloadGrid' );
  });

// 設定 jQuery 日期選擇器
$(function() {
  //$("#appoint_date").datepicker({ dateFormat: 'yy-mm-dd' });
  //$("#checkin_date").datepicker({ dateFormat: 'yy-mm-dd' });
  $("#query_appoint_date").datepicker({dateFormat: 'yy-mm-dd'});
  //$("#birthday").datepicker({dateFormat: 'yy-mm-dd'});
})

// 重新整理 jqGrid
  function ReloadGrid(id){
    var p = jQuery('#jqGrid').jqGrid("getGridParam");
    jQuery('#jqGrid').jqGrid('clearGridData');
    jQuery('#jqGrid').jqGrid('setGridParam', {datatype: 'json'});
    jQuery('#jqGrid').jqGrid('setGridParam', {url: "getOverviewListJson.php"});
    //jQuery('#jqGrid').trigger('reloadGrid');
    jQuery('#jqGrid').trigger("reloadGrid", { page: p.page, current: true });
    //jQuery('#jqGrid').jqGrid('setSelection', id);
  }
  function selectElement(id, valueToSelect){
    let element = document.getElementById(id);
    element.value = valueToSelect;
  }

  function getID(){
    var Today=new Date();
    var result = 'A' + Today.getFullYear() + (Today.getMonth()+1) + Today.getDate() + Today.getHours() + Today.getMinutes();
    return result;
  }

<?php echo '</script'; ?>
>
<!-----------------------------end-----------------------------------------><?php }
}
