<?

class Items {
	
	public $type;
	protected $smarty;
	protected $DB;
	protected $template;
	//protected $opParent = array('offset' => 0, 'limit' => 20, where => array());
	protected $op = array();
	
	function __construct() {
		$this->smarty = new Smarty();
		$this->DB = new DB();
		$this->template = $this->type.'-admin.tpl'; 
	}


	public function listar($where, $offset = 0, $limit = 20, $encabezado = true, $template = null) {
		
		
		if (count($where)) $this->op[where] = array_merge($this->op[where], $where);
		//print_r($this->op);
		
		$q = $this->DB->select($this->op[table], $this->op[fields], $this->op[where], "nombre ASC", $offset, $limit, false);
		$datos = $q->results;
		
		if ($template != 'json') {
			$this->smarty->assign('encabezado', $encabezado);	
			$this->smarty->assign('datos', $datos);
			//$this->smarty->debugging = true;
			$this->display($template);
		}
		else echo json_encode($datos);
	}
	
	public function update($params, $where) {
		$fields = implode(', ', $params);
		$where = $this->op[fieldUpdate]." = ".$where;
		if (empty($this->op[tableUpdate])) $this->op[tableUpdate] = $this->op[table];
		$this->DB->update($this->op[tableUpdate], $fields, $where);
			
	}
	
	public function delete ($where) {
		$where = $this->op[fieldUpdate]." = ".$where;	
		if (empty($this->op[tableUpdate])) $this->op[tableUpdate] = $this->op[table];
		$this->DB->delete($this->op[tableUpdate], $where);
	}
	
	
	public function editar($id) {
		
		if ($id != 0) {
			
			$datos = $this->DB->select($this->op[table], "*", $this->op[fieldUpdate]." = $id");	
			$this->smarty->assign('r', $datos->results[0]);
		}
		
		$this->template = $this->type."-editar.tpl";
		//$this->smarty->debugging = true;
		$this->display();
	}
	
	
	
	public function insert($params) {
		$q = $this->DB->insert($this->op[table], $params);	
		
		if (is_numeric($q)) $this->listar( array($this->op[fieldUpdate]." = $q"), 0, 1, false);
	}
	
	
	public function display($template = null) {
		if (!$template) $template = $this->template; 
		
		
		$this->smarty->display($template);
		$this->DB->navegacion();
		
	}
	
	
	public function search($term) {
		$terms = explode(" ", $term);
		foreach ($terms as $e) 
			$wheres[] = "nombre LIKE '%$e%'";
		
		$this->listar($wheres, 0, 10, true, 'json');
	
	}
	

}


class Categoria extends Items {
	
	protected $op = array(table => 'categorias',
				 			   fields => 'id_cat, nombre',
							   where => array(),
							   fieldUpdate => "id_cat"
							   );
	
	public function __construct() {
		//$this->op = array_merge($this->opParent, $this->op);
		$this->type = 'categoria';
		parent::__construct();
	}
	
	public function editar($id, $parent) {
		
		$this->smarty->assign('parent', $parent);
		parent::editar($id);	
	}
	
	public function delete ($where) {
		//$where = $this->op[fieldUpdate]." = ".$where;	
		$borrar = array($where);
		$temp = new DB();
		$temp->select('categorias', 'id_cat', "parent = $where");
		foreach ($temp->results as $k)
			$borrar[] = $k[id_cat];
		$borrar = implode(', ', $borrar);	
		$temp->delete($this->op[table], "id_cat IN ($borrar) OR parent IN ($borrar)", true);
	}

}


class Proveedor extends Items {
	
	protected $op = array(table => 'clientes',
				 			   fields => 'id_cli, nombre',
							   where => array("tipo = 'proveedor'"),
							   fieldUpdate => "id_cli"
							   );
	
	public function __construct() {
		//$this->op = array_merge($this->opParent, $this->op);
		$this->type = 'proveedor';
		parent::__construct();
	}
	
	public function insert($params) {
		$params[tipo] = "proveedor";
		parent::insert($params);	
	}

	public function subirPorcentaje($id, $nombre) {
		$this->smarty->assign('id', $id);
		$this->smarty->assign('nombre', $nombre);
		$this->smarty->display('subir-porcentaje.tpl');	
	}
	
	public function subirAhora($id, $value) {
		if (!is_numeric($value)) return false;
		$value = 1 + $value / 100;
		$this->DB->update('materias_primas', "valor = (valor * $value)", "id_cli = $id");
		
		$items = new Materia();
		$items->actualizarPrecios(0, $id);
			
	}

}


class Cliente extends Items {
	
	protected $op = array(table => 'clientes',
				 			   fields => 'id_cli, nombre',
							   where => array("tipo = 'cliente'"),
							   fieldUpdate => "id_cli"
							   );
	
	public function __construct() {
		//$this->op = array_merge($this->opParent, $this->op);
		$this->type = 'cliente';
		parent::__construct();
	}

	public function insert($params) {
		$params[tipo] = "cliente";
		parent::insert($params);	
	}

}



class Materia extends Items {
	
	protected $op = array(table => 'materias_primas',
							where => array(),
				 			   fields => 'id_mat, nombre, valor, id_cli, unidad, stock, stock_minimo',
							   fieldUpdate => "id_mat"
							   
							   );
							   
	
	public function __construct() {
		//$this->op = array_merge($this->opParent, $this->op);
		$this->type = 'materia';
		parent::__construct();
	}



