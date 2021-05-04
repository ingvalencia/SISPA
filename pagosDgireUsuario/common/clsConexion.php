<?php
require_once "config.php";
	
class clsConexion{

	protected $host;
	protected $pass;
	protected $user;
	protected $db;
	protected $port;

	protected $linkMysql;
	protected $lastQuery;
	protected $errorMsg;
	protected $result;
	//sybase
	protected $sybase_link;
	
	
	public function __construct($xhost = false, $xpass = false, $xuser = false, $xdb = false, $xport = false, $shost = false, $spass = false, $suser = false, $sdb = false){
		global $CONFIG;

		$this->host = $xhost ? $xhost : $CONFIG->mysql->host;
		$this->user = base64_decode($xpass ? $xpass : $CONFIG->mysql->user);
		$this->pass = base64_decode($xpass ? $xpass : $CONFIG->mysql->pass);
		$this->db = $xdb ? $xdb : $CONFIG->mysql->db;
		$this->port = $xport ? $xport : $CONFIG->mysql->port;

		//sybase 
		$this->host1 = $shost ? $shost : $CONFIG->sybase->host;
		$this->user1 = base64_decode($suser ? $suser : $CONFIG->sybase->user);
		$this->pass1 = base64_decode($spass ? $spass : $CONFIG->sybase->pass);
		$this->db1 = $sdb ? $sdb : $CONFIG->sybase->db;
		
	}

	/**
	* Conecta con la db, si recibe el link de conexion ya no se conecta con la db
	*
    * @param     obj			$xlinkMysql		  Objeto de conexion a mysql
    * @return    bool           				  true si la operacion fue exitosa, false de contrariose puedo conectar
    */
	public function connect($xlinkMysql = false, $linkSybase = NULL){

		if($xlinkMysql == NULL){
			
			if(!$this->connectMysql()){
				return false;
			}
		}else{ $this->linkMysql = $xlinkMysql;}

		if($linkSybase == NULL){
			
			if(!$this->connect_unamsi()){
				return false;
			}
		}else{$this->sybase_link = $linkSybase;	}

		return true;
	
	}
	
	/**
	* Verifica si existe un error en la llamada de mysql
	*
    * @return    bool           				  true si se presenta un error, false de lo contrario
    */
	protected function hasError(){
		
		//$this->errorMsg = $this->linkMysql->error;
		if ($this->linkMysql->connect_error) {
			$this->errorMsg = "Error de Conexi�n (".
							  $this->linkMysql->connect_errno . ') '.
							  $this->linkMysql->connect_error;
			return true;
		}
		
		return false;
		
	}
		
	
	/**
	* Conecta con MySQL
	*
    * @return    bool           				  true si se puedo conectar, false de lo contrario
    */
	private function connectMysql(){

		$this->linkMysql = new MySQLi($this->host, $this->user, $this->pass, $this->db, $this->port);

		if (!$this->linkMysql->set_charset("utf8")) {
			
		}

		return !$this->hasError();
	}
	
	/**
	* Cierra la conexion de mysql
	*
    * @return    bool           				  true
    */
	private function closeMysql(){
		$this->linkMysql->close();
		
		return true;
	}
	
	/**
	* Inicia la transaccion de mysql
	*
    * @return    bool           				  true si la operacion fue exitosa, false de contrario
    */
	public function startTransactionMysql(){
		$query = "START TRANSACTION";
		return $this->executeQueryMysql($query);
	}
	
	/**
	* Realiza un commit en msyql
	*
    * @return    bool           				  true si la operacion fue exitosa, false de contrario
    */
	public function commitMysql(){
		$query = "COMMIT";
		return $this->executeQueryMysql($query);
	}
	
	/**
	* REaliza un rollback en msyql
	*
    * @return    bool           				  true si la operacion fue exitosa, false de contrario
    */
	public function rollbackMysql(){
		$query = "ROLLBACK";
		return $this->executeQueryMysql($query);
	}
	
	/**
	* Ejecuta un query en la db de mysql
	*
    * @param     string		$query			Query a ejecutar
    * @return    resultset/bool           	resultset/true si la operacion fue exitosa, false de contrario
    */	
	public function executeQueryMysql($query){
		$this->lastQuery = $query;
		
		$this->result = $this->linkMysql->query($query);
		
		$this->errorMsg = "";
		if(!$this->result){
			//$this->hasError();
			$this->errorMsg = $this->linkMysql->error;
			return false;
		}
		
		
		return $this->result;
	}
	
