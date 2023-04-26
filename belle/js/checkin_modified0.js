d3.select("#read").selectAll("input").attr("readonly", "true");
// d3.select("#read").selectAll("input").attr("readonly", "true");

d3.select("#defaultCheck2").on("change", update);
// update();
function update() {
    if (d3.select("#defaultCheck2").property("checked")) {
        document.getElementById('apdat').removeAttribute('readonly');
        document.getElementById('testtype').removeAttribute('readonly');
        document.getElementById('cname').removeAttribute('readonly');
        document.getElementById('ename').removeAttribute('readonly');
        document.getElementById('dob').removeAttribute('readonly');
        document.getElementById('sex').removeAttribute('readonly');
        document.getElementById('email').removeAttribute('readonly');
        document.getElementById('userid1').removeAttribute('readonly');
        document.getElementById('passportid1').removeAttribute('readonly');
        document.getElementById('mobile1').removeAttribute('readonly');
        document.getElementById('hicardno1').removeAttribute('readonly');
        document.getElementById('sendname').removeAttribute('readonly');
        document.getElementById('testreason').removeAttribute('readonly');
        document.getElementById('sampleid1').removeAttribute('readonly');
        document.getElementById('sampleid2').removeAttribute('readonly');
        document.getElementById('nationality').removeAttribute('readonly');

    } else {
        d3.select("#apdat").attr('readonly', true);
        d3.select("#testtype").attr('readonly', true);
        d3.select("#cname").attr('readonly', true);
        d3.select("#ename").attr('readonly', true);
        d3.select("#dob").attr('readonly', true);
        d3.select("#sex").attr('readonly', true);
        d3.select("#email").attr('readonly', true);
        d3.select("#userid1").attr('readonly', true);
        d3.select("#passportid1").attr('readonly', true);
        d3.select("#mobile1").attr('readonly', true);
        d3.select("#hicardno1").attr('readonly', true);
        d3.select("#sendname").attr('readonly', true);
        d3.select("#testreason").attr('readonly', true);
        d3.select("#sampleid1").attr('readonly', true);
        d3.select("#sampleid2").attr('readonly', true);
        d3.select("#nationality").attr('readonly', true);
    }
}

function IdCardNumberCheck(id) {
    if(id == "") {
        document.getElementById("PointMsgIdCardNumber").innerHTML = "<span style='color:red; font-style:italic'></span>";
    }else{
        // console.log(id);
        var city = new Array(1, 10, 19, 28, 37, 46, 55, 64, 39, 73, 82, 2, 11, 20, 48, 29, 38, 47, 56, 65, 74, 83, 21, 3, 12, 30);
        id = id.toUpperCase();
        // 使用「正規表達式」檢驗格式
        if (!id.match(/^[A-Z]\d{9}$/) && !id.match(/^[A-Z][A-D]\d{8}$/)) {
            document.getElementById("PointMsgIdCardNumber").innerHTML = "<span style='color:red; font-style:italic'>請輸入正確身分證字號格式。</span>";
            document.getElementById("userid1").value = "";
        } else {
            var total = 0;
            if (id.match(/^[A-Z]\d{9}$/)) { //身分證字號
                //將字串分割為陣列(IE必需這麼做才不會出錯)
                id = id.split('');
                //計算總分
                total = city[id[0].charCodeAt(0) - 65];
                for (var i = 1; i <= 8; i++) {
                    total += eval(id[i]) * (9 - i);
                }
            } else { // 外來人口統一證號
                //將字串分割為陣列(IE必需這麼做才不會出錯)
                id = id.split('');
                //計算總分
                total = city[id[0].charCodeAt(0) - 65];
                // 外來人口的第2碼為英文A-D(10~13)，這裡把他轉為區碼並取個位數，之後就可以像一般身分證的計算方式一樣了。
                id[1] = id[1].charCodeAt(0) - 65;
                for (var i = 1; i <= 8; i++) {
                    total += eval(id[i]) * (9 - i);
                }
            }
            //補上檢查碼(最後一碼)
            total += eval(id[9]);
            //檢查比對碼(餘數應為0);
            if (total % 10 == 0) {
                document.getElementById("PointMsgIdCardNumber").innerHTML = "<span style='color:red; font-style:italic'></span>";
            }
            else {
                document.getElementById("PointMsgIdCardNumber").innerHTML = "<span style='color:red; font-style:italic'>驗證錯誤，請輸入正確的身分證字號</span>";
                document.getElementById("userid1").value = "";
            }
        }
    }
}

