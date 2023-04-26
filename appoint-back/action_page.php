<?php


?>
<!DOCTYPE html>
<html>
<title>麗寶生醫</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<!--this is for jqGrid start-->
<link rel='stylesheet' type='text/css' href='http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css' />
<link rel='stylesheet' type='text/css' href='http://www.trirand.com/blog/jqgrid/themes/ui.jqgrid.css' />
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type='text/javascript' src='http://www.trirand.com/blog/jqgrid/js/jquery-ui-custom.min.js'></script>        
<script type='text/javascript' src='http://ds.maxcheng.tw/jqgrid/grid.locale-tw.js'></script>
<script type='text/javascript' src='http://www.trirand.com/blog/jqgrid/js/jquery.jqGrid.js'></script>
<script src="./JQG_GetStatistics.js?update=123" type='text/javascript'></script>
<!--jqGrid end--><style>
body,h1 {font-family: "Raleway", sans-serif}
body, html {height: 100%}
.bgimg {
    background-image: url('/w3images/parallax1.jpg');
    min-height: 100%;
    background-position: center;
    background-size: cover;
}
</style>
<body>

  <div class="bgimg w3-display-container w3-animate-opacity w3-text-white">
    <div class="w3-display-topleft w3-padding-large w3-xxxlarge">
      麗寶生醫
    </div>
    <div class="w3-display-bottomleft w3-padding-large">
      Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a>
    </div>
  </div>
  <div class="w3-container w3-light-grey w3-display-middle" style="height:90%">
      <h3 class="w3-animate-top">客戶預約資料查詢</h3>
      <table id="OverviewList" style="font-size: 100%"></table> 
      <p id="PageOverview"></p> 
  </div>

</body>
</html>