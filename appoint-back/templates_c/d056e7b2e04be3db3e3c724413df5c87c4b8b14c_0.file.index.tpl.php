<?php
/* Smarty version 3.1.34-dev-7, created on 2023-04-10 08:11:34
  from 'C:\Users\tina.xue\Documents\Tina\appoint-back\appoint-back\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6433a89691d245_22552651',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd056e7b2e04be3db3e3c724413df5c87c4b8b14c' => 
    array (
      0 => 'C:\\Users\\tina.xue\\Documents\\Tina\\appoint-back\\appoint-back\\templates\\index.tpl',
      1 => 1678438676,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:indexdesign.tpl' => 1,
    'file:indexheader.tpl' => 1,
  ),
),false)) {
function content_6433a89691d245_22552651 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<title>LiboBio Appointment System</title>
    <head><?php $_smarty_tpl->_subTemplateRender("file:indexdesign.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?></head>
    <body style="font-family:Microsoft JhengHei;">
        <?php $_smarty_tpl->_subTemplateRender("file:indexheader.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <!-- LOGIN START -->
        <div class="wrapper-page">
            <div class="card-box login">
                <div class="panel-heading" style="text-align:center">
                    <h2 class="text-center"><strong class="login" style="font-family:Microsoft JhengHei;">請登入</strong></h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal m-t-20" method="post" action="<?php echo $_smarty_tpl->tpl_vars['Form']->value;?>
">
                        <div class="form-group" >
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" name="<?php echo $_smarty_tpl->tpl_vars['Username']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['Username']->value;?>
" placeholder="帳號" value="<?php echo $_smarty_tpl->tpl_vars['Username_Value']->value;?>
" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" name="<?php echo $_smarty_tpl->tpl_vars['Password']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['Password']->value;?>
" placeholder="密碼" value="<?php echo $_smarty_tpl->tpl_vars['Password_Value']->value;?>
">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-40">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-block text-uppercase waves-effect waves-light btn-lg" name="<?php echo $_smarty_tpl->tpl_vars['Access']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['Access']->value;?>
" type="submit">
                                確定
                                </button>
                            </div>
                        </div>
                        <?php echo $_smarty_tpl->tpl_vars['Hiddenfield']->value;?>

                    </form>
                </div>
                </div>
        </div>
    </body>
</html>
<?php }
}
