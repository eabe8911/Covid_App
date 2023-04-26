<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>jqGrid Example</title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/tonytomov/jqGrid@4.6.0/css/ui.jqgrid.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/tonytomov/jqGrid@4.6.0/js/jquery.jqGrid.min.js"></script>
</head>

<body>
    <table id="jqGrid"></table>
    <div id="jqGridPager"></div>
</body>

<script>
    $(document).ready(function () {
        $("#jqGrid").jqGrid({
            url: 'GetTodayMemberList.php',
            datatype: 'json',
            mtype: "GET",
            colModel: [
                { label: '採檢編號', name: 'sampleid2'},
            ],
            loadonce: true,
            viewrecords: true,
        });
    });
</script>
</body>

</html>