<?php

require_once ("config.php");

#Inicia clase Log

class clsLog {

	# ATTRIBUTES

	# PRIVATE variables may only be accessed by the class that defines them
	private $logfile;
	private $client_ip;


	# METHODS

	# Constructor method
	############################################################################################################
	public function __construct($logfile){

		# Parameters initialization for new objects of this class

        global $CONFIG;

        $Dirfile= $CONFIG->rutas->DirLog;

        $this->logfile = $Dirfile.$logfile;

		$this->client_ip = $_SERVER['REMOTE_ADDR'];

		# Checking if file already exist

		$log_exist = file_exists($this->logfile);

	}
	############################################################################################################



	############################################################################################################
	public function getDateLog(){

		$months = array(
			'01' => 'Ene',	'02' => 'Feb',	'03' => 'Mar',	'04' => 'Abr',
			'05' => 'May',	'06' => 'Jun',	'07' => 'Jul',	'08' => 'Ago',
			'09' => 'Sep',	'10' => 'Oct',	'11' => 'Nov',	'12' => 'Dic'
		);

		$day = date('d');
		$month = date('m');
		$month = $months[$month];
		$year = date('Y');

		$date = $day.'-'.$month.'-'.$year;

		return $date;
	}
	############################################################################################################



	############################################################################################################
	public function readLog(){

		# Open file in write mode

		$log_handler = fopen($this->logfile,'r');
		$log_size = filesize($this->logfile);
		$log_content = fread($log_handler,$log_size);

		# Formatting the content. I'll transform log's newlines into <br/> tags (for HTML format)

		$log_content = nl2br($log_content);

		return $log_content;
	}
	############################################################################################################



	############################################################################################################
	public function writeLog($log_message){

	    $logfile = $this->logfile;
		$client_ip = $this->client_ip;
		$date = $this->getDateLog();
		$time = date('H:i:s');
		$log_startline = '['.$date.' '.$time.' '.$client_ip.']';

		# Open file in write mode. If file doesn't exist, it creates it ('a')

		$log_handler = fopen($logfile,'a');

		# Write entire log message and go to the next line of file (characters chr(13).chr(10))

		$write = fwrite($log_handler,$log_startline.$log_message.chr(13).chr(10));

		fclose($log_handler);


		return $write;
	}
	############################################################################################################



}


?>