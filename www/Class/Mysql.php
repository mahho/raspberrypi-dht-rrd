<?php
//    include_once("Logger.php");

    class Mysql {
	private $host = "localhost";
	private $user = "temp";
        private $password = "jajwogUrim3";
        private $db = "temperatures";
        private $logger;
	private $pdo;

	
        public function __construct() {
//	    $this->logger = new Logger();
//	    $this->logger->log("Constructor " .get_class($this));
	    try {
		$this->pdo = new PDO('mysql:host='.$this->host.';port=3306;dbname='.$this->db, $this->user, $this->password);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//		$this->logger->log("mysql: Connection established");
	    } catch(PDOEception $e) {
//		$this->logger->log("mysql: Connection failed " . $e->getMessage());
	    }
	}
	
	public function query($query, $data = null) {
	    try {
		$q = $this->pdo->prepare($query);
		//$this->logger->log("Executing innoDB query: $query");
    		$q->execute($data);
		return $q->fetchAll();
	    } catch(PDOException $e) {
//		$this->logger->log("innoDB query failed: " . $e->getMessage());
	    }
	}
    }
?>


