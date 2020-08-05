<?php
class db
{
	private $c_dev = false;
	private $c_host = "localhost";
	private $c_user = "";
	private $c_pass = "";
	private $c_datenbank = "";

	public function __construct(){}

	public function set_dev($state)
	{
		$this->c_dev = $state;
	}

	public function connect()
	{
		$mysqli = mysqli_connect($this->c_host,$this->c_user,$this->c_pass,$this->c_datenbank);
		if (mysqli_connect_errno()) {
			echo "<strong>Verbindung fehlgeschlagen:</strong><br>";
			return false;
		}
		$mysqli->set_charset("utf8");
		$mysqli->query("SET lc_time_names='de_DE'");
		return $mysqli;
	}

	public function free_result($result)
	{
		mysqli_free_result($result);
	}

	public function close($mysqli)
	{
		mysqli_close($mysqli);
	}

	public function query($sql)
	{
		$sql = trim($sql);
		$mysqli = $this->connect();
		if($mysqli!==false)
		{
			$q = $mysqli->query($sql) or die(mysqli_error($mysqli));
			echo $this->c_dev || !$q ? "<div class='dev_error'>" : "";
				echo $this->c_dev ? $sql . "<br>" : "";
				echo ($this->c_dev || !$q) && !empty($mysqli->error) ? $mysqli->error : "";
			echo $this->c_dev || !$q ? "</div><br>" : "";
			if(substr($sql, 0, 6) == "SELECT")
			{
				if($q->num_rows==0)
				{
					$this->free_result($q);
					$this->close($mysqli);
					return false;
				}
				elseif($q->num_rows==1)
				{
					$row = mysqli_fetch_assoc($q);
					$r = array();
					$r["count"] = 1;
					$r[] = $row;
				}
				elseif($q->num_rows>1)
				{
					$r = array();
					$r["count"] = mysqli_num_rows($q);
					while($row = mysqli_fetch_assoc($q))
					{
						$r[] = $row;
					}
				}
				$this->free_result($q);
				$this->close($mysqli);
				return $r;
			}
			else
			{
				if($mysqli->affected_rows>0)
				{
					$this->close($mysqli);
					return true;
				}
				else
				{
					$this->close($mysqli);
					return false;
				}
			}
		}
		else
		{
			$this->close($mysqli);
		}
	}
}
?>