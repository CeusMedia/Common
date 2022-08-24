<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\CLI;

use CeusMedia\Common\CLI;
use RangeException;

class Question
{
	public const TYPE_UNKNOWN			= 0;
	public const TYPE_BOOLEAN			= 1;
	public const TYPE_INTEGER			= 2;
	public const TYPE_NUMBER			= 3;
	public const TYPE_STRING			= 4;

	public const TYPES					= [
		self::TYPE_UNKNOWN,
		self::TYPE_BOOLEAN,
		self::TYPE_INTEGER,
		self::TYPE_NUMBER,
		self::TYPE_STRING,
	];

	protected $message;
	protected $type				= 0;
	protected $default			= NULL;
	protected $options			= [];
	protected $break			= TRUE;
	protected $rangeFrom		= 0;
	protected $rangeTo			= 0;
	protected $strictOptions	= TRUE;

	public static $defaultBooleanOptions	= array(
		'y'		=> 'yes',
		'n'		=> 'no',
	);

	public function __construct( string $message, int $type = self::TYPE_STRING, $default = NULL, array $options = [], bool $break = TRUE )
	{
		$this->setMessage( $message );
		$this->setType( $type );
		$this->setDefault( $default );
		$this->setOptions( $options );
		$this->setBreak( $break );
	}

	public function ask(): string
	{
		$message	= $this->renderLabel();
		CLI::out( $message, $this->break );
		$handle	= fopen( "php://stdin","r" );
		$input	= trim( fgets( $handle ) );
		if( !$this->evaluateInput( $input ) )
			$input	= $this->ask();
		return $input;
	}

	public static function askStatic( string $message, int $type = self::TYPE_STRING, $default = NULL, array $options = [], bool $break = TRUE ): string
	{
		$input	= new self( $message, $type, $default, $options, $break );
		return $input->ask();
	}

	public static function getInstance( string $message ): self
	{
		return new self( $message );
	}

	public function setBreak( bool $break = TRUE ): self
	{
		$this->break	= $break;
		return $this;
	}

	public function setDefault( $default = NULL ): self
	{
		$this->default	= $default;
		return $this;
	}

	public function setMessage( string $message ): self
	{
		$this->message	= $message;
		return $this;
	}

	public function setOptions( array $options = [] ): self
	{
		if( $options )
			$this->options	= $options;
		return $this;
	}

	public function setStrictOptions( bool $switch = TRUE ): self
	{
		$this->strictOptions	= $switch;
		return $this;
	}

	public function setRange( int $from, int $to ): self
	{
		$this->rangeFrom	= $from;
		$this->rangeTo		= $to;
		return $this;
	}

	public function setType( int $type ): self
	{
		$this->type		= $type;
		if( $type === self::TYPE_BOOLEAN )
			$this->setOptions( self::$defaultBooleanOptions );
		return $this;
	}

	protected function evaluateInput( string & $input ): bool
	{
		if( $this->default && !strlen( $input ) )
			$input	= $this->default;
		if( $this->type === self::TYPE_BOOLEAN ){
			if( !array_key_exists( $input, $this->options ) )
				if( !in_array( $input, $this->options, TRUE ) )
					return FALSE;
		}
		if( $this->type === self::TYPE_STRING ){
			if( $this->options && $this->strictOptions ){
				if( !in_array( $input, $this->options, TRUE ) )
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
				else if( $this->options && !in_array( $input, $this->options, TRUE ) )
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
				else if( $this->options && !in_array( $input, $this->options, TRUE ) )
					return FALSE;
			}
		}
		return TRUE;
	}

	protected function renderLabel(): string
	{
		$message		= $this->message;
		$options		= $this->options;
		if( $this->type === self::TYPE_BOOLEAN ){
			if( $this->strictOptions )
				if( !is_null( $this->default ) )
					if( !array_key_exists( $this->default, $this->options ) )
						throw new RangeException( 'Default value is not within options' );
			$options	= [];
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
}
