<!--THIS IS THE VIEWPORT AND META OF CUSTOMER CARE / HOME.PHP-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="LiboBio">
<meta name="author" content="LiboBio">
<!--FAVICON AND TITLE OF WEBSITE-->
<link rel="shortcut icon" href="">
<title>麗寶生醫</title>
<!--DESIGN OR CSS OF HOME.PHP-->
<link href="style/CSS_home.css" rel="stylesheet" type="text/css" />
<link href="style/fontawesome5.0.13/web-fonts-with-css/css/fontawesome-all00.css" rel="stylesheet" type="text/css" />
<link href="style/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="style/icons.css" rel="stylesheet" type="text/css" />   

<script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
<script src="assets/plugins/jquery-ui/jquery-ui.js"></script>
<script src="js/jquery.validate.min.js"></script>

<!--THIS JQUERY AND CSS IS FOR JQGRID TABLE TO MAKE IT RESPONSIVE OR BOOTSTRAP-->

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/css/ui.jqgrid.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/i18n/grid.locale-tw.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/jquery.jqGrid.min.js"></script>




<script>
/**THIS SCRIPT IS FOR JQGRID RESPONSIVE TABLE OR BOOTSTRAP**/
    $.jgrid.defaults.width = 780;
    $.jgrid.defaults.responsive = true;
    $.jgrid.defaults.styleUI = 'Bootstrap';
