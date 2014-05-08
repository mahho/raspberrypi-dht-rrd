<?php
    include_once ("Mysql.php");

    class TemperatureStats {
	private $mysql;
	
	public function __construct() {
		$this->mysql = new Mysql();
	}

	public function getMaxTemperature() {
		$query = $this->mysql->query("SELECT MAX(temperature) FROM measures");
		return round($query[0][0], 2);
	}

	public function getMinTemperature() {
		$query = $this->mysql->query("SELECT MIN(temperature) FROM measures");
		return round($query[0][0], 2);
	}


	public function getLast24() {
		$query = $this->mysql->query("SELECT * FROM measures ORDER BY id DESC LIMIT 0,288");
		return $query;
	}

	public function getLastWeek() {
		$query = $this->mysql->query("SELECT * FROM measures ORDER BY id DESC LIMIT 0,2016");
		return $query;
	}
	
	public function getLimited($limit) {
		$query = $this->mysql->query("SELECT * FROM measures ORDER BY id DESC LIMIT 0,$limit");
		return $query;
	}	
	public function getAverageTemperature() {
	}
    }
?>