	/**
	* Crea y ejecuta un update en mysql
	*
    * @param     string		$table			Nombre de la tabla que se actualizara
	* @param     array		$fields			Array asociativo con los datos a actualziar de la forma 
	*										('nombre_columna' => 'valor_columna', ...)
	* @param     array		$conditions		Array con las condiciones para la actualizacion de la forma
	*										('nombre_columna = 12', "combre_columna in (1,2,3)", ...)
    * @return    bool           			true si se pudo realizar la operacion, false de contrario
    */	
	protected function genericUpdate($table=false, $fields=array(), $conditions=array()){

		if(!$table){ return false; }
		
		if(!count($fields)){ return false; }
		
		$query = " UPDATE $table SET ";

		$sets = "";
		foreach($fields as $col => $val){
			$sets .= ", $col = '$val' ";
		}
		
		$where = "";
		foreach($conditions as $condition){
			$where .= "AND $condition ";
		}

		$query = $query . substr($sets, 1) . " WHERE " . substr($where, 4);

		return $this->executeQueryMysql($query);
	
	}

	/**
	* Crea y ejecuta un insert en mysql
	*
    * @param     string		$table			Nombre de la tabla donde se insertaran los datos
	* @param     array		$data			Array asociativo con los datos a insertar en la tabla de la forma 
	*										('nombre_columna' => 'valor_columna', ...)
	* @return    bool			           	true si la operacion fue exitosa, false de contrario
    */	
	protected function genericInsert($table = false, $data=array()){
		
		if(!$table){ return false; }
		
		if(!count($data)){ return false; }
		
		
		$fields = "";
		$values = "";
		
		foreach($data as $col => $val){
			
			$fields.= ", $col";
			$values.= ", '$val'";
		}
		
		$query = " INSERT INTO $table (".substr($fields, 1). ") VALUES (".substr($values, 1).")";
		
		return $this->executeQueryMysql($query);
	
	}
	
	/**
	* Crea y ejecuta un delete en mysql
	*
    * @param     string		$table			Nombre de la tabla donde se borraran los datos
	* @param     array		$data			Array con las erstricciones para borrar los datos 
	*										('nombre_columna = 12', "combre_columna in (1,2,3)", ...)
	* @return    bool			           	true si la operacion fue exitosa, false de contrario
    */	
	protected function genericDelete($table=false, $conditions=array()){
		
		if(!$table){ return false; }
		
		$query = " DELETE FROM $table_name ";
		
		$where = "";
		foreach($conditions as $condition){
			$where .= "AND $condition ";
		}
		$query = $query . " WHERE " . substr($where, 4);
		
		return $this->executeQueryMysql($query);
	}

	public function setHost($xhost){
		$this->host = $xhost;
	}
	
	public function setPass($xpass){
		$this->pass = $xpass;
	}
	
	public function setUser($xuser){
		$this->user = $xuser;
	}
	
	public function setDB($xdb){
		$this->db = $xdb;
	}
	
	public function setPort($xport){
		$this->port = $xport;
	}
	
	public function getLinkMysql(){
		return $this->linkMysql;
	}
	
	public function getLastQuery(){
		return $this->lastQuery;
	}
	
	public function getResult(){
		return $this->result;
	}
	
	public function getError(){
		return $this->errorMsg;
	}


	//SYBASE
	public function connect_unamsi(){
	
		self::__construct();
		
		try{
				
			$this->sybase_link = new PDO("dblib:host=$this->host1;dbname=$this->db1;charset=UTF8", "$this->user1", "$this->pass1");
			
			return true;
		}catch(PDOException $e){
			
			echo 'Error de conexión: ' . $e->getMessage();
			
			exit;
		}

	}

	///SYBASE 
	//Ejecuta el query en db de sybase unamsi
	protected function executeQueryUnamsi($query){
		try {
			//$this->result = $this->sybase_link->query($query)->fetchAll();
			$this->result = $this->sybase_link->query($query);
		}
		catch( PDOException $e ) {
			print_r($e->getMessage( )); exit;
		}
		return $this->result;

	}

	
}

?>
