<?php
class ADT_CSS_Sheet{

	public $rules		= NULL;

	public function __construct(){
		$this->rules	= array();
	}

	public function add( $selector, $key, $value ){
		$this->set( $selector, $key, $value );
	}

	public function addRule( ADT_CSS_Rule $rule ){
		$got = $this->getRuleBySelector( $rule->selector );
		if( $got )
			foreach( $rule->getProperties() as $property )
				$got->setPropertyByKey( $property->getKey(), $property->getValue() );
		else
			$this->rules[]	= $rule;
	}
	
	public function getRuleBySelector( $selector ){
		foreach( $this->rules as $rule )
			if( $selector == $rule->getSelector() )
				return $rule;
		return NULL;
	}

	public function getRules(){
		return $this->rules;
	}

	public function getSelectors(){
		$list	= array();
		foreach( $this->rules as $rule )
			$list[]	= $rule->getSelector();
		return $list;
	}

	public function has( $selector, $key = NULL ){
		$rule = $this->getRuleBySelector( $selector );
		if( $rule )
			return !$key ? TRUE : $rule->has( $key );
		return FALSE;
	}

	public function hasRuleBySelector( $selector ){
		foreach( $this->rules as $rule )
			if( $selector == $rule->getSelector() )
				return TRUE;
		return FALSE;
	}

	public function remove( $selector, $property = NULL ){
		$rule	= $this->getRule( $selector );
		if( !$rule )
			return FALSE;
		if( $property ){
			$rule	= $this->getRule( $selector );
			if( $rule )
				$rule->removeProperty( $propery );
		}
		if( !$rule->getAll() )
			$this->removeRule( $selector );
	}

	public function removeRule( $selector ){
		foreach( $this->rules as $nr => $rule ){
			if( $selector == $rule->getSelector() ){
				unset( $this->rules[$nr] );
				return TRUE;
			}
		}
		return FALSE;
	}

	public function set( $selector, $key, $value = NULL ){
		$rule = $this->getRule( $selector );
		if( !$rule )
			$rule	= $this->add( $selector );
		$rule->setProperty( $key, $value );
	}
}
?>
