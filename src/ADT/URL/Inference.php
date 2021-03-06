<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 */
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 */
class ADT_URL_Inference extends ADT_URL
{
	public $separator		= "&";
	public static $staticAddress	= "./";
	public static $staticScheme		= "";
	public static $staticSeparator	= "&";

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
	public function build( $mapSet = array(), $listRemove = array(), $fragment = NULL )
	{
		$parameters	= $this->buildQueryString( $mapSet, $listRemove );
		$parameters	= $parameters ? "?".$parameters : "";
		$parameters	.= $fragment ? "#".$fragment : "";
		return $this->get( $this->isAbsolute() ).$parameters;
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
	public function buildQueryString( $mapSet = array(), $listRemove = array() )
	{
		$mapRequest	= $_GET;

		// overwriting vars
		foreach( $mapSet as $key => $value )
			$mapRequest[$key] = $value;

		// unsetting vars
		foreach( $listRemove as $key )
			unset( $mapRequest[$key] );

		// making link parameter string
		return http_build_query( $mapRequest, "test_", $this->separator );
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
	public static function buildQueryStringStatic( $mapSet = array(), $listRemove = array() )
	{
		$mapRequest	= $_GET;

		// overwriting vars
		foreach( $mapSet as $key => $value )
			$mapRequest[$key] = $value;

		// unsetting vars
		foreach( $listRemove as $key )
			unset( $mapRequest[$key] );

		// making link parameter string
		return http_build_query( $mapRequest, "", self::$staticSeparator );
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
	public function buildStatic( $mapSet = array(), $listRemove = array(), $fragment = NULL )
	{
		$parameters	= self::buildQueryStringStatic( $mapSet, $listRemove );
		$parameters	= $parameters ? "?".$parameters : "";
		$parameters	.= $fragment ? "#".$fragment : "";
		$url		= new ADT_URL( self::$staticScheme, self::$staticAddress );
		return $url->getUrl().$parameters;
	}
}
