<?php
require_once ("config.php");
require_once ("clsCatalogos.php"); 


class clsUsuario extends clsCatalogos{

	protected $id_usuario;
	protected $id_rol;
	protected $nom_rol;
	protected $login;
	protected $nom_area;
	protected $nombre_usr;
	protected $id_subdirecion;
	
	
	public function __construct($linkMysql=NULL){

		parent::__construct();

		$this->connect($linkMysql);
	}
	
	public function addUsuario($nombre_usr, $ap_pat_usr, $ap_mat_usr, $login, $passwd, $id_area, $id_rol, $vigente){
		
		$query = "".
		" INSERT INTO usuarios (nombre_usr, ap_pat_usr, ap_mat_usr, login, passwd, id_area, id_rol, vigente) VALUE ".
		" ('$nombre_usr', '$ap_pat_usr', '$ap_mat_usr', '$login', md5('$passwd'), $id_area, $id_rol, $vigente) ";
		
		$resp = $this->executeQueryMysql($query);

		if(!$resp){
			return false;
		}
		
		return true;
		
	}
	
	public function getUsuario($id_usuario){
		
		$query = "".
		" SELECT nombre_usr, ap_pat_usr, ap_mat_usr, login, id_area, id_rol, vigente ".
		" FROM usuarios WHERE id_usuario = $id_usuario ";
		
		$resp = $this->executeQueryMysql($query);

		if(!$resp){
			return false;
		}
		
		if(!$resp->num_rows){
			return (object)array("count" => 0, "usuario" => array());
		}
		
		$row = $resp->fetch_object();
		
		return (object)array("count" => 1, "usuario" => $row);
		
	}
	
	public function updUsuario($id_usuario, $lstParam=array()){
		
		$query = " UPDATE usuarios SET ";
		
		$sets = "";
		foreach($lstParam as $col => $val){
			switch($col){
				case "nombre_usr": $sets .= ", $col = '$val' "; break; 
				case "ap_pat_usr": $sets .= ", $col = '$val' "; break;
				case "ap_mat_usr": $sets .= ", $col = '$val' "; break;
				case "login": $sets .= ", $col = '$val' "; break;
				case "passwd": $sets .= ", $col = '$val' "; break;
				case "id_area": $sets .= ", $col = $val "; break;
				case "id_rol": $sets .= ", $col = $val "; break;
				case "vigente": $sets .= ", $col = $val "; break;
			}
			
		}
		
		if($sets == ""){
			return true;
		}
		
		$query .= substr($sets, 1). " WHERE id_usuario = $id_usuario ";
		//die($query);
		$resp = $this->executeQueryMysql($query);

		if(!$resp){
			return false;
		}
		
		return true;
		
	}
	
	public function existLogin($login){
		
		$query = " SELECT id_usuario, login FROM usuarios WHERe login = '$login'";
		
		$resp = $this->executeQueryMysql($query);

		if(!$resp){
			return false;
		}
		
		$num = $resp->num_rows;
		$resp->close();
		
		if($num!=0){
			return (object)array("count" => $num, "exist" => true);
		}
		
		return (object)array("count" => $num, "exist" => false);
	}
	
	
	public function getUserInfo($login){
		$this->connect();

		$query = "".
		" SELECT us.id_usuario, us.id_rol, rol.nom_rol ,us.login ,area.nom_area, ".
		" CONCAT(us.nombre_usr,' ',us.ap_pat_usr,' ',IFNULL(us.ap_mat_usr,'')) as nombre_usr, ".
		" area.id_subdireccion ".
		" FROM usuarios us, ct_rol rol, ct_area area ".
		" WHERE rol.id_rol = us.id_rol ".
		" AND us.id_area = area.id_area ".
		" AND us.login = '$login' ";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		if(!$resp->num_rows){
			$resp->close();
			return (object)array("count" => 0, "user" => false);
		}
					
		$usr = $resp->fetch_object();
		$this->id_usuario = $usr->id_usuario;
		$this->id_rol = $usr->id_rol;
		$this->nom_rol = $usr->nom_rol;
		$this->login = $usr->login;
		$this->nom_area = $usr->nom_area;
		$this->nombre_usr = $usr->nombre_usr;
		$this->id_subdirecion = $usr->id_subdireccion;
	
		$resp->close();
		
		return (object)array("count" => 0, "user" => $usr);
	
	}

