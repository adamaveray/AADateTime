<?php
class AADateTime extends DateTime {
	static protected $modern	= true;
	
	protected $sleep_time;

	static public function setup(){
		self::$modern	= version_compare(PHP_REAL_VERSION, '5.3.0', '>=');
	}


	public function __construct($time_or_object = 'now', DateTimeZone $timezone = NULL){
		if($time_or_object instanceof DateTime){
			// Upgrade from native to AADateTime
			$timezone		= @$time_or_object->getTimezone();
			$time_or_object	= @$time_or_object->format('c');
		} elseif(!self::$modern && is_string($time_or_object) && (strstr($time_or_object, 'last day') !== false || strstr($time_or_object, 'last day') !== false)){
			$time_or_object	= str_replace(array('last day of next month',
												'first day of next month'), '+1 month',
												 $time_or_object);
												
			if(strstr($time_or_object, 'last day') !== false || strstr($time_or_object, 'last day') !== false){
				throw new BadMethodCallException('First- and last-day relative strings cannot be processed in PHP5.2');
			}
		}

		if(!isset($timezone) || $timezone === false){
			$timezone	= NULL;
		}
		
		parent::__construct($time_or_object, $timezone);
	}

	public function diff($object = 'now', $absolute = false){
		if(is_string($object)){
			$object = new AADateTime($object);
		}

		if(self::$modern){
			return parent::diff($object, $absolute);
		}

		// Fallback
		if(!$absolute){
			$diff	= $object->getTimestamp() - $this->getTimestamp();
			return $diff;
		}
	}

	public function setDate($year, $month, $day){
		$result	= parent::setDate($year, $month, $day);
		if($result === false){
			// Could not set date
			return false;
		}
		return $this;
	}

	public function setTimestamp($unixtimestamp){
		if(self::$modern){
			return parent::setTimestamp($unixtimestamp);
		}

		// Function missing
		if(!is_numeric($unixtimestamp) && !is_null($unixtimestamp)){
			trigger_error('DateTime::setTimestamp() expects parameter 1 to be long, '.gettype($unixtimestamp).' given ('.$unixtimestamp.')', E_USER_WARNING);
		} else {
			$this->setDate(date('Y', $unixtimestamp), date('n', $unixtimestamp), date('d', $unixtimestamp));
			$this->setTime(date('G', $unixtimestamp), date('i', $unixtimestamp), date('s', $unixtimestamp));
		}
		return $this;
	}

	public function getTimestamp(){
		if(self::$modern){
			return parent::getTimestamp();
		}
		
		// Fallback
		return $this->format('U');
	}


	public function format($format){
		return parent::format($format);
	}


	public function __toString(){
		$result	= $this->format('c');
		
		return ($result) ? strval($result) : '';
	}


	public function __sleep(){
		if(self::$modern){
			return parent::__sleep();
		}

		$this->sleep_time	= $this->format('c');

		return array('sleep_timestamp');
	}
	
	public function __wakeup(){
		$this->__construct($this->sleep_time);
		unset($this->sleep_time);
	}


	public function add($var){
		if(!$var instanceof DateInterval){
			if(is_string($var) && substr($var, 0, 1) != 'P'){
				$var	= 'P'.$var;
			}
			try {
				$var	= new DateInterval($var);
			} catch(Exception $e){
				return;
			}
		}

		if(self::$modern){
			return parent::add($var);
		}

		// Polyfill
		$array	= array('y'	=> 'year',
						'm'	=> 'month',
						'd'	=> 'day',
						'h'	=> 'hour',
						'i'	=> 'minute',
						's'	=> 'second');
		foreach($array as $key => $val){
			if($var->{$key} > 0){
				$this->modify('+'.$var->{$key}.' '.Helpers::get_plural($var->{$key}, $val));
			}
		}
	}

	public function sub($var){
		if(!$var instanceof DateInterval){
			if(is_string($var) && substr($var, 0, 1) != 'P'){
				$var	= 'P'.$var;
			}
			try {
				$var	= new CoreDateInterval($var);
			} catch(Exception $e){
				return;
			}
		}

		if(self::$modern){
			return parent::sub($var);
		}

		// Polyfill
		$array	= array('y'	=> 'year',
						'm'	=> 'month',
						'd'	=> 'day',
						'h'	=> 'hour',
						'i'	=> 'minute',
						's'	=> 'second');
		foreach($array as $key => $val){
			if($var->{$key} > 0){
				$this->modify('-'.$var->{$key}.' '.Helpers::get_plural($var->{$key}, $val));
			}
		}
	}
};

AADateTime::setup();