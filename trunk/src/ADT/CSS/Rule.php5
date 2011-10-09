<?php
class ADT_CSS_Rule{

	public $selector	= NULL;

	public $properties	= array();

	public function __construct( $selector, $properties = array() ){
		$this->setSelector( $selector );
		foreach( $properties as $property )
			$this->addProperty( $property );
	}

	public function addProperty( ADT_CSS_Property $property ){
		$got = $this->getPropertyByKey( $property->getKey() );
		if( $got ){
			$got->setValue( $property->getValue() );
			return $got;
		}
		$this->properties[]	= $property;
		return $property;
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

	public function hasPropertyByKey( $key ){
		foreach( $this->properties as $nr => $property )
			if( $key == $property->getKey() )
				return TRUE;
		return FALSE;
	}

	public function setPropertyByKey( $key, $value = NULL ){
		$property	= $this->getPropertyByKey( $key );
		if( $property ){
			if( $value === NULL )
				return $this->removePropertyByKey( $key );
			return $property->setValue( $value );
		}
		$this->properties[]	= new ADT_CSS_Property( $key, $value );
		return TRUE;
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

	public function getSelector(){
		return $this->selector;
	}

	public function setSelector( $selector ){
		$this->selector	= $selector;
	}
}
?>
