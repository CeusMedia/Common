<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Obj;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Exception\Data\Ambiguous as AmbiguousDataException;
use DomainException;
use RangeException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Obj
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Constant
{
	protected string $className;

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
	 */
	public function getAll( ?string $prefix = NULL, bool $asDictionary = FALSE ): Dictionary|array
	{
		/** @var class-string $className */
		$className	= $this->className;
		$reflection	= new ReflectionClass( $className );
		$constants	= $reflection->getConstants();
		if( $prefix ){
			$prefix		= rtrim( $prefix, '_' );
			$dictionary	= new Dictionary( $constants );
			$constants	= $dictionary->getAll( $prefix.'_' );
		}
		return $asDictionary ? new Dictionary( $constants ) : $constants;
	}

	/**
	 *	@param		mixed				$value
	 *	@param		string|NULL			$prefix
	 *	@return		string
	 *	@throws		ReflectionException		if reflection on class failed / class not exists
	 *	@throws		RangeException			if no constant having this value is defined (within this prefix)
	 *	@throws		AmbiguousDataException	if there are several constants having this value (within this prefix)
	 */
	public function getKeyByValue( mixed $value, ?string $prefix = NULL ): string
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
	 *	@throws		DomainException			if constant is not defined
	 */
	public function getValue( string $constantKey, ?string $prefix = NULL ): mixed
	{
		/** @var array $constants */
		$constants	= $this->getAll( $prefix, FALSE );
		if( array_key_exists( $constantKey, $constants ) )
			return $constants[$constantKey];
		$constantKey	= $prefix ? rtrim( $prefix, '_' ).'_'.$constantKey : $constantKey;
		$message		= 'Constant "%s" is not defined in class "%s"';
		throw new DomainException( sprintf( $message, $constantKey, $this->className ) );
	}

	/**
	 *	@param		string			$constantKey
	 *	@param		string|NULL		$prefix
	 *	@return		bool
	 */
	public function hasKey( string $constantKey, ?string $prefix = NULL ): bool
	{
		/** @var array $constants */
		$constants	= $this->getAll( $prefix, FALSE );
		return in_array( $constantKey, array_keys( $constants ), TRUE );
	}

	/**
	 *	@param		mixed				$value
	 *	@param		string|NULL			$prefix
	 *	@return		bool
	 */
	public function hasValue( mixed $value, ?string $prefix = NULL ): bool
	{
		/** @var array $constants */
		$constants	= $this->getAll( $prefix, FALSE );
		return array_key_exists( $value, $constants );
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
	 */
	public static function staticGetAll( string $className, ?string $prefix = NULL, bool $asDictionary = FALSE ): Dictionary|array
	{
		return self::fromClassName( $className )->getAll( $prefix, $asDictionary );
	}

	/**
	 *	@param		string			$className
	 *	@param		string			$constantKey
	 *	@param		string|NULL		$prefix
	 *	@return		mixed
	 */
	public static function staticGetValue( string $className, string $constantKey, ?string $prefix = NULL ): mixed
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
	public static function staticGetKeyByValue( string $className, mixed $value, ?string $prefix = NULL ): string

	{
		$object		= new Constant( $className );
		return $object->getKeyByValue( $value, $prefix );
	}
}
