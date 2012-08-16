<p class="titulo_recuadro">Editar<img class="loader" src="img/loader.gif"></p>
    	
   
     
       <input type="hidden" id="id" name="id" value="{$r.id_cli}" />
        <input type="hidden" id="tipo" name="tipo" value="cliente" />
		<label>Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="{$r.nombre}" />
        <label>Contacto:</label>
        <input type="text" id="contacto" name="contacto" value="{$r.contacto}"/>
        <label>Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="{$r.direccion}"/>
        <label>Telefono:</label>
        <input type="text" id="telefono" name="telefono" value="{$r.telefono}"/>
        <label>Mail:</label>
        <input type="text" id="mail" name="mail" value="{$r.mail}"/>
        <label>CUIT / DNI:</label>
        <input type="text" id="dni_cuit" name="dni_cuit" value="{$r.dni_cuit}"/>
        <label>CBU:</label>
        <input type="text" id="cbu" name="cbu" value="{$r.cbu}"/>
     
        
        <a class="boton rojo" onClick="cerrar_ventana()">Descartar</a> 
        <a class="boton azul" onClick="guardar()">Guardar</a>