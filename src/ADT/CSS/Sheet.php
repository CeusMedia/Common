<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2011-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\CSS;

use InvalidArgumentException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Sheet
{
	/**	@var		Rule[]			$rules		List of CSS rule objects */
	public array $rules				= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->rules	= [];
	}

	/**
	 *	Add rule object
	 *	@access		public
	 *	@param		Rule	$rule		CSS rule object
	 *	@return		self
	 */
	public function addRule( Rule $rule ): self
	{
		$got = $this->getRuleBySelector( $rule->selector );
		if( $got )
			foreach( $rule->getProperties() as $property )
				$got->setPropertyByKey( $property->getKey(), $property->getValue() );
		else{
			if( !preg_match( '/([a-z])|(#|\.[a-z])/i', $rule->getSelector() ) )
				throw new InvalidArgumentException( 'Invalid selector' );
			$this->rules[]	= $rule;
		}
		return $this;
	}

	/**
	 *	Return property value.
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@param		string			$key		Property key
	 *	@return		Property|NULL
	 */
	public function get( string $selector, string $key ): ?Property
	{
		$rule = $this->getRuleBySelector( $selector );
		return $rule?->getPropertyByKey($key);
	}

	/**
	 *
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@return		Rule|NULL
	 */
	public function getRuleBySelector( string $selector ): ?Rule
	{
		foreach( $this->rules as $rule )
			if( $selector === $rule->getSelector() )
				return $rule;
		return NULL;
	}

	/**
	 *	Returns a list of rule objects.
	 *	@access		public
	 *	@return		array
	 */
	public function getRules(): array
	{
		return $this->rules;
	}

	/**
	 *	Returns a list of selectors.
	 *	@access		public
	 *	@return		array
	 */
	public function getSelectors(): array
	{
		$list	= [];
		foreach( $this->rules as $rule )
			$list[]	= $rule->getSelector();
		return $list;
	}

	/**
	 *	Indicates whether a property is existing by its key.
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@param		string|NULL		$key		Rule key
	 *	@return		boolean
	 */
	public function has( string $selector, ?string $key = NULL ): bool
	{
		$rule = $this->getRuleBySelector( $selector );
		if( !is_null( $rule ) ){
			if( is_null( $key ) )
				return TRUE;
			return $rule->hasPropertyByKey( $key );
		}
		return FALSE;
	}

	/**
	 *	Indicates whether a rule is existing by its selector.
	 *	@access		public
	 *	@param		string			$selector	Rule selector
	 *	@return		boolean
	 */
	public function hasRuleBySelector( string $selector ): bool
	{
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
	public function remove( string $selector, string $key ): bool
	{
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
	 *	@param		Rule		$rule		Rule object
	 *	@param		Property	$property	Property object
	 *	@return		boolean
	 */
	public function removeProperty( Rule $rule, Property $property ): bool
	{
		return $this->remove( $rule->getSelector(), $property->getKey() );
	}

	/**
	 *	Removes a rule.
	 *	@access		public
	 *	@param		Rule		$rule		Rule object
	 *	@return		boolean
	 */
	public function removeRule( Rule $rule ): bool
	{
		return $this->removeRuleBySelector( $rule->getSelector() );
	}

	/**
	 *	Removes a rule by its selector.
	 *	@access		public
	 *	@param		string			$selector		Rule selector
	 *	@return		boolean
	 */
	public function removeRuleBySelector( string $selector ): bool
	{
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
	 *	@param		string|NULL		$value			Property value
	 *	@return		boolean
	 */
	public function set( string $selector, string $key, ?string $value = NULL ): bool
	{
		if( $value === NULL || !strlen( $value ) )
			return $this->remove( $selector, $key );
		$rule = $this->getRuleBySelector( $selector );
		if( !$rule ){
			$rule	= new Rule( $selector );
			$this->rules[]	= $rule;
		}
		return $rule->setPropertyByKey( $key, $value );
	}

	/**
	 *	Sets a property.
	 *	@access		public
	 *	@param		Rule		$rule		Rule object
	 *	@param		Property	$property	Property object
	 *	@return		boolean
	 */
	public function setProperty( Rule $rule, Property $property ): bool
	{
		return $this->set( $rule->getSelector(), $property->getKey(), $property->getValue() );		//
	}
}
