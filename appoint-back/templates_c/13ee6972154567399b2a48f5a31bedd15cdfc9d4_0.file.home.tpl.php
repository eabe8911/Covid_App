<?php
/* Smarty version 3.1.34-dev-7, created on 2023-04-13 09:29:43
  from 'C:\Users\tina.xue\Documents\Tina\appoint-back\appoint-back\templates\home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6437af670edde5_43912787',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '13ee6972154567399b2a48f5a31bedd15cdfc9d4' => 
    array (
      0 => 'C:\\Users\\tina.xue\\Documents\\Tina\\appoint-back\\appoint-back\\templates\\home.tpl',
      1 => 1681370980,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:homecss.tpl' => 1,
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
    'file:home_js.tpl' => 1,
    'file:jqgrid_js.tpl' => 1,
  ),
),false)) {
function content_6437af670edde5_43912787 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
    <head>
        <!-- THIS IS THE CSS OF HOME.PHP-->
        <?php $_smarty_tpl->_subTemplateRender("file:homecss.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>  
    </head>
    <body> 
        <form id="form1" name="form1" method="post" action="home.php">
            <?php echo $_smarty_tpl->tpl_vars['Hiddenfield1']->value;
echo $_smarty_tpl->tpl_vars['Hiddenfield2']->value;?>

        </form>

        <!--header.tpl THIS PAGE IS FOR HEADER OF HOME.PHP (LOGO AND AGENT NAME)--->
        <div class="container" style="width:100%;">
            <div class="card-box">
                <?php $_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
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
                        <table id="<?php echo $_smarty_tpl->tpl_vars['jqGrid']->value;?>
"></table>
                        <div id="<?php echo $_smarty_tpl->tpl_vars['jqGridPager']->value;?>
"></div>
                    </div>
                </div>
                <?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            </div>
        </div>
        <!--homejs.tpl IS JAVASCRIPT PAGE FOR HOME.PHP-->
        <?php $_smarty_tpl->_subTemplateRender("file:home_js.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    </body>
</html>
<?php $_smarty_tpl->_subTemplateRender("file:jqgrid_js.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
