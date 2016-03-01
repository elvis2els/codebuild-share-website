<?php
class Timer { 
	private $StartTime = 0;//�������п�ʼʱ�� 
	private $StopTime = 0;//�������н���ʱ�� 
	private $TimeSpent = 0;//�������л���ʱ��
 
	function start(){//�������п�ʼ 
		$this->StartTime = microtime();
	} 

	function stop(){//�������н��� 
		$this->StopTime = microtime(); 
	} 

	function spent(){//�������л��ѵ�ʱ�� 
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
//...�������еĴ��� 
//...$timer->stop(); 
//...$timer->spent();
//...echo $timer->getTimeSpent(); 
?>