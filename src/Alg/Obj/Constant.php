<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\Alg\Obj;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Exception\Data\Ambiguous as AmbiguousDataException;
use DomainException;
use RangeException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class Constant
{
	protected $className;

	protected $constants;

	protected $reflection;

	public function __construct( string $className )
	{
		if( !class_exists( $className ) ){
			$message	= sprintf( 'Class "%s" is not available', $className );
			throw new RuntimeException( $message );
		}
		$this->className	= $className;
	}

	/**
	 *	@param		string|NULL		$prefix
	 *	@param		bool			$asDictionary		Flag: return as ADT\Collection\Dictionary
	 *	@return		array|Dictionary
	 *	@throws		ReflectionException
	 */
	public function getAll( ?string $prefix = NULL, bool $asDictionary = FALSE )
	{
		$reflection	= new ReflectionClass( $this->className );
		$constants	= $reflection->getConstants();
		if( $prefix ){
			$prefix		= rtrim( $prefix, '_' );
			$dictionary	= new Dictionary( $constants );
			$constants	= $dictionary->getAll( $prefix.'_', $asDictionary );
		}
		return $constants;
	}

	/**
	 *	@param		mixed				$value
	 *	@param		string|NULL			$prefix
	 *	@return		string
	 *	@throws		ReflectionException
	 *	@throws		RangeException			if no constant having this value is defined (within this prefix)
	 *	@throws		AmbiguousDataException	if there are several constants having this value (within this prefix)
	 */
	public function getKeyByValue( $value, ?string $prefix = NULL ): string
	{
		$constants	= $this->getAll( $prefix );
		$list		= [];
		foreach( $constants as $constantKey => $constantValue )
			if( $constantValue === $value )
				$list[]	= (string) $constantKey;
		if( count( $list ) === 0 ){
			$message	= 'Constant value "%s" is not defined in class "%s"';
			throw new RangeException( sprintf( $message, $value, $this->className ) );
		}
		if( count( $list ) > 1 ){
			$message	= 'Constant value "%s" is ambiguous';
			throw new AmbiguousDataException( sprintf( $message, $value ), 0, NULL, $list );
		}
		return $list[0];
	}

	/**
	 *	@param		string			$constantKey
	 *	@param		string|NULL		$prefix
	 *	@return		mixed
	 *	@throws		ReflectionException
	 */
	public function getValue( string $constantKey, ?string $prefix = NULL )
	{
		$constants	= $this->getAll( $prefix );
		if( array_key_exists( $constantKey, $constants ) )
			return $constants[$constantKey];
		$constantKey	= $prefix ? rtrim( $prefix, '_' ).'_'.$constantKey : $constantKey;
		$message	= 'Constant "%s" is not defined in class "%s"';
		throw new DomainException( sprintf( $message, $constantKey, $this->className ) );
	}

	/**
	 *	@param		string			$constantKey
	 *	@param		string|NULL		$prefix
	 *	@return		bool
	 *	@throws		ReflectionException
	 */
	public function hasKey( string $constantKey, ?string $prefix = NULL ): bool
	{
		return in_array( $constantKey, $this->getAll( $prefix ), TRUE );
	}

	/**
	 *	@param		mixed				$value
	 *	@param		string|NULL			$prefix
	 *	@return		bool
	 *	@throws		ReflectionException
	 */
	public function hasValue( $value, ?string $prefix = NULL ): bool
	{
		return array_key_exists( $value, $this->getAll( $prefix ) );
	}

	public static function fromClassName( string $className ): self
	{
		return new self( $className );
	}

	/**
	 *	@param		string			$className
	 *	@param		string|NULL		$prefix
	 *	@param		bool			$asDictionary		Flag: return as ADT\Collection\Dictionary
	 *	@return		array|Dictionary
	 *	@throws		ReflectionException
	 */
	public static function staticGetAll( string $className, ?string $prefix = NULL, bool $asDictionary = FALSE )
	{
		return self::fromClassName( $className )->getAll( $prefix, $asDictionary );
	}

	/**
	 *	@param		string			$className
	 *	@param		string			$constantKey
	 *	@param		string|NULL		$prefix
	 *	@return		mixed
	 *	@throws		ReflectionException
	 */
	public static function staticGetValue( string $className, string $constantKey, ?string $prefix = NULL )
	{
		return self::fromClassName( $className )->getValue( $constantKey, $prefix );
	}

	/**
	 *	@param		string			$className
	 *	@param		mixed			$value
	 *	@param		string|NULL		$prefix
	 *	@return		string
	 *	@throws		ReflectionException
	 *	@throws		AmbiguousDataException
	 */
	public static function staticGetKeyByValue( string $className, $value, ?string $prefix = NULL ): string
	{
		$object		= new Constant( $className );
		return $object->getKeyByValue( $value, $prefix );
	}
}
