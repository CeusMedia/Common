<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Terminal question.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI;

use CeusMedia\Common\CLI;
use RangeException;

/**
 *	Terminal question.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Question
{
	public const TYPE_UNKNOWN			= 0;
	public const TYPE_BOOLEAN			= 1;
	public const TYPE_INTEGER			= 2;
	public const TYPE_NUMBER			= 3;
	public const TYPE_STRING			= 4;

	public const TYPES			= [
		self::TYPE_UNKNOWN,
		self::TYPE_BOOLEAN,
		self::TYPE_INTEGER,
		self::TYPE_NUMBER,
		self::TYPE_STRING,
	];

	public static array $defaultBooleanOptions	= [
		'y'		=> 'yes',
		'n'		=> 'no',
	];

	protected string $message;

	protected int $type				= 0;

	/** @var string|int|float|NULL $default  */
	protected string|int|float|NULL $default				= NULL;

	protected array $options		= [];

	protected bool $break			= TRUE;

	protected int $rangeFrom		= 0;

	protected int $rangeTo			= 0;

	protected bool $strictOptions	= TRUE;

	/**
	 *	@param		string					$message
	 *	@param		int						$type
	 *	@param		string|int|float|NULL	$default
	 *	@param		array					$options
	 *	@param		bool					$break
	 */
	public function __construct( string $message, int $type = self::TYPE_STRING, string|int|float|null $default = NULL, array $options = [], bool $break = TRUE )
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

	/**
	 * @param		string					$message
	 * @param		int						$type
	 * @param		string|int|float|NULL	$default
	 * @param		array					$options
	 * @param		bool					$break
	 * @return		string
	 */
	public static function askStatic( string $message, int $type = self::TYPE_STRING, string|int|float|null $default = NULL, array $options = [], bool $break = TRUE ): string
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

	/**
	 *	@param		string|int|float|NULL	$default
	 *	@return		self
	 */
	public function setDefault( string|int|float|null $default = NULL ): self
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
			if( 1 !== preg_match( '/^\d+$/', $input ) )
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
			if( 1 !== preg_match( '/^[\d.]+$/', $input ) )
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
				$options	= [$this->rangeFrom.'-'.$this->rangeTo];
			}
		}
		if( strlen( trim( $this->default ?? '' ) ) )
			$message	.= " [".$this->default."]";
		if( is_array( $options ) && count( $options ) )
			$message	.= " (".implode( "|", $options ).")";
		if( !$this->break )
			$message	.= ": ";
		return $message;
	}
}
