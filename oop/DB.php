<?
class DB {
	
	private $DBCONN;
	public $cantidad;
	public $results = array();
	public $error;
	public $offset;
	public $limit;
	
	
	public function DB() {
		
	}
	
	
	public function update($table, $fields, $where) {
		$consulta = "UPDATE $table SET $fields WHERE $where";
		echo $consulta;
		
		$q = mysql_query($consulta);
		
	}
	
	public function delete($table, $where, $debug = false) {
		$consulta = "DELETE FROM $table WHERE $where";
		if ($debug) echo $consulta;
		
		$q = mysql_query($consulta);
		
	}
	
	
	public function select($table, $rows = '*', $where = null, $order = null, $offset = 0, $limit = 0, $debug = false)
    {
		$this->offset = $offset;
		$this->limit = $limit;
		
        
		$q = "SELECT SQL_CALC_FOUND_ROWS ".$rows.' FROM '.$table;
        if($where != null) {
            if (is_array($where)) $where = implode(' AND ', $where); // si es un array lo junta con AND
			$q .= ' WHERE '.$where;
		}
        if($order != null)
            $q .= ' ORDER BY '.$order;
		if ($offset != 0 or $limit != 0)
			$q .= " LIMIT $offset, $limit";
		
		
		
        $query = mysql_query($q) or die($q.mysql_error());
		
        if($query)
        {
            $this->cantidad = mysql_num_rows($query);
			$this->results = array();
			while ($r = mysql_fetch_assoc($query))
				$this->results[] = $r;	
			
			if ($debug) {
			echo "<pre> $q";
			print_r($this->results);
			echo "</pre>";
			}
            return $this;
        }
        else
        {
			$this->error = $q." --- ".mysql_error();
            return false;
        }
    }


	public function rawQuery($consulta, $debug = false) {
		$query = mysql_query($consulta) or die($q.mysql_error());
		
        if($query)
        {
            $this->cantidad = mysql_num_rows($query);
			$this->results = array();
			while ($r = mysql_fetch_assoc($query))
				$this->results[] = $r;	
			
			if ($debug) {
			echo "<pre> $consulta";
			print_r($this->results);
			echo "</pre>";
			}
            return $this;
        }
        else
        {
			$this->error = $q." --- ".mysql_error();
            return false;
        }
	}


	public function insert($table, $params, $devolver = null) {
		$firstKey = key($params);
		
		$keys = array();
		$values = array();
		if (!is_numeric($firstKey) and !is_array($params[$firstKey])) { // si es un array associativo unidimensional
			foreach ($params as $k => $v) {
				$keys[] = $k;
				$values[] = "'".Utils::escapar($v)."'";	
			}
			
		}
		$consulta = "INSERT INTO $table (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
		//echo $consulta;
		mysql_query($consulta) or die(mysql_error());
		return $this->lastInsert();
	}

	
	public function totalFoundRows() {
		$q = mysql_fetch_assoc(mysql_query("SELECT FOUND_ROWS() as found"));
		return $q[found];	
	}
	
	public function lastInsert() {
		$q = mysql_fetch_assoc(mysql_query('SELECT LAST_INSERT_ID() as lid'));
		return $q[lid];
		
	}
	
	
	
	private function escapar($t) {
		if (get_magic_quotes_gpc())
			$t = stripslashes($t);
		return mysql_real_escape_string($t);
	}
	
	
	
	public static function DBtoLinearArray($arr) {
		if (!count($arr)) return array();
		foreach ($arr as $e) {
					foreach ($e as $k => $v)
						$temp[] = $v;
				}	
		return $temp;
	}
	
	
	public static function DBtoAssocArray($arr) {
		if (!count($arr)) return array();
		foreach ($arr as $e) {
					reset($e);
					$k = $e[key($e)];
					end($e);
					//echo $k. key($e);
					$temp[$k] = $e[key($e)];
				
				}	
				//print_r($temp);
		return $temp;
	}
	
	
	
	
	public function navegacion () { //navegacion($cantidad, $limitederesults, $offset) {
		$cantidad = $this->totalFoundRows();
		$limitederesults = $this->limit;
		$offset = $this->offset;
		if ($cantidad > $limitederesults and $limitederesults > 0) {
		
			$limitescroll = 5;
			$maximo = false; // si hay que agreagr que sigue para adelante
			$minimo = false; // si hay que agreagr que sigue para atras
			$actual = ($offset / $limitederesults) + 1;
			
			$scroll = ceil($cantidad / $limitederesults);
			$response = "<div id='navegacion'>";
			for ($r = 1; $r <= $scroll; $r++) {
				$offset = ($r-1) * $limitederesults;
						
				if ($r == $actual) {
					$response .= "<span>$r</span>";
				}
				else if (($r - $actual <= $limitescroll) && ($r - $actual >= -$limitescroll)) {
					$response .= "<a href='javascript:iraOffset($offset)'>$r</a>";
				}
				
				else if ($r - $actual > $limitescroll) {
					$maximo = true;
					}
				else if ($r - $actual < -$limitescroll) {
						if (!$minimo) {
						$minimo = true;
						$response .= "<span><</span>";
						}
					}	
				
			}
			
			if ($maximo) $response .= "<span>></span>";
			$response.="</div>";
			echo $response;
		}


	}


}


?>