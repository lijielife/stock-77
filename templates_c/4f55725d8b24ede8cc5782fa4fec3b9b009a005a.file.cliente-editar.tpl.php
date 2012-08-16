<?php /* Smarty version Smarty-3.1.11, created on 2012-08-12 22:19:29
         compiled from ".\templates\cliente-editar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:311050280fd1ac8064-90762403%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4f55725d8b24ede8cc5782fa4fec3b9b009a005a' => 
    array (
      0 => '.\\templates\\cliente-editar.tpl',
      1 => 1344802165,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '311050280fd1ac8064-90762403',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'r' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50280fd1c39907_68284703',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50280fd1c39907_68284703')) {function content_50280fd1c39907_68284703($_smarty_tpl) {?><p class="titulo_recuadro">Editar<img class="loader" src="img/loader.gif"></p>
    	
   
     
       <input type="hidden" id="id" name="id" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['id_cli'];?>
" />
        <input type="hidden" id="tipo" name="tipo" value="cliente" />
		<label>Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['nombre'];?>
" />
        <label>Contacto:</label>
        <input type="text" id="contacto" name="contacto" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['contacto'];?>
"/>
        <label>Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['direccion'];?>
"/>
        <label>Telefono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['telefono'];?>
"/>
        <label>Mail:</label>
        <input type="text" id="mail" name="mail" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['mail'];?>
"/>
        <label>CUIT / DNI:</label>
        <input type="text" id="dni_cuit" name="dni_cuit" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['dni_cuit'];?>
"/>
        <label>CBU:</label>
        <input type="text" id="cbu" name="cbu" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['cbu'];?>
"/>
     
        
        <a class="boton rojo" onClick="cerrar_ventana()">Descartar</a> 
        <a class="boton azul" onClick="guardar()">Guardar</a><?php }} ?>