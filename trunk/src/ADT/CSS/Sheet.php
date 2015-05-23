<?php
/**
 *	...
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		ADT.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		ADT.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
class ADT_CSS_Sheet{

	/**	@var		array			$rules		List of CSS rule objects */
	public $rules		= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct(){
		$this->rules	= array();
	}

	/**
	 *	Add rule object
	 *	@access		public
	 *	@param		ADT_CSS_Rule	$rule		CSS rule object
	 *	@return		void
	 */
	public function addRule( ADT_CSS_Rule $rule ){
		$got = $this->getRuleBySelector( $rule->selector );
		if( $got )
			foreach( $rule->getProperties() as $property )
				$got->setPropertyByKey( $property->getKey(), $property->getValue() );
		else{
			if( !preg_match( '/([a-z])|(#|\.[a-z])/i', $rule->getSelector() ) )
				throw new InvalidArgumentException( 'Invalid selector' );
			$this->rules[]	= $rule;
		}
	}

	/**
	 *	Return property value.
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@param		string			$key		Property key
	 *	@return		string|NULL
	 */
	public function get( $selector, $key ){
		$rule = $this->getRuleBySelector( $selector );
		if( !$rule )
			return NULL;
		return $rule->getPropertyByKey( $key );
	}

	/**
	 *
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@return		ADT_CSS_Rule|NULL
	 */
	public function getRuleBySelector( $selector ){
		foreach( $this->rules as $rule )
			if( $selector == $rule->getSelector() )
				return $rule;
		return NULL;
	}

	/**
	 *	Returns a list of rule objects.
	 *	@access		public
	 *	@return		array
	 */
	public function getRules(){
		return $this->rules;
	}

	/**
	 *	Returns a list of selectors.
	 *	@access		public
	 *	@return		array
	 */
	public function getSelectors(){
		$list	= array();
		foreach( $this->rules as $rule )
			$list[]	= $rule->getSelector();
		return $list;
	}

	/**
	 *	Indicates whether a property is existing by its key.
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@return		boolean
	 */
	public function has( $selector, $key = NULL ){
		$rule = $this->getRuleBySelector( $selector );
		if( $rule )
			return !$key ? TRUE : $rule->has( $key );
		return FALSE;
	}

	/**
	 *	Indicates whether a rule is existing by its selector.
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@return		boolean
	 */
	public function hasRuleBySelector( $selector ){
		foreach( $this->rules as $rule )
			if( $selector == $rule->getSelector() )
				return TRUE;
		return FALSE;
	}

	/**
	 *	Removes a property by its key.
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@param		string			$key		Property key
	 *	@return		boolean
	 */
	public function remove( $selector, $key ){
		$rule	= $this->getRuleBySelector( $selector );
		if( !$rule )
			return FALSE;
		if( $rule->removePropertyByKey( $key ) ){
			if( !$rule->getProperties() )
				$this->removeRuleBySelector( $selector );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Removes a property.
	 *	@access		public
	 *	@param		ADT_CSS_Rule		$rule		Rule object
	 *	@param		ADT_CSS_Property	$property	Property object
	 *	@return		boolean
	 */
	public function removeProperty( ADT_CSS_Rule $rule, ADT_CSS_Property $property ){
		return $this->remove( $rule->getSelector(), $property->getKey() );
	}

	/**
	 *	Removes a rule.
	 *	@access		public
	 *	@param		ADT_CSS_Rule		$rule		Rule object
	 *	@return		boolean
	 */
	public function removeRule( ADT_CSS_Rule $rule ){
		return $this->removeRuleBySelector( $rule->getSelector() );
	}

	/**
	 *	Removes a rule by its selector.
	 *	@access		public
	 *	@param		string			$selector		Rule selector
	 *	@return		boolean
	 */
	public function removeRuleBySelector( $selector ){
		foreach( $this->rules as $nr => $rule ){
			if( $selector == $rule->getSelector() ){
				unset( $this->rules[$nr] );
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 *	Sets a properties value.
	 *	@access		public
	 *	@param		string			$selector		Rule selector
	 *	@param		string			$key			Property key
	 *	@param		string			$value			Property value
	 *	@return		boolean
	 */
	public function set( $selector, $key, $value = NULL ){
		if( $value === NULL || !strlen( $value ) )
			return $this->remove( $selector, $key );
		$rule = $this->getRuleBySelector( $selector );
		if( !$rule ){
			$rule	= new ADT_CSS_Rule( $selector );
			$this->rules[]	= $rule;
		}
		return $rule->setPropertyByKey( $key, $value );
	}

	/**
	 *	Sets a property.
	 *	@access		public
	 *	@param		ADT_CSS_Rule		$rule		Rule object
	 *	@param		ADT_CSS_Property	$property	Property object
	 *	@return		boolean
	 */
	public function setProperty( ADT_CSS_Rule $rule, ADT_CSS_Property $property ){
		return $this->set( $rule->getSelector(), $property->getKey(), $property->getValue() );		//  
	}
}
?>
