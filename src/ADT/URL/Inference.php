<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 */

namespace CeusMedia\Common\ADT\URL;

use CeusMedia\Common\ADT\URL;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 */
class Inference extends URL
{
	public string $separator				= "&";
	public static string $staticAddress		= "./";
	public static string $staticScheme		= "";
	public static string $staticSeparator	= "&";

	/**
	 *	Builds URL Query String based on current URL Parameters extended by a Map of new Parameters ($mapSet) and reduced by a List of Parameters ($listRemove).
	 *	Note: You can also remove a Parameter by setting a new Parameter with value NULL.
	 *
	 *	@access		public
	 *	@param		array			$mapSet			Map of Parameters to append to URL
	 *	@param		array			$listRemove		List of Parameters to remove from URL
	 *	@param		string|NULL		$fragment		Fragment ID
	 *	@return		string			New URL.
	 */
	public function build( array $mapSet = [], array $listRemove = [], ?string $fragment = NULL ): string
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
	 *	@param		array		$listRemove		List of Parameters to remove from URL
	 *	@return		string		New URL.
	 */
	public function buildQueryString( array $mapSet = [], array $listRemove = [] ): string
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
	 *	@static
	 *	@param		array		$mapSet			Map of Parameters to append to URL
	 *	@param		array		$listRemove		List of Parameters to remove from URL
	 *	@return		string		New URL.
	 */
	public static function buildQueryStringStatic( array $mapSet = [], array $listRemove = [] ): string
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
	 *	@static
	 *	@param		array			$mapSet			Map of Parameters to append to URL
	 *	@param		array			$listRemove		List of Parameters to remove from URL
	 *	@param		string|NULL		$fragment		Fragment ID
	 *	@return		string			New URL.
	 */
	public static function buildStatic( array $mapSet = [], array $listRemove = [], ?string $fragment = NULL, bool $absolute = FALSE ): string
	{
		$parameters	= self::buildQueryStringStatic( $mapSet, $listRemove );
		$parameters	= $parameters ? "?".$parameters : "";
		$parameters	.= $fragment ? "#".$fragment : "";
		$url		= new URL( self::$staticScheme, self::$staticAddress );
		return $url->get( $absolute ).$parameters;
	}
}
