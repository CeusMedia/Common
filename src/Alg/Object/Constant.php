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
		$this->reflection	= new ReflectionClass( $className );
		$this->constants	= $this->reflection->getConstants();
	}

	public function getAll( $prefix = NULL ){
		if( $prefix )
			return $this->getAllByPrefix( $prefix );
		return $this->constants;
	}

	public function getAllByPrefix( $prefix, $strict = TRUE ){
		$prefix		= rtrim( $prefix, '_' );
		$dictionary	= new ADT_List_Dictionary( $this->constants );
		$list		= $dictionary->getAll( $prefix.'_' );
		if( !$list && $strict ){
			$message	= 'No constants defined in class "%s" with prefix "%s"';
			throw DomainException( sprintf( $message, $this->className, $prefix ) );
		}
		return $list;
	}

	public function getKeyByValue( $value, $prefix = NULL ){
		if( !is_null( $prefix ) )
			return $this->getKeyByValueAndPrefix( $value, $prefix );
		$constants	= $this->getAll();
		$list		= array();
		foreach( $constants as $constantKey => $constantValue )
			if( $constantValue === $value )
				$list[$constantKey]	= $constantKey;
		if( count( $list ) === 0 ){
			$message	= 'Constant value "%s" is not defined in class "%s"';
			throw new RangeException( sprintf( $message, $value, $this->className ) );
		}
		if( count( $list ) > 1 ){
			$message	= 'Constant value "%s" is ambigious. Please specify a constant key prefix';
			throw new RangeException( sprintf( $message, $value ) );
		}
		$keys	= array_keys( $list );
		return $keys[0];
	}

	public function getKeyByValueAndPrefix( $value, $prefix ){
		$constants	= $this->getAllByPrefix( $prefix );
		if( !in_array( $value, $constants ) ){
			$message	= 'Value "%s" is not defined in class "%s" with prefix "%s"';
			$message	= sprintf( $message, $value, $this->className, $prefix );
			throw new RangeException( $message );
		}
		$index	= array_search( $constants, $value );
		if( $index < 0 ){
			$message	= 'Constant value "%s" is not defined in class "%s" with prefix "%"';
			$message	= sprintf( $message, $value, $this->className, $prefix );
			throw new DomainException( $message );
		}
		return $index;
	}

	static public function staticGetAll( $className, $prefix = NULL ){
		$object		= new Alg_Object_Constants( $className );
		return $object->getAll( $prefix );
	}
}
