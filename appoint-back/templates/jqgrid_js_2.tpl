<script type="text/javascript">
{literal}
  //----------------------------------
  //功能名稱 : 廣告維護
  //建立日期 : 2020/05/13 15:44:48
  //建立人員 : Max Cheng
  //修改日期 : 2020/10/19
  //修改人員 : Max Cheng
  //----------------------------------
  var rowData=[];
  var mode="";
  $(document).ready(function () {
    // 20220728:取得今天的年月日填入搜尋欄位
    const today = new Date();
    const yyyy = today.getFullYear();
    let mm = today.getMonth() + 1;
    let dd = today.getDate();
    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;
    const formattedToday = yyyy + '-' + mm + '-' + dd;
    document.getElementById('query_appoint_date').value = formattedToday;
    var NumOfRow = Math.floor(($(window).height()-102)/48);
    var Height = Math.floor(($(window).height())-320);
    jQuery("#jqGrid").jqGrid({
      rul: "getOverviewListJson.php?date=" + formattedToday,
      datatype: "json",
      width: $(window).width()-55,
      height: Height,      
      colModel: [
          {label:"uuid",    name: "uuid", hidden: true},
          {label:"中文名",   name: "cname", width: "6%"},
          {label:"英文名",   name: "fname", width: "10%"},
          {label:"身分證號", name: "userid", width: "7%"},
          {label:"護照號碼", name: "passportid", width: "6%"},
          {label:"台胞證號", name: "mtpid", hidden: true},
          {label:"健保卡號", name: "hicardno", hidden: true},
          {label:"手機號碼", name: "mobile", hidden: true},
          {label:"電子郵件", name: "uemail", width: "15%"},
          {label:"預約日期", name: "apdat", width: "5%", formatter: "date", formatoptions: { srcformat: "ISO8601Long", newformat: "Y-m-d" }},
          {label:"採檢日期", name: "tdat", width: "7%", formatter: "date", formatoptions: { srcformat: "ISO8601Long", newformat: "Y-m-d h:i:s" }},
          {label:"採檢編號", name: "sampleid2", width: "6%"},
          {label:"報告時效", name: "twrpturgency", width: "6%"},
          {label:"篩檢原因", name: "testreason", hidden: true},
          {label:"生日",    name: "dob", width: "6%", hidden: true},
          {label:"國籍",    name: "testreason", hidden: true},
          {label:"報告日期", name: "rdat", hidden: true},
          {label:"檢測結果", name: "pcrtest", hidden: true},
      ],
      rowNum:NumOfRow,
      rowTotal: 10000,
      loadonce:true,
      mtype: "GET",
      gridview: true,
      pager: "#jqGridPager",
      viewrecords: true,
      ondblClickRow: function (id) {
        setDataEmpty();
        rowData = jQuery(this).getRowData(id);
        mode="Query";
        FillFieldsData();
        setFieldsDisabled(true);
        $("#Query").show();
        $("#Modify").hide();
        $("#ViewAdsDetail").modal({backdrop: "static"});
      }
    });    
  });
{/literal}
</script>
<!-----------------------------end----------------------------------------->