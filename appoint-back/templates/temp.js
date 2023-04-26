
  /**THIS FUNCTION IS FOR SEARCH AND TO FILTER ANY CHARACTER**/
  var timer;
  $("#SearchTable").on("keyup", function() {
      var self = this;
      if (timer) { clearTimeout(timer); }
      timer = setTimeout(function() {
          $("#jqGrid").jqGrid('filterInput', self.value);
      }, 0);
  });

  
  
  // 關閉明細按鈕
  $("#btnCloseDetail").click(function(){
    document.getElementById('SearchTable').value='';
    setDataEmpty();
  });

  $("#BtnQuery").click(function(){
    var query_date=$("#query_appoint_date").val();
    alert(query_date);
    mode = "Query";
    ReQuery(query_date);
  });

  //修改
  $("#BtnEdit").click(function(){
    mode="Edit";
    $("#Query").hide();
    $("#Modify").show();
    setFieldsDisabled(false);
    return false;
  });

  // 新增
  $("#btnAdd").click(function(){
    mode="Add";
    $("#Query").hide();
    $("#Modify").show();
    setDataEmpty();
    setFieldsDisabled(false);
    $("#ViewAdsDetail").modal({backdrop: "static"});
  });

  // 刪除
  $("#BtnDelete").click(function(){
    document.getElementById("Message").innerHTML = "確定要刪除此筆資料嗎?";
    mode="Delete";
    $("#Query").hide();
    $("#Modify").show();
    setFieldsDisabled(true);
    return false;
  });

  // 確定
  $("#BtnSubmit").click(function(){
    var curID = rowData['ID'];
    getData();
    switch(mode) {
      case "Add":
        if(CheckFields()==true){
          InsertData();
        }else{
          return false;
        }
        break;
      case "Edit":
        if(CheckFields()==true){
          UpdateData();
        }else{
          return false;
        }
        break;
      case "Delete":
        DeleteData();
        break;
      default:
        return false;
        break;
    }
    mode = "Query";
    ReloadGrid(curID);
    setFieldsDisabled(true);
    
    $("#ViewAdsDetail").modal({backdrop: "toogle"});
  });

  // 取消
  $("#BtnCancel").click(function(){
    document.getElementById('SearchTable').value='';
    setFieldsDisabled(true);
    $("#ViewAdsDetail").modal({backdrop: "toogle"});
  });

  // 設定 jQuery 日期選擇器
  $(function() {
    //$("#appoint_date").datepicker({ dateFormat: 'yy-mm-dd' });
    //$("#checkin_date").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#query_appoint_date").datepicker({dateFormat: 'yy-mm-dd'});
    //$("#birthday").datepicker({dateFormat: 'yy-mm-dd'});
  });

  // 檢查欄位
  function CheckFields(){
    return true;
  };

  // 轉至首頁
  function NewURL(){
    window.location.href="index.php";
  };

  // 刪除資料
  function DeleteData(){
    var xmlhttp = new XMLHttpRequest();
    rowData['Picture']='';
    document.getElementById("Message").innerHTML = "開始刪除資料";
    var data = JSON.stringify(rowData);
    xmlhttp.open("POST", "DeleteData.php", !0);
    xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xmlhttp.send(data);
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4){
        document.getElementById("Message").innerHTML = "刪除結束";
        if(this.status == 200 || this.status == 0){
          //if(this.responseText == "OK"){
            document.getElementById("Message").innerHTML = "廣告資料刪除成功";
          //}else{
            //document.getElementById("Message").innerHTML = "刪除錯誤："+this.responseText;
            //alert("刪除失敗，請稍後再試! 1");
          //}
        }else{
          document.getElementById("Message").innerHTML = "刪除錯誤："+this.responseText;
          alert("刪除失敗，請稍後再試! readyState=" + this.readyState + " status=" + this.status);
        }
      }else{
        document.getElementById("Message").innerHTML = "刪除進行中："+this.readyState;
      }
    };
  };

  // 新增資料
  function InsertData(){
    document.getElementById("Message").innerHTML = "開始新增廣告資料";
    var data = JSON.stringify(rowData);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "InsertData.php", !0);
    xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xmlhttp.send(data);
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4){
        document.getElementById("Message").innerHTML = "新增結束";
        if(this.status == 200){
          if(this.responseText == "OK"){
            document.getElementById("Message").innerHTML = "廣告資料新增成功";
            alert("廣告資料新增成功!");
          }else{
            document.getElementById("Message").innerHTML =  this.status + " 新增錯誤：" + this.responseText;
              alert("新增廣告失敗，請稍後再試! 1 " + this.status + this.statusText + this.responseText);
          }
        }else{
          document.getElementById("Message").innerHTML = this.status + " 新增錯誤：" + this.responseText;
          alert("新增廣告失敗，請稍後再試! 2 " + this.status + this.statusText + this.responseText);
        }
      }else{
        document.getElementById("Message").innerHTML = "新增進行中：" + this.readyState;
      }
    };
  };

  // 重新查詢 jqGrid
  function ReQuery(query_date){
    jQuery('#jqGrid').jqGrid('clearGridData');
    jQuery('#jqGrid').jqGrid('setGridParam',{datatype: 'json'});
    jQuery('#jqGrid').jqGrid('setGridParam',{rul: "getOverviewListJson.php?date=" + query_date});
  };

  // 重新整理 jqGrid
  function ReloadGrid(id){
    var p = jQuery('#jqGrid').jqGrid("getGridParam");
    jQuery('#jqGrid').jqGrid('clearGridData');
    jQuery('#jqGrid').jqGrid('setGridParam', {datatype: 'json'});
    jQuery('#jqGrid').jqGrid('setGridParam', {url: "getOverviewListJson.php"});
    //jQuery('#jqGrid').trigger('reloadGrid');
    jQuery('#jqGrid').trigger("reloadGrid", { page: p.page, current: true });
    //jQuery('#jqGrid').jqGrid('setSelection', id);
  };

  // 更新資料
  function UpdateData(){
    var xmlhttp = new XMLHttpRequest();
    document.getElementById("Message").innerHTML = "開始更新資料";
    var data = JSON.stringify(rowData);
    xmlhttp.open("POST", "UpdateData.php", !0);
    xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xmlhttp.send(data);
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4){
        document.getElementById("Message").innerHTML = "更新結束";
        if(this.status == 200){
          if(this.responseText == "OK"){
            document.getElementById("Message").innerHTML = "資料更新成功";
          }else{
            document.getElementById("Message").innerHTML = "更新錯誤："+this.responseText;
            alert("更新失敗，請稍後再試!"+this.responseText);
          }
        }else{
          document.getElementById("Message").innerHTML = "更新錯誤："+this.responseText;
          alert("更新失敗，請稍後再試!"+this.responseText);
        }
      }else{
        document.getElementById("Message").innerHTML = "更新進行中："+this.readyState;
      }
    };
  };

  // 資料清除
  function setDataEmpty() {
    //document.getElementById("uuid").value = '';
    // 第一排
    document.getElementById("c_name").value = '';             // 中文名
    document.getElementById("e_name").value = '';             // 英文名
    document.getElementById("user_id").value = '';            // 身分證號
    document.getElementById("passport_id").value = '';        // 護照號碼
    document.getElementById("mtp_id").value = '';             // 台胞證號
    document.getElementById("healthcare_id").value = '';      // 健保卡號
    document.getElementById("mobile_phone").value = '';       // 手機號
    document.getElementById("email").value = '';              // 電子郵件
    // 第二排
    document.getElementById("appoint_date").value = '';       // 預約日期
    document.getElementById("checkin_date").value = '';       // 報到日期
    document.getElementById("inspection_id").value = '';      // 採檢編號
    document.getElementById("report_ageing").value = '';      // 報告時效
    document.getElementById("inspection_reason").value = '';  // 篩檢原因
    document.getElementById("birthday").value = '';           // 生日
    document.getElementById("nationality").value = '';        // 國籍
    // 第三排
    document.getElementById("sampling_date").value = '';      // 採樣日期
    document.getElementById("inspection_date").value = '';    // 檢測日期 
    //document.getElementById("inspection_item").value = '';    // 檢測項目
    //document.getElementById("inspection_type").value = '';    // 檢體類型
    //document.getElementById("inspection_method").value = '';  // 檢測方法
    document.getElementById("inspection_result").value = '';  // 檢測結果
    document.getElementById("Message").innerHTML = "";
  }

  // 從網頁欄位讀取資料
  function getData() {
    rowData['uuid']         = document.getElementById('uuid').value;
    // 第一排
    rowData['cname']        = document.getElementById("c_name").value;            // 中文姓名
    rowData['fname']        = document.getElementById("e_name").value;            // 英文姓名
    rowData['userid']       = document.getElementById("user_id").value;           // 身分證號
    rowData['passportid']   = document.getElementById("passport_id").value;       // 護照號碼
    rowData['mtpid']        = document.getElementById("mtp_id").value;            // 台胞證號
    rowData['hicardno']     = document.getElementById("healthcare_id").value;     // 健保卡號
    rowData['mobile']       = document.getElementById("mobile_phone").value;      // 手機號
    rowData['uemail']       = document.getElementById("email").value;             // 電子郵件
    // 第二排
    rowData['apdat']        = document.getElementById("appoint_date").value;      // 預約日期
    rowData['tdat']         = document.getElementById("checkin_date").value;      // 報到日期
    rowData['sampleid2']    = document.getElementById("inspection_id").value;     // 採檢編號
    rowData['twrpturgency'] = document.getElementById("report_ageing").value;     // 報告時效
    rowData['testreason']   = document.getElementById("inspection_reason").value; // 篩檢原因
    rowData['dob']          = document.getElementById("birthday").value;          // 生日

    var select = document.getElementById("nationality");
    rowData['nationality']  = select.options[select.selectedIndex].value;         // 國籍
    
    // 第三排
    rowData['rdat']         = document.getElementById("inspection_date").value;   // 檢測日期
    rowData['pcrtest']      = document.getElementById("inspection_result").value; // 檢測結果
  };

  // 將資料填入網頁欄位
  //fill data
  function FillFieldsData() {
    document.getElementById("uuid").value            = rowData['uuid'];
    // 第一排
    document.getElementById("c_name").value          = rowData['cname'];            // 中文名
    document.getElementById("e_name").value          = rowData['fname'];            // 英文名
    document.getElementById("user_id").value         = rowData['userid'];           // 身分證號
    document.getElementById("passport_id").value     = rowData['passportid'];       // 護照號碼
    document.getElementById("mtp_id").value          = rowData['mtpid'];            // 台胞證號
    document.getElementById("healthcare_id").value   = rowData['hicardno'];         // 健保卡號
    document.getElementById("mobile_phone").value    = rowData['mobile'];           // 手機號
    document.getElementById("email").value           = rowData['uemail'];           // 電子郵件
    // 第二排
    document.getElementById("appoint_date").value    = rowData['apdat'];            // 預約日期
    document.getElementById("checkin_date").value    = rowData['tdat'];             // 報到日期
    document.getElementById("inspection_id").value   = rowData['sampleid2'];        // 採檢編號
    selectElement('report_ageing', rowData['twrpturgency']);                        // 報告時效
    selectElement('inspection_reason', rowData['testreason']);                      // 篩檢原因
    document.getElementById("birthday").value        = rowData['dob'];              // 生日
    selectElement('nationality', rowData['nationality']);                           // 國籍
    // 第三排
    document.getElementById("sampling_date").value   = rowData['tdat'];             // 採樣日期
    document.getElementById("inspection_date").value = rowData['rdat'];             // 檢測日期 
    //document.getElementById("inspection_item").value = '';                          // 檢測項目
    //document.getElementById("inspection_type").value = '';                          // 檢體類型
    //document.getElementById("inspection_method").value = '';                        // 檢測方法
    document.getElementById("inspection_result").value = rowData['pcrtest'];        // 檢測結果
    document.getElementById("Message").innerHTML = "";
    //return false;
  };

  function selectElement(id, valueToSelect){
    let element = document.getElementById(id);
    element.value = valueToSelect;
  };

  // 設定欄位唯讀屬性
  function setFieldsDisabled(bool){
    // 第一排
    $('#c_name').prop('disabled', bool);
    $('#e_name').prop('disabled', bool);
    $('#user_id').prop('disabled', bool);
    $('#passport_id').prop('disabled', bool);
    $('#mtp_id').prop('disabled', bool);
    $('#healthcare_id').prop('disabled', bool);
    $('#mobile_phone').prop('disabled', bool);
    $('#email').prop('disabled', bool);
    // 第二排
    $('#appoint_date').prop('disabled', bool);
    $('#checkin_date').prop('disabled', bool);
    $('#inspection_id').prop('disabled', bool);
    $('#report_ageing').prop('disabled', bool);
    $('#inspection_reason').prop('disabled', bool);
    $('#birthday').prop('disabled', bool);
    $('#nationality').prop('disabled', bool);
    // 第三排
    $('#sampling_date').prop('disabled', bool);
    $('#inspection_date').prop('disabled', bool);
    $('#inspection_item').prop('disabled', bool);
    $('#inspection_type').prop('disabled', bool);
    $('#inspection_method').prop('disabled', bool);
    $('#inspection_result').prop('disabled', bool);
  return false;
  };
