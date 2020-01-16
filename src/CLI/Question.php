<?php
class CLI_Question{

	const TYPE_UNKNOWN			= 0;
	const TYPE_BOOLEAN			= 1;
	const TYPE_INTEGER			= 2;
	const TYPE_NUMBER			= 3;
	const TYPE_STRING			= 4;

	protected $message;
	protected $type				= 0;
	protected $default			= NULL;
	protected $options			= array();
	protected $break			= TRUE;
	protected $rangeFrom		= 0;
	protected $rangeTo			= 0;
	protected $strictOptions	= TRUE;

	static public $defaultBooleanOptions	= array(
		'y'	=> 'yes',
		'n'	=> 'no',
	);

	public function __construct( $message, $type = self::TYPE_STRING, $default = NULL, $options = array(), $break = TRUE ){
		$this->setMessage( $message );
		$this->setType( $type );
		$this->setDefault( $default );
		$this->setOptions( $options );
		$this->setBreak( $break );
	}

	public function ask(){
		$message	= $this->renderLabel();
		CLI::out( $message, $this->break );
		$handle	= fopen( "php://stdin","r" );
		$input	= trim( fgets( $handle ) );
		if( !$this->evaluateInput( $input ) )
			$input	= $this->ask();
		return $input;
	}

	protected function evaluateInput( & $input ){
		if( $this->default && !strlen( $input ) )
			$input	= $this->default;
		if( $this->type === self::TYPE_BOOLEAN ){
			if( !array_key_exists( $input, $this->options ) )
				if( !in_array( $input, $this->options ) )
					return FALSE;
		}
		if( $this->type === self::TYPE_STRING ){
			if( $this->options && $this->strictOptions ){
				if( !in_array( $input, $this->options ) )
					return FALSE;
			}
		}
		if( $this->type === self::TYPE_INTEGER ){
			if( !preg_match( '/^[0-9]+$/', $input ) )
				return FALSE;
			$input	= (int) $input;
			if( $this->strictOptions ){
 				if( $this->rangeFrom || $this->rangeTo )
	 				if( $input < $this->rangeFrom || $input > $this->rangeTo )
						return FALSE;
				else if( $this->options && !in_array( $input, $this->options ) )
					return FALSE;
			}
		}
		if( $this->type === self::TYPE_NUMBER ){
			if( !preg_match( '/^[0-9.]+$/', $input ) )
				return FALSE;
			$input	= (float) $input;
			if( $this->strictOptions ){
 				if( $this->rangeFrom || $this->rangeTo ){
	 				if( $input < $this->rangeFrom || $input > $this->rangeTo )
						return FALSE;
				}
				else if( $this->options && !in_array( $input, $this->options ) )
					return FALSE;
			}
		}
		return TRUE;
	}

	static public function askStatic( $message, $type = 'string', $default = NULL, $options = array(), $break = TRUE ){
		$input	= new self( $message, $type, $default, $options, $break );
		return $input->ask();
	}

	static public function getInstance( $message ){
		return new static( $message );
	}

	protected function renderLabel(){
		$message		= $this->message;
		$options		= $this->options;
		if( $this->type === self::TYPE_BOOLEAN ){
			if( $this->strictOptions )
				if( !is_null( $this->default ) )
					if( !array_key_exists( $this->default, $this->options ) )
						throw new RangeException( 'Default value is not within options' );
			$options	= array();
			foreach( $this->options as $key => $value )
				$options[]	= $key.':'.$value;
		}
		else if( $this->type === self::TYPE_INTEGER ){
			if( $this->rangeFrom || $this->rangeTo ){
				if( !is_null( $this->default ) )
					if( $this->default < $this->rangeFrom || $this->default > $this->rangeTo )
						throw new RangeException( 'Default value is not within set range' );
				$options	= array( $this->rangeFrom.'-'.$this->rangeTo );
			}
		}
		if( strlen( trim( $this->default ) ) )
			$message	.= " [".$this->default."]";
		if( is_array( $options ) && count( $options ) )
			$message	.= " (".implode( "|", $options ).")";
		if( !$this->break )
			$message	.= ": ";
		return $message;
	}

	public function setBreak( $break = TRUE ){
		$this->break	= $break;
		return $this;
	}

	public function setDefault( $default = NULL ){
		$this->default	= $default;
		return $this;
	}

	public function setMessage( $message ){
		$this->message	= $message;
		return $this;
	}

	public function setOptions( $options = array() ){
		if( $options )
			$this->options	= $options;
		return $this;
	}

	public function setStrictOptions( $switch = TRUE ){
		$this->strictOptions	= $switch;
		return $this;
	}

	public function setRange( $from, $to ){
		$this->rangeFrom	= $from;
		$this->rangeTo		= $to;
		return $this;
	}

	public function setType( $type ){
		$this->type		= $type;
		if( $type === self::TYPE_BOOLEAN )
			$this->setOptions( self::$defaultBooleanOptions );
		return $this;
	}
}
