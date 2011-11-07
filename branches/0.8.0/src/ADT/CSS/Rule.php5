<?php
class ADT_CSS_Rule{

	public $selector	= NULL;

	public $properties	= array();

	public function __construct( $selector, $properties = array() ){
		$this->setSelector( $selector );
		foreach( $properties as $property )
			$this->setProperty( $property );
	}

	public function getPropertyByIndex( $index ){
		if( !isset( $this->properties[$index] ) )
			throw new OutOfRangeException( 'Invalid property index' );
		return $this->properties[$index];
	}

	public function getPropertyByKey( $key ){
		foreach( $this->properties as $nr => $property )
			if( $key == $property->getKey() )
				return $property;
		return NULL;
	}

	public function getProperties(){
		return $this->properties;
	}

	public function getSelector(){
		return $this->selector;
	}

	public function hasProperty( ADT_CSS_Property $property ){
		return $this->hasPropertyByKey( $property->getKey() );
	}

	public function hasPropertyByKey( $key ){
		foreach( $this->properties as $nr => $property )
			if( $key == $property->getKey() )
				return TRUE;
		return FALSE;
	}

	public function removeProperty( ADT_CSS_Property $property ){
		return $this->removePropertyByKey( $property->getKey() );
	}

	public function removePropertyByKey( $key ){
		foreach( $this->properties as $nr => $property ){
			if( $key == $property->getKey() ){
				unset( $this->properties[$nr] );
				return TRUE;
			}
		}
		return FALSE;
	}

	public function setProperty( ADT_CSS_Property $property ){
		return $this->setPropertyByKey( $property->getKey(), $property->getValue() );				//
	}

	public function setPropertyByKey( $key, $value = NULL ){
		if( $value === NULL || !strlen( $value ) )
			return $this->removePropertyByKey( $key );
		$property	= $this->getPropertyByKey( $key );
		if( $property )
			return $property->setValue( $value );
		$this->properties[]	= new ADT_CSS_Property( $key, $value );
		return TRUE;
	}

	public function setSelector( $selector ){
		$this->selector	= $selector;
	}
}
?>