</script>
<style>
  body {
    background: #ebeff2;
    font-family:Microsoft JhengHei, 'Noto Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    /* font-size:12px; */
    margin: 0;
    overflow-x: hidden;
    padding-bottom: 65px;
    color: #000000;
  }
  html {
    position: relative;
    min-height: 100%;
    background: #ebeff2;
  }

  /* Text colors */
  .text-custom {
    color: #5fbeaa;
  }
  .text-white {
    color: #ffffff;
  }
  .text-danger {
    color: #810606;
      }
  .text-muted {
    color: #98a6ad;
  }
  .text-primary {
    color: #5d9cec;
  }
  .text-warning {
    color: #ffbd4a;
  }
  .text-success {
  color: #81c868;
  }
  .text-info {
  color: #34d3eb;
  }
  .text-inverse {
  color: #4c5667;
  }
  .text-pink {
  color: #fb6d9d;
  }
  .text-purple {
  color: #7266ba;
  }
  .text-dark {
  color: #797979 !important;
  }

    /* Panels */
  .panel {
    border: none;
    margin-bottom: 20px;
  }
  .panel .panel-body {
    padding: 20px;
  }
  .panel .panel-body p {
    margin: 0px;
  }
  .panel .panel-body p + p {
    margin-top: 15px;
  }
  .panel-heading {
    border: none !important;
    padding: 10px 20px;
  }
  .panel-default > .panel-heading {
    background-color: #f4f8fb;
    border-bottom: none;
    color: #000000;
  }

  .panel-color .panel-title {
    color: #ffffff;
  }

  .panel-danger > .panel-heading {
    background-color: #f05050;
  }


  /*  Checkbox and Radios*/
  .checkbox {
    padding-left: 20px;
  }
  .checkbox label {
    display: inline-block;
    padding-left: 5px;
    position: relative;
  }
  .checkbox label::before {
    -o-transition: 0.3s ease-in-out;
    -webkit-transition: 0.3s ease-in-out;
    background-color: #ffffff;
    border-radius: 3px;
    border: 1px solid #cccccc;
    content: "";
    display: inline-block;
    height: 17px;
    left: 0;
    margin-left: -20px;
    position: absolute;
    transition: 0.3s ease-in-out;
    width: 17px;
    outline: none !important;
  }
  .checkbox label::after {
    color: #555555;
    display: inline-block;
    font-size: 11px;
    height: 16px;
    left: 0;
    margin-left: -20px;
    padding-left: 3px;
    padding-top: 1px;
    position: absolute;
    top: 0;
    width: 16px;
  }
  .checkbox input[type="checkbox"] {
    cursor: pointer;
    opacity: 0;
    z-index: 1;
    outline: none !important;
  }
  .checkbox input[type="checkbox"]:disabled + label {
    opacity: 0.65;
  }
  .checkbox input[type="checkbox"]:focus + label::before {
    outline-offset: -2px;
    outline: none;
    outline: thin dotted;
  }
  .checkbox input[type="checkbox"]:checked + label::after {
    content: "\f00c";
    font-family: 'FontAwesome';
  }
  .checkbox input[type="checkbox"]:disabled + label::before {
    background-color: #eeeeee;
    cursor: not-allowed;
  }
  
  .checkbox-danger input[type="checkbox"]:checked + label::before {
    background-color: #f05050;
    border-color: #f05050;
  }
  .checkbox-danger input[type="checkbox"]:checked + label::after {
    color: #ffffff;
  }
  
  .checkbox-success input[type="checkbox"]:checked + label::before {
    background-color: #81c868;
    border-color: #81c868;
  }
  .checkbox-success input[type="checkbox"]:checked + label::after {
    color: #ffffff;
  }
  
  /* =============
    Bootstrap-custom
  ============= */
  .row {
    margin-right: -5px;
    margin-left: -5px;
  }
  .col-lg-1,
  .col-lg-10,
  .col-lg-11,
  .col-lg-12,
  .col-lg-2,
  .col-lg-3,
  .col-lg-4,
  .col-lg-5,
  .col-lg-6,
  .col-lg-7,
  .col-lg-8,
  .col-lg-9,
  .col-md-1,
  .col-md-10,
  .col-md-11,
  .col-md-12,
  .col-md-2,
  .col-md-3,
  .col-md-4,
  .col-md-5,
  .col-md-6,
  .col-md-7,
  .col-md-8,
  .col-md-9,
  .col-sm-1,
  .col-sm-10,
  .col-sm-11,
  .col-sm-12,
  .col-sm-2,
  .col-sm-3,
  .col-sm-4,
  .col-sm-5,
  .col-sm-6,
  .col-sm-7,
  .col-sm-8,
  .col-sm-9,
  .col-xs-1,
  .col-xs-10,
  .col-xs-11,
  .col-xs-12,
  .col-xs-2,
  .col-xs-3,
  .col-xs-4,
  .col-xs-5,
  .col-xs-6,
  .col-xs-7,
  .col-xs-8,
  .col-xs-9 {
    padding-left: 5px;
    padding-right: 5px;
  }
  .breadcrumb {
    background-color: transparent;
    margin-bottom: 15px;
    padding-top: 10px;
    padding-left: 0px;
  }

  /* Form components */
  textarea.form-control {
    min-height: 90px;
  }
  .form-control {
    background-color: #FFFFFF;
    border: 1px solid #E3E3E3;
    border-radius: 4px;
    color: #565656;
    padding: 7px 12px;
    height: 38px;
    max-width: 100%;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-transition: all 300ms linear;
    -moz-transition: all 300ms linear;
    -o-transition: all 300ms linear;
    -ms-transition: all 300ms linear;
    transition: all 300ms linear;
  }
  .form-control:focus {
    background-color: #FFFFFF;
    border: 1px solid #AAAAAA;
    -webkit-box-shadow: none;
    box-shadow: none;
    outline: 0 !important;
    color: #333333;
  }

  .form-horizontal .form-group {
    margin-left: -10px;
    margin-right: -10px;
  }


</style>
<style>
  .ui-jqgrid .ui-jqgrid-hdiv {
    margin-bootom:10px;
  }

  .ui-jqgrid tr.ui-search-toolbar th {
  font-size:12px;
  height:40px;
  }

  .ui-jqgrid .ui-jqgrid-pager { 
    height: 50px; 
} 

  form label {
    display: inline-block;
    width: 100px;
  }

  form div {
    /* margin-bottom: 10px; */
  }


  .error {
    color: red;
    margin-left: 5px;
  }


  label.error {
    display: inline;
  }
</style>
