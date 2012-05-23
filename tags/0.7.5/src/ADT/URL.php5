<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 *	@version		$Id$
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 *	@version		$Id$
 */
class ADT_URL
{
	public $scheme;
	public $address;
	public $separator		= "&";
	public static $staticUrlScheme	= "";
	public static $staticUrlAddress	= "./";
	
	public function __construct( $scheme, $address = NULL )
	{
		$scheme	= preg_replace( "/^url:/i", "", $scheme );
		if( $address === NULL && preg_match( "/^\S+:\S+$/", $scheme ) )
		{
			$parts		= explode( ":", $scheme );
			$scheme		= array_shift( $parts );
			$address	= preg_replace( "@^/*@", "", implode( ":", $parts ) );
		}
		$this->setScheme( $scheme );
		$this->setAddress( $address );
	}

	public function getScheme()
	{
		return $this->scheme;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function getUrl( $withPrefix = FALSE )
	{
		$url	= (string) $this;
		if( $withPrefix )
			$url	= "url:".$url;
		return $url;
	}
	
	/**
	 *	Builds URL Query String based on current URL Parameters extended by a Map of new Parameters ($mapSet) and reduced by a List of Parameters ($listRemove).
	 *	Note: You can also remove a Parameter by setting a new Parameter with value NULL.
	 *
	 *	@access		public
	 *	@param		array		$mapSet			Map of Parameters to append to URL
	 *	@param		array		$reset			List of Parameters to remove from URL
	 *	@param		string		$fragment		Fragment ID
	 *	@return		string		New URL.
	 */
	public function infer( $mapSet = array(), $listRemove = array(), $fragment = NULL )
	{
		$parameters	= $this->inferQueryString( $mapSet, $listRemove, $fragment );
		$parameters	= $parameters ? "?".$parameters : "";
		$parameters	.= $fragment ? "#".$fragment : "";
		return $this->getUrl().$parameters;
	}
	
	/**
	 *	Builds URL Query String based on current URL Parameters extended by a Map of new Parameters ($mapSet) and reduced by a List of Parameters ($listRemove).
	 *	Note: You can also remove a Parameter by setting a new Parameter with value NULL.
	 *
	 *	@access		public
	 *	@param		array		$mapSet			Map of Parameters to append to URL
	 *	@param		array		$reset			List of Parameters to remove from URL
	 *	@param		string		$fragment		Fragment ID
	 *	@return		string		New URL.
	 */
	public function inferStatic( $mapSet = array(), $listRemove = array(), $fragment = NULL )
	{
		$parameters	= self::inferQueryString( $mapSet, $listRemove, $fragment );
		$parameters	= $parameters ? "?".$parameters : "";
		$parameters	.= $fragment ? "#".$fragment : "";
		$url		= new ADT_URL( self::$staticUrlScheme, self::$staticUrlAddress );
		return $url->getUrl().$parameters;
	}
		
	/**
	 *	Builds URL based on current URL extended by a Map of new Parameters ($mapSet) and reduced by a List of Parameters ($listRemove).
	 *	Note: You can also remove a Parameter by setting a new Parameter with value NULL.
	 *
	 *	@access		public
	 *	@param		array		$mapSet			Map of Parameters to append to URL
	 *	@param		array		$reset			List of Parameters to remove from URL
	 *	@return		string		New URL.
	 */
	public static function inferQueryString( $mapSet = array(), $listRemove = array() )
	{
		$mapRequest	= $_GET;

		foreach( $mapSet as $key => $value )											// overwriting vars
			$mapRequest[$key] = $value;

		foreach( $listRemove as $key )													// unsetting vars
			unset( $mapRequest[$key] );	

		return http_build_query( $mapRequest, "test_", $this->separator );				// making link parameter string
	}

	public function setScheme( $scheme )
	{
		if( !preg_match( '/^([a-z0-9][a-z0-9-]{1,31})?$/i', $scheme ) )
			throw new InvalidArgumentException( 'Scheme "'.$scheme.'" is invalid.' );
		$this->scheme	= $scheme;
	}
	
	public function setAddress( $address )
	{
		$alpha		= 'a-z0-9';
		$others		= '()+,-.:=@;$_!*\\';
		$reserved	= '%\/?#';
		$trans		= '(['.$alpha.$others.$reserved.'])';
		$hex		= '(%[0-9a-f]{2})';
		if( !preg_match( '/^('.$trans.'|'.$hex.')+$/i', $address ) )
			throw new InvalidArgumentException( 'Address "'.$address.'" is invalid.' );
		$this->address	= $address;
	}
	
	public function __toString()
	{
		$prefix	= $this->scheme ? ( $this->scheme.":" ) : "";
		return $prefix.$this->address;
	}
}
?>