	public function listar($where, $offset = 0, $limit = 20, $encabezado = true, $template = null) {
			$this->generarUnidadesYProveedores();
			parent::listar($where, $offset, $limit, $encabezado, $template);
	}
	
	public function editar($id) {
		$this->generarUnidadesYProveedores();
		parent::editar($id);
	}
	
	public function search($term) {
		$this->op[fields] = 'id_mat as id, nombre as value, unidad, valor';
		parent::search($term);	
	}
	
	private function generarUnidadesYProveedores() {
			$tem = new DB();
			$temp = $tem->select('clientes', 'id_cli, nombre', "tipo = 'proveedor'");
			$proveedores = $tem->DBtoAssocArray($temp->results);
			$inicial = array(0=> 'Seleccione proveedor'); //agregae l x defecto	
			$proveedores = $inicial + $proveedores;
			
			
			$this->smarty->assign('proveedores', $proveedores);
			$this->smarty->assign('unidades', Config::$UNIDADES);
	}
	
	public function update($params, $id, $actualizarPrecios) {
		parent::update($params, $id);
		print_r($params);
		if ($actualizarPrecios) {
			$this->actualizarPrecios($id, 0);	
		}
	}
	
	public function actualizarPrecios($id_mat, $id_prov) {
		echo "actiualidlxl";
		//if (is_array($id_mat)) $id_mat = implode(', ', $id_mat); //si es un array de materias las serializa
		if ($id_mat != 0) //por materia
			$consulta = "SELECT id_art, id_mat, cantidad, materias_primas.valor, auto_publico, auto_mayor, margen_recomendado, margen_mayor, datos_producto.valor, valor_mayor,
			SUM((materias_primas.valor * cantidad)) as costo_total
			FROM materias_producto 
			LEFT JOIN materias_primas USING (id_mat) 
			LEFT JOIN datos_producto USING (id_art)
			WHERE id_art IN 
			(SELECT id_art FROM materias_producto WHERE id_mat IN ($id_mat))  
			GROUP BY id_art";
		elseif (!empty($id_prov)) //por proveedor
			$consulta = "SELECT id_art, id_mat, cantidad, materias_primas.valor, auto_publico, auto_mayor, margen_recomendado, margen_mayor, datos_producto.valor, valor_mayor, 
			SUM((materias_primas.valor * cantidad)) as costo_total
			FROM materias_producto 
			LEFT JOIN materias_primas USING (id_mat) 
			LEFT JOIN datos_producto USING (id_art)
			WHERE id_art IN 
			(SELECT id_art FROM materias_producto LEFT JOIN materias_primas USING (id_mat) WHERE id_cli IN ($id_prov))  
			GROUP BY id_art";
		
		$this->DB->rawQuery($consulta); // toma los datos para actualizar de cada producrto que tenga esas materias o ese proveedor
		
		
		
		foreach ($this->DB->results as $e) 
			if (!empty($e[id_art])) {
				$campos = array($e[id_art],  $e[costo_total]); //prestablece el id y cosoto
				
				if ($e[auto_publico]) { //si es automtico el precio al publico
 					$valor_recomendado = $e[costo_total] * (1 + $e[margen_recomendado] / 100);	// lo calcula
					$campos[] = $valor_recomendado;
				}
				else $campos[] = $e[valor]; //sino el que ya esta
				
				if ($e[auto_mayor]) { //si es auto el rpoecio al pro mayor
					$valor_mayor = $e[costo_total] * (1 + $e[margen_mayor] / 100);	 //lo calcula y lo pneo en una array
					$campos[] = $valor_mayor;
				}
				else $campos[] = $e[valor_mayor];
				
				
				$registros[] = "(".implode(', ', $campos).")";
				
			}
		$query = "INSERT INTO datos_producto (id_art, valor_costo, valor, valor_mayor) VALUES ".implode(', ', $registros)." ON DUPLICATE KEY UPDATE valor_costo = VALUES(valor_costo), valor = VALUES(valor), valor_mayor = VALUES(valor_mayor)";	
		echo $query;
		mysql_query($query);	
		
	} //actualizar precios

} //Materia clase






class Producto extends Items {
	
	protected $op = array(table => 'articulo LEFT JOIN datos_producto USING (id_art) LEFT JOIN categorias_articulo USING (id_art)',
							where => array(),
						  fields => 'nombre, articulo.id_art, codigo, valor, valor_mayor, stock, stock_minimo, auto_publico, auto_mayor',
						  tableUpdate => 'datos_producto',
						  fieldUpdate => "id_art");
							   
	
	public function __construct() {
		//$this->op = array_merge($this->opParent, $this->op);
		$this->type = 'producto';
		parent::__construct();
	}
	
	
	public function delete($where) {
		$this->op[tableUpdate] = "articulo";
		parent::delete($where);	
	}
	
	
	public function open($id) {
		$this->op[fields] = "articulo.*, datos_producto.*, GROUP_CONCAT(categorias_articulo.id_cat SEPARATOR '*') AS categorias";
		if (!empty($id)) $this->op[where][] = "id_art = $id";
		
		$this->DB->select($this->op[table], $this->op[fields], $this->op[where], null, 0, 1, false);
		return $this->DB->results;	
	}
	

}
	

?>