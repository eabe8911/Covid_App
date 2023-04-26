<!--POP UP MODAL TO VIEW MEMBER DETAILS AND RESULTS FOR CONSULTATION-->
<form method="post" action="{$FormAction}" onsubmit="{$Onsubmit}" name="{$FormName}">
<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog" style="width:95%;">
        <div class="modal-content p-0 b-0">
            <div class="panel panel-color panel-danger">
                <div class="panel-heading">
                    <!--THE x BUTTON TO CLOSE THE MODAL POP UP OR MEMBER DETAILS POP UP-->
                    <button id="btnCloseConsultation" type="button" data-dismiss="modal"  style="float:right;"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <!--TITLE OF MODAL POP UP-->
                    <h4 style="font-weight:bold;font-size:20px;color:white;"><center>MEMBER DETAILS</center></h4>
                </div>
                <div class="container" style="width:100%;">
                    <div class="row"><br>
                        <div class="col-sm-12">
                            <div class="card-box" style="height:100%;">
                                <div class="row">
                                    <div class="form-horizontal" role="form">
                                        <div class="col-md-4" style="right:1%;">
                                            <div class="form-group">
                                                <!--MEMBER NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Member name:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="C_Membername" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--HEALTHCARD STATUS FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Healthcard Status:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="C_HealthcardStatus" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--PRINCIPAL NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Principal name:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="C_PrincipalName" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--MEMBER BALANCE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Balance:</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-4" style="right:2%;">
                                            <div class="form-group">
                                                <!--CARDNUMBER FIELD, THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Policy number:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="C_Cardnumber" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--BIRTHDATE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Birth date:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="C_Birthdate" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--EXPIRY FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Expiry:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="C_Expiry" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <!--CONTACT NUMBER FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Contact Number:</label>
                                                    <div class="col-md-9">
                                                        <input type="number" id="C_EcontactNumber" class="form-control" style="font-weight:bold;" readonly>
                                                    </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <!--COMPANY NAME FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Company Name:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="C_Companyname" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--MEMBER TYPE FIELD , THIS FIELD IS CONNECTED TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Member Type:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="C_MemberType" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--MAXIMUM COVERAGE BENEFIT FIELD , THIS FIELD IS CONNECT TO HOME.PHP-->
                                                <label class="col-md-3 control-label">Maximum Coverage Benefit:</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="C_Mcb" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <!--WAIVER-->
                                         <div class="row">
                                            <div class="col-md-12 form-group" style="">    
                                                <label class="col-md-1 control-label" style="font-weight:bold;font-size:15px;">WAIVER:</label>
                                                    <div class="col-md-11">
                                                        <input id="C_Waiver" type="textarea" class="form-control" style="font-weight:bold;font-size:15px;" readonly>
                                                    </div>
                                            </div>
                                        </div>
                                        {* <div class="row">
                                            <div class=" col-lg-12 col-sm-12 form-group">
                                                <label style="font-weight:bold;font-size:15px;">WAIVER:</label>
                                                    <input id="C_Waiver" type="textarea" class="form-control" style="font-weight:bold;font-size:15px;" readonly>
                                            </div>
                                        </div> *}
                                        <div align="right">
                                            <!--BUTTON FOR IVIEW IS CONNECTED TO VALUCARE-->
                                            <button class="btn btn-custom btn-danger btn-md" onclick=" window.open('https://www.valucarehealth.com/iview/details.php?id=' + document.getElementById('C_Cardnumber').value);"><i class="fa fa-wpforms" style="font-size:23px;"></i> i-View</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div align="center">
                            <!--TITLE (CONSULTATION) RESULTS-->
                            <font style="font-weight:bold;font-size:22px;">CONSULTATION</font>
                        </div>
                        <div class="col-sm-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="form-horizontal" role="form">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <!--HOSPITAL NAME FIELD , THIS FIELD IS THE RESULT OF CONSULTATION AND CONNECTED TO HOME.PHP-->
                                                <label class="col-md-2 control-label">Hospital Name:</label>
                                                <div class="col-md-10">
                                                    <input type="text" id="C_Hospitalname" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--DOCTOR NAME FIELD , THIS FIELD IS THE RESULT OF CONSULTATION AND CONNECTED TO HOME.PHP-->
                                                <label class="col-md-2 control-label">Doctor Name:</label>
                                                <div class="col-md-10">
                                                    <input type="text" id="C_Doctorname" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!--SPECIALIZATION , THIS FIELD IS THE RESULT OF CONSULTATION AND CONNECTED TO HOME.PHP-->
                                                <label class="col-md-2 control-label">Specialization:</label>
                                                <div class="col-md-10">
                                                    <input type="text" id="C_Specialization" class="form-control" style="font-weight:bold;" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <!--MAJOR COMPLAINT , THIS FIELD IS THE RESULT OF CONSULTATION AND CONNECTED TO HOME.PHP-->
                                                <label class="col-md-2 control-label">Chief Complaint:</label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control" id="C_Majorcomplaint" rows="6" style="resize:none;font-weight:bold;" maxlength="350" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div><hr>
                        </div>
                        <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-weight:bold;font-size:15px;">APPROVER NAME:</label>
                                            <input type="text" class="form-control" id="C_ApproverName" style="font-weight:bold;font-size:15px;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-weight:bold;font-size:15px;">APPROVAL CODE:</label>
                                            <input type="text" class="form-control" id="C_ApprovalCode" style="font-weight:bold;font-size:15px;" readonly>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>