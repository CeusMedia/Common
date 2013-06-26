<?php
/**
 *	Submits sitemap URL to Google webmaster tools.
 *
 *	Copyright (c) 2013 Christian Würker (ceusmedia.com)
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
 *	@package		Net.API.Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@link			http://code.google.com/closure/compiler/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Submits sitemap URL to Google webmaster tools.
 *
 *	@category		cmClasses
 *	@package		Net.API.Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@link			http://code.google.com/closure/compiler/
 *	@since			0.7.6
 *	@version		$Id$
 */
class Net_API_Google_Sitemap_Submit
{

	/**	@var		string		$baseUrl		Base URL to Google webmaster tools */
	static public $baseUrl		= "http://www.google.com/webmasters/tools/ping?sitemap=";

	/**	@var		string		$lastError		Last error message if request went wrong */
	static protected $lastError	= NULL;

	/**
	 *	Returns error message of latest failed submit request.
	 *	@static
	 *	@access		public
	 *	@return		string|NULL	Latest error message or NULL if none stored
	 */
	static public function getLastError()
	{
		return self::$lastError;
	}

	/**
	 *	Sends sitemap URL to Google webmaster tools.
	 *	Stores error message if request went wrong.
	 *	@access		public
	 *	@static
	 *	@param		string		$url			URL of sitemap to submit
	 *	@return		boolean		Result of request
	 */
	static public function submit( $url )
	{
		if( $url instanceof ADT_URL )
			$url	= (string) $url;
		if( !is_string( $url ) )
			throw new InvalidArgumentException( 'URL must be string or instance of ADT_URL' );
		try
		{
			Net_Reader::readUrl( self::$baseUrl.urlencode( $url ) );
		}
		catch( Exception $e )
		{
			self::$lastError	= $e->getMessage();
			return FALSE;
		}
		return TRUE;
	}
}
?>
