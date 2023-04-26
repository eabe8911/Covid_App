$(document).ready(function () {
    var NumOfRow = Math.floor(($(window).height()-270)/25);
    jQuery("#OverviewList").jqGrid({
        url: "getOverviewListJson.php",
        datatype: "json",
        height: $(window).height()-258,
        width: $(window).width()-230,

        colNames: ["userid","cname","fname","dob","mobile", "uemail", "apdat", "sampleid2", "tdat"],
        colModel: [
            { name: "身分證號", width: "10%",editable:false},
            { name: "中文名", width: "10%",editable:true,editoptions:{size:30},formoptions:{ rowpos:1, elmprefix:"(*)"},editrules:{required:true}},
            { name: "英文名", width: "10%",editable:true,editoptions:{size:30},formoptions:{ rowpos:2,elmprefix:"&nbsp;&nbsp;&nbsp;&nbsp;"}},
            { name: "生日", width: "8%" ,editable:true,editoptions:{size:30},formoptions:{ rowpos:3,elmprefix:"&nbsp;&nbsp;&nbsp;&nbsp;"}},
            { name: "手機號", width:"12%" ,editable:true,editoptions:{size:30},formoptions:{ rowpos:4,elmprefix:"&nbsp;&nbsp;&nbsp;&nbsp;"}},
            { name: "電子郵件", width:"12%" ,editable:true,editoptions:{size:30},formoptions:{ rowpos:5,elmprefix:"&nbsp;&nbsp;&nbsp;&nbsp;"}},
            { name: "預約日期", width:"5%" ,editable:true,editoptions:{size:30},formoptions:{ rowpos:6,elmprefix:"&nbsp;&nbsp;&nbsp;&nbsp;"}},
            { name: "採檢編號", width:"5%" ,editable:true,editoptions:{size:30},formoptions:{ rowpos:7,elmprefix:"&nbsp;&nbsp;&nbsp;&nbsp;"}},
            { name: "測試日期", width:"5%" , align:"right",editable:true,editoptions:{size:30},formoptions:{ rowpos:8,elmprefix:"&nbsp;&nbsp;&nbsp;&nbsp;"}}
        ],
        rowNum:NumOfRow,
        autowidth: false,
        rowTotal: 3000,
        loadonce:true,
        mtype: "GET",
        rownumbers: true,
        gridview: true,
        pager: "#PageOverview",
        sortname: "Create_date",
        sortorder: "desc",
        viewrecords: true,
        caption: "預約資料維護",
        editurl:"SaveData.php"
    });
    
    jQuery("#OverviewList").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});  