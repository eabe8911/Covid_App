<!DOCTYPE html>
<html>
    <head>
        <!-- THIS IS THE CSS OF HOME.PHP-->
        {include file="homecss.tpl"}  
    </head>
    <body> 
        <form id="form1" name="form1" method="post" action="home.php">
            {$Hiddenfield1}{$Hiddenfield2}
        </form>

        <!--header.tpl THIS PAGE IS FOR HEADER OF HOME.PHP (LOGO AND AGENT NAME)--->
        <div class="container" style="width:100%;">
            <div class="card-box">
                {include file="header.tpl"}
                <div class="row">
                    <!---- 預約日期 ---->
                    <div class="col-md-5" style="padding:10px">
                        <label for="query_appoint_date" style="text-align:right;font-size:20px;">預約日期：</label>
                        <input type="text" id="query_appoint_date" style="font-weight:bold;font-size:20px;">
                        <button id="BtnQuery" class="btn btn-custom btn-info btn-md" style="font-size:18px"><i class="fa fa-search"></i> 查 詢 </button>
                    </div>
                    <!---- 查詢 ---->
                    <div class="col-md-3" style="float:right;">
                        <input type="search" class="form-control input-md" id="SearchTable" name="SearchTable" placeholder="Search" style="font-weight:bold;font-size:20px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!--THIS IS THE JQGRID TABLE ,AND THIS FIELD IS CONNECTED TO HOME.PHP-->
                        <table id="{$jqGrid}"></table>
                        <div id="{$jqGridPager}"></div>
                    </div>
                </div>
                {include file="footer.tpl"}
            </div>
        </div>
        <!--homejs.tpl IS JAVASCRIPT PAGE FOR HOME.PHP-->
        {include file="home_js.tpl"}
    </body>
</html>
{include file="jqgrid_js.tpl"}