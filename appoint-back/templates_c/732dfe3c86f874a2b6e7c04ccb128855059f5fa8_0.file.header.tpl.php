<?php
/* Smarty version 3.1.34-dev-7, created on 2023-04-12 11:03:30
  from 'C:\Users\tina.xue\Documents\Tina\appoint-back\appoint-back\templates\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_643673e2b1f058_99183584',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '732dfe3c86f874a2b6e7c04ccb128855059f5fa8' => 
    array (
      0 => 'C:\\Users\\tina.xue\\Documents\\Tina\\appoint-back\\appoint-back\\templates\\header.tpl',
      1 => 1681290208,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_643673e2b1f058_99183584 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="row">
    <div class="col-md-8 text-center">
            <h2>採檢預約資料維護</h2>
    </div>
    <div class="col-md-2 text-right align-self-end" style="float:right;">
        <span class="label label-primary label-block" style="text-align:right;font-size:16px;"><i class="far fa-user"></i> <?php echo $_smarty_tpl->tpl_vars['UserName']->value;?>
 您好</span>
        <button class="btn btn-custom btn-info btn-md" onclick="NewURL();">
        <i class="fa fa-sign-out-alt"></i> 登 出</button>
    </div>
</div><?php }
}
