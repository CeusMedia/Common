<?php
/**
 *	Builder for HTML Code of Country Flag Icon.
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2009
 *	@version		$Id$
 */
/**
 *	Builder for HTML Code of Country Flag Icon.
 *
 *	By default this class uses the CeuS Media Flag Icon Repository which uses the FAMFAMFAM Flags.
 *	If you are using this feature heavily please consider to use your own copy or icon set.
 *	You are allowed to use the CeuS Media Repository on a limited base.
 *	If your application is requesting too much images, we will contact you and you will have to find your own solution.
 *	Please keep in mind that this project will help you to start but your production is on your own.
 *	If you slow down our Image Server the community will suffer from your traffic.
 *
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2009
 *	@version		$Id$
 */
class UI_HTML_CountryFlagIcon
{
	public static $imageBaseUri		= "//icons.ceusmedia.com/famfamfam/flags/png/";
	public static $imageExtension	= "png";

	/** 
	 *	Builds HTML Code of Country Flag Icon statically.
	 *	@access		public
	 *	@param		string		$languageCode	Language Code or ISO Code of Country
	 *	@param		string		$title			Title and alternative Text of Image (needed for valid XHTML)
	 *	@param		string		$class			CSS Class
	 *	@return		string		HTML Code of Country Flag Icon
	 */
	public static function build( $languageCode, $title = NULL, $class = NULL )
	{
		if( !$languageCode )
			throw new InvalidArgumentException( 'No country language code given' );
		$languageCode	= self::transformCode( $languageCode );
		$url			= self::$imageBaseUri.$languageCode.".".self::$imageExtension;
		$code			= UI_HTML_Elements::Image( $url, $title, $class );
		return $code;
	}

	/**
	 *	Transforms special Language Codes to ISO Codes. To be overwritten.
	 *	@access		protected
	 *	@param		string		$languageCode	Language Code
	 *	@return		string
	 */
	protected static function transformCode( $languageCode )
	{
		$isoCode	= $languageCode;
		if( $languageCode == "en" || $languageCode == "uk" )
			$isoCode = "gb";
		if( $languageCode == "cs" )
			$isoCode = "cz";
		return $isoCode;
	}
}
?>