	///////////////////////////////////////////////////////////////////////

	public function getUserInfo2($login){
		$this->connect();

		$query ="". 
		"SELECT id_solicitante, CONCAT(nombre,' ',ap_paterno,' ',IFNULL(ap_materno,'')) as nombre_usr , correo_e FROM solicitantes_pago WHERE correo_e = '$login'";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		if(!$resp->num_rows){
			$resp->close();
			return (object)array("count" => 0, "user" => false);
		}
					
		$usr = $resp->fetch_object();
		$this->id_solicitante = $usr->id_solicitante;
		$this->nombre_usr = $usr->nombre_usr;
		$this->correo_e = $usr->correo_e;
		
		$resp->close();
		
		return (object)array("count" => 0, "user" => $usr);
	
	}
	///////////////////////////////////////////////////////////////////////
		
	//checa
	public function isUserValid($login,$pwd){

		$query = "".

        "SELECT id_solicitante, correo_e, passwd, vigente FROM solicitantes_pago WHERE passwd=MD5('$pwd') AND correo_e = '$login'";

		$resp = $this->executeQueryMysql($query);

		if(!$resp){
			return false;
		}

		if($resp->num_rows == 0){
            $resp->close();
            return (object)array("count" => 0, "valid" => 0, "consulta"=>0);
        }

		$usr = $resp->fetch_object();

		$resp->close();

        /*
        if($usr->passwd != md5($pwd)){
			return (object)array("count" => 1, "valid" => 0, "dasdasd"=>1 );
		}
        */

        if($usr->vigente !=1){

            return (object)array("count" => 1, "valid" => 0, 'vigente' =>0, "consulta"=>1);
        }

		return (object)array("count" => 1, "valid" => 1, 'tipoUsuario' => 'U', "consulta"=>1);
		
	}
	
	
	public function search_usuarios($lstParam = array()){

		$query = "".
		" SELECT id_usuario, login, vigente, nom_usr, vgente, nombre_usr, ap_pat_usr, ap_mat_usr, ".
		" id_area, id_rol, nom_area, nom_sub, id_subdireccion, nom_rol FROM vw_usersy_info ";
		
		$where = "";
		foreach($lstParam as $col => $val){
		
			switch($col){
				case "id_usuario": $where.= "AND id_usuario = $val "; break;
				case "login": $where.= "AND login = $val "; break;
				case "vigente": $where.= "AND vigente = $val "; break;
				case "nom_usr": $where.= "AND nom_usr = $val "; break;
				case "vgente": $where.= "AND vgente = $val "; break;
				case "nombre_usr": $where.= "AND nombre_usr = $val "; break;
				case "ap_pat_usr": $where.= "AND ap_pat_usr = $val "; break;
				case "ap_mat_usr": $where.= "AND ap_mat_usr = $val "; break;
				case "id_area": $where.= "AND id_area = $val "; break;
				case "id_rol": $where.= "AND id_rol = $val "; break;
				case "nom_area": $where.= "AND nom_area = $val "; break;
				case "nom_sub": $where.= "AND nom_sub = $val "; break;
				case "id_subdireccion": $where.= "AND id_subdireccion = $val "; break;
				case "nom_rol": $where.= "AND nom_rol = $val "; break;
			}
		}
		
		$query = $where == "" ? $query : $query . " WHERE " . substr($where, 4);
			
		
		//die($query);
		$lstParam = (object)$lstParam;
		if(isset($lstParam->sidx)){
			//echo "dsds";
			if(($lstParam->sidx != "")&&(($lstParam->sord == "asc")||($lstParam->sord == "desc"))){
				$query .= " ORDER BY ".$lstParam->sidx." ".$lstParam->sord;
			}
		}

		//print_r($lstParam);
		if((isset($lstParam->limit))&&(isset($lstParam->start))){
			if(((is_numeric($lstParam->start))&&(is_numeric($lstParam->limit)))&&(($lstParam->start>=0)&&($lstParam->limit>=0))){
				$query .= " LIMIT ".$lstParam->start.",".$lstParam->limit;
			}
		}	
		
		//die($query);
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		
		if(isset($lstParam->getCount)){
			$num = $resp->num_rows;
			$resp->close();
			return (object)array("count" => $num, "usuarios" => array());
		}
		
		$j=0;
		$lst = array();
		while ($obj = $resp->fetch_object()){
			
			$lst[] = $obj;
			$j++;
		}
		
		$resp->close();
		return (object)array("count" => $j, "usuarios" => $lst);
	}

	
	/*
	 * Funcion que genera la lista de permisos de scripts que tiene asociado el usaurio de acuerdo al
	 * rol que tiene y la base dir url que tiene el proyecto
	 * 
	 * */
	
