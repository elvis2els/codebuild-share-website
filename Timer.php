<?php
class Timer { 
	private $StartTime = 0;//程序运行开始时间 
	private $StopTime = 0;//程序运行结束时间 
	private $TimeSpent = 0;//程序运行花费时间
 
	function start(){//程序运行开始 
		$this->StartTime = microtime();
	} 

	function stop(){//程序运行结束 
		$this->StopTime = microtime(); 
	} 

	function spent(){//程序运行花费的时间 
		if ($this->TimeSpent) { 
			return $this->TimeSpent; 
		}
		else { 
			list($StartMicro, $StartSecond) = explode(" ", $this->StartTime); 
			list($StopMicro, $StopSecond) = explode(" ", $this->StopTime); 
			$start = floatval($StartMicro) + $StartSecond; 
			$stop = floatval($StopMicro) + $StopSecond; 
			$this->TimeSpent = number_format($stop - $start, 7); 
		} 
	} 
	
	function getTimeSpent(){
		return $this->TimeSpent;
	}
} 
//...$timer->start(); 
//...程序运行的代码 
//...$timer->stop(); 
//...$timer->spent();
//...echo $timer->getTimeSpent(); 
?>