function TesttypeCheck() {
    var testtype = document.getElementById("testtype").value;
    var sampleid1 = document.getElementById("sampleid1").value;
    var sampleid2 = document.getElementById("sampleid2").value;
    var str = document.getElementById("sampleid1").value;
    var found = str.match(/^F[0-9]{9}/g);
    var str2 = document.getElementById("sampleid2").value;
    var found2 = str2.match(/^Q[0-9]{9}|^QL[0-9]{9}}|^QH[0-9]{9}/g);

    if (testtype == "") {
        document.getElementById("PointMsgTesttype").innerHTML = "<span style='color:red; font-style:italic'>此欄不能為空。</span>";
    } else if (testtype !== "1" && testtype !== "2" && testtype !== "3") {
        document.getElementById("PointMsgTesttype").innerHTML = "<span style='color:red; font-style:italic'>請輸入正確格式</span>";
    } else {
        document.getElementById("PointMsgTesttype").innerHTML = "<span style='color:red; font-style:italic'></span>";
    }
    if (testtype == "1") {
        if (sampleid1 != "" && sampleid2 != "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>檢測型別與 ID 數量不符</span>";
        } else if (sampleid2 != "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>此欄位不是快篩 ID </span>";
        } else if (sampleid1 == "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>請輸入快篩 ID，若未報到，可留空。</span>";
        } else if (str != found) {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>快篩 ID 格式有誤。</span>";
        } else {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'></span>";
        }
    } else if (testtype == "2") {
        if (sampleid1 != "" && sampleid2 != "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>ID 數量與檢測型別不符</span>";
        } else if (sampleid1 != "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>此欄位不是 PCR ID</span>";
        } else if (sampleid2 == "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>請輸入 PCR ID，若未報到，可留空。</span>";
        } else if (str2 != found2) {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>PCR ID 格式有誤。</span>";
        }   else {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'></span>";
        }
    } else if (testtype == "3") {
        if (sampleid1 == "" && sampleid2 == "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'> 請輸入快篩 ID 與 PCR ID，若未報到，可留空。</span>";
        } else if (sampleid1 == "" || sampleid2 == "") {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'> 請輸入快篩 ID 與 PCR ID</span>";
        } else if (str != found) {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>快篩 ID 格式有誤。</span>";
        } else if (str2 != found2) {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'>PCR ID 格式有誤。</span>";
        } else {
            document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'></span>";
        }
    } else {
        document.getElementById("PointMsgTesttypeCheck").innerHTML = "<span style='color:red; font-style:italic'></span>";

    }
}

function GenderCheck() {
    var gender = document.getElementById("sex").value;
    if (gender !== "男 / Male" && gender !== "女 / Female" && gender !== "NA") {
        document.getElementById("PointMsgGender").innerHTML = "<span style='color:red; font-style:italic'>請輸入正確格式</span>";
    }
    else {
        document.getElementById("PointMsgGender").innerHTML = "<span style='color:red; font-style:italic'></span>";
    }
}

function CnameCheck() {
    var str = document.getElementById("cname").value;
    var found = str.match(/[\u4E00-\u9FFF]+/g);
    if (str == "") {
        document.getElementById("PointMsgCname").innerHTML = "<span style='color:red; font-style:italic'></span>";
    } else if (str != found) {
        document.getElementById("PointMsgCname").innerHTML = "<span style='color:red; font-style:italic'>只接受中文字</span>";
    } else {
        document.getElementById("PointMsgCname").innerHTML = "<span style='color:red; font-style:italic'></span>";
    }
}

function EnameCheck() {
    var str = document.getElementById("ename").value;
    var found = str.match(/[a-zA-Z, -]+/g);
    if (str == "") {
        document.getElementById("PointMsgEname").innerHTML = "<span style='color:red; font-style:italic'></span>";
    } else if (str != found) {
        document.getElementById("PointMsgEname").innerHTML = "<span style='color:red; font-style:italic'>只接受英文、','、'-'或 ' '。</span>";
    } else {
        document.getElementById("PointMsgEname").innerHTML = "<span style='color:red; font-style:italic'></span>";
    }
}

// function SendnameCheck() {
//     var str = document.getElementById("sendname").value;
//     var found = str.match(/[0-9]+/g);
//     if (str == "") {
//         document.getElementById("PointMsgSendname").innerHTML = "<span style='color:red; font-style:italic'></span>";
//     }
//     else if (str != found) {
//         document.getElementById("PointMsgSendname").innerHTML = "<span style='color:red; font-style:italic'>只接受數字。</span>";
//     } else {
//         document.getElementById("PointMsgSendname").innerHTML = "<span style='color:red; font-style:italic'></span>";
//     }
// }
function MobileCheck() {
    var str = document.getElementById("mobile1").value;
    var found = str.match(/^[0-9]+/g);
    if (str == "") {
        document.getElementById("PointMsgMobile").innerHTML = "<span style='color:red; font-style:italic'>此欄不能為空。</span>";
    } else if (str != found) {
        document.getElementById("PointMsgMobile").innerHTML = "<span style='color:red; font-style:italic'>只接受數字。</span>";
    } else {
        document.getElementById("PointMsgMobile").innerHTML = "<span style='color:red; font-style:italic'></span>";
    }
}

function TestreasonCheck() {
    var str = document.getElementById("testreason").value;
    var found = str.match(/[1-7]{1}/g);
    if (str == "") {
        document.getElementById("PointMsgTestreason").innerHTML = "<span style='color:red; font-style:italic'>此欄不能為空。</span>";
    } else if (str != found) {
        document.getElementById("PointMsgTestreason").innerHTML = "<span style='color:red; font-style:italic'>只能輸入數字 1 至 7。</span>";
    } else {
        document.getElementById("PointMsgTestreason").innerHTML = "<span style='color:red; font-style:italic'></span>";
    }
}

function EmailCheck() {
    var strEmail = document.getElementById("email").value;
    //Regular expression Testing
    emailRule = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;

    //validate ok or not
    if (strEmail==""){
        document.getElementById("PointMsgEmail").innerHTML = "<span style='color:red; font-style:italic'>此欄不能為空。</span>";
    }else if (strEmail.search(emailRule) != -1) {
        document.getElementById("PointMsgEmail").innerHTML = "<span style='color:red; font-style:italic'></span>";
    } else {
        document.getElementById("PointMsgEmail").innerHTML = "<span style='color:red; font-style:italic'>E-mail 格式有誤。</span>";
    }
}

