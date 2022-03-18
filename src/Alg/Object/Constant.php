<?php
class Alg_Object_Constant{

	protected $className;
	protected $constants;
	protected $reflection;

	public function __construct( $className ){
		if( !class_exists( $className ) ){
			$message	= sprintf( 'Class "%s" is not available', $className );
			throw new RuntimeException( $message );
		}
		$this->className	= $className;
	}

	public function getAll( $prefix = NULL, bool $asDictionary = FALSE ){
		$reflection	= new ReflectionClass( $this->className );
		$constants	= $reflection->getConstants();
		if( $prefix ){
			$prefix		= rtrim( $prefix, '_' );
			$dictionary	= new ADT_List_Dictionary( $constants );
			$constants	= $dictionary->getAll( $prefix.'_', $asDictionary );
		}
		return $constants;
	}

	public function getKeyByValue( $value, $prefix = NULL ){
		$constants	= $this->getAll( $prefix );
		$list		= array();
		foreach( $constants as $constantKey => $constantValue )
			if( $constantValue === $value )
				$list[]	= $constantKey;
		if( count( $list ) === 0 ){
			$message	= 'Constant value "%s" is not defined in class "%s"';
			throw new RangeException( sprintf( $message, $value, $this->className ) );
		}
		if( count( $list ) > 1 ){
			$message	= 'Constant value "%s" is ambigious';
			throw new RangeException( sprintf( $message, $value ) );
		}
		return $list[0];
	}

	public function getValue( $constantKey, $prefix = NULL ){
		$constants	= $this->getAll( $prefix );
		if( array_key_exists( $constantKey, $constants ) )
			return $constants[$constantKey];
		$key		= $prefix ? rtrim( $prefix, '_' ).'_'.$constantKey : $constantKey;
		$message	= 'Constant "%s" is not defined in class "%s"';
		throw new DomainException( sprintf( $message, $constantKey, $this->className ) );
	}

	public function hasKey( $constantKey, $prefix = NULL ): bool
	{
		return in_array( $constantKey, $this->getAll( $prefix ), TRUE );
	}

	public function hasValue( $value, $prefix = NULL ): bool
	{
		return array_key_exists( $value, $this->getAll( $prefix ) );
	}

	static public function staticGetAll( $className, $prefix = NULL ){
		$object		= new Alg_Object_Constant( $className );
		return $object->getAll( $prefix );
	}

	static public function staticGetValue( $className, $constantKey, $prefix = NULL ){
		$object		= new Alg_Object_Constant( $className );
		return $object->getValue( $constantKey, $prefix );
	}

	static public function staticGetKeyByValue( $className, $value, $prefix = NULL ){
		$object		= new Alg_Object_Constant( $className );
		return $object->getKeyByValue( $value, $prefix );
	}
}
