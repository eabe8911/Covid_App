<?php
/* Smarty version 3.1.34-dev-7, created on 2023-03-24 09:03:38
  from 'C:\Users\asoma\appoint-back\appoint-back\templates\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_641d595ad55f40_53595263',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64ebd7c451e7ccd766f2741a98653c4c571daf14' => 
    array (
      0 => 'C:\\Users\\asoma\\appoint-back\\appoint-back\\templates\\header.tpl',
      1 => 1672730690,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_641d595ad55f40_53595263 (Smarty_Internal_Template $_smarty_tpl) {
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