	public function getPermissionList($id_rol, $path_script){
		
		$query = "".
		" SELECT DISTINCT cts.nom_tarea ".
		" FROM rel_rol_tareas_sistema rrts, ct_tareas_sistema cts, rel_tareas_sistema_script rtss, ".
		" ct_script cs ".
		" WHERE rrts.id_tarea_sistema = cts.id_tarea_sistema ".
		" AND cts.id_tarea_sistema = rtss.id_tarea_sistema ".
		" AND rtss.id_script = cs.id_script ".
		" AND rrts.id_rol = $id_rol ".
		" AND cs.path_script = '$path_script' ".
		" ORDER BY cts.nom_tarea ASC ";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		if(!$resp->num_rows){
			$resp->close();
			return (object)array("count" => 0, "list" => false);
		}
		
		$i=0;
		$list = array();
		while($row = $resp->fetch_object()){
			$list[] = $row->nom_tarea;
			$i++;
		}
		
		$resp->close();
		
		return (object)array("count" => $i, "list" => $list);
	}
	
	/*
	 * Funcion que crea el menu de acuerdo al perfil y los permisos que tiene el rol del usuario
	 * 
	 * 
	 * */
	
	public function getElementosMenu($id_rol){
		$this->connect();
		
		$query = "".
		" SELECT cem.*, csc.path_script ".
		" FROM ct_elemento_menu cem LEFT JOIN ( ".
		" 	SELECT DISTINCT cs.id_script, cs.path_script ".
		" 	FROM rel_rol_tareas_sistema rrts, ct_tareas_sistema cts, rel_tareas_sistema_script rtss, ".
		" 	ct_script cs ".
		" 	WHERE rrts.id_tarea_sistema = cts.id_tarea_sistema ".
		" 	AND cts.id_tarea_sistema = rtss.id_tarea_sistema ".
		" 	AND rtss.id_script = cs.id_script ".
		" 	AND rrts.id_rol = $id_rol ".
		" ) csc ".
		" ON cem.id_script = csc.id_script ".
		" ORDER BY cem.id_elemento_menu, cem.orden";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		if(!$resp->num_rows){
			$resp->close();
			return (object)array("count" => 0, "menu" => false);
		}
		
		$i=0;
		$menu = array();
		while($row = $resp->fetch_object()){
			if(!is_null($row->path_script) || is_null($row->id_script)){
				if(is_null($row->id_elemento_menu_padre)){
					$menu[$row->id_elemento_menu] = $row;
				}else {
					$menu[$row->id_elemento_menu_padre]['children'][$row->id_elemento_menu] = $row;
				}
			}
			$i++;
		}
		
		$resp->close();
		return (object)array("count" => $i, "menu" => $menu);
	}
   	
}

?>