<?php
class DateInterval {
	public $y;
	public $m;
	public $d;
	public $h;
	public $i;
	public $s;
	public $invert;
	public $days;

	public function __construct($interval_spec, $strict = false){
		if($interval_spec[0] != 'P'){
			throw new Exception('Invalid interval spec');
		}
		$interval_spec	= str_split($interval_spec);
		array_shift($interval_spec);

		$current	= NULL;
		$number		= '';

		$in_time	= false;
		foreach($interval_spec as $char){
			if(is_numeric($char)){
				$number	.= $char;
				continue;
			}

			if(!isset($current)){
				$current	= $char;
				if($char == 'T'){
					$in_time	= true;
					continue;
				}
			}

			$number	= intval($number);
			if(!$in_time){
				// Date
				switch($char){
					case 'Y':
						break;

					case 'M':
						if($strict && $number >= 13){
							throw new Exception('Invalid months');
						}
						break;


					case 'W':
						$char	= 'D';
						$number	*= 7;
						// Continue as days
					case 'D':
						if($strict && $number >= 31){
							throw new Exception('Invalid days');
						}
						if(isset($this->d)){
							throw new Exception('Days already set');
						}
						break;

					case 'T':
						// Starting time
						$in_time	= true;
						break;

					default:
						throw new Exception('Invalid date format "'.$char.'"');
						break;
				}

			} else {
				// Time
				switch($char){
					case 'H':
						if($strict && $number >= 24){
							throw new Exception('Invalid hours');
						}
						break;

					case 'M':
						if($strict && $number >= 60){
							throw new Exception('Invalid minutes');
						}
						$char	= 'I';
						break;

					case 'S':
						if($strict && $number >= 60){
							throw new Exception('Invalid seconds');
						}
						break;

					case 'W':
						if(isset($this->d)){
							throw new Exception('Days already set');
						}
						$char	= 'D';
						$number	*= 7;
						break;

					default:
						throw new Exception('Invalid date format "'.$char.'"');
						break;
				}
			}

			if(strtolower($char) == 't'){
				// In time now
				continue;
			}

			$this->{strtolower($char)}	= $number;

			$current	= $char;
		}

		$array	= array('y','m','d','h','i','s');
		foreach($array as $key){
			if(!isset($this->{$key})){
				$this->{$key}	= 0;
			}
		}
	}

	public function format($format){
		throw new BadMethodCallException('Cannot use method yet');
	}

	public static function createFromDateString($time){
		throw new BadMethodCallException('Cannot use method yet');
	}
};
