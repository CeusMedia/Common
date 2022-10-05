<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Submits sitemap URL to Google webmaster tools.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_API_Google_Sitemap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			http://code.google.com/closure/compiler/
 */

namespace CeusMedia\Common\Net\API\Google\Sitemap;

use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Net\Reader as NetReader;
use Exception;
use InvalidArgumentException;

/**
 *	Submits sitemap URL to Google webmaster tools.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API_Google_Sitemap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			http://code.google.com/closure/compiler/
 */
class Submit
{

	/**	@var		string			$baseUrl		Base URL to Google webmaster tools */
	public static $baseUrl			= "https://www.google.com/webmasters/tools/ping?sitemap=";

	/**	@var		string|NULL		$lastError		Last error message if request went wrong */
	protected static $lastError		= NULL;

	/**
	 *	Returns error message of latest failed submit request.
	 *	@static
	 *	@access		public
	 *	@return		string|NULL	Latest error message or NULL if none stored
	 */
	static public function getLastError(): ?string
	{
		return self::$lastError;
	}

	/**
	 *	Sends sitemap URL to Google webmaster tools.
	 *	Stores error message if request went wrong.
	 *	@access		public
	 *	@static
	 *	@param		URL|string		$url			URL of sitemap to submit
	 *	@return		boolean		Result of request
	 */
	static public function submit( $url ): bool
	{
		if( $url instanceof URL )
			$url	= (string) $url;
		if( !is_string( $url ) )
			throw new InvalidArgumentException( 'URL must be string or instance of ADT\\URL' );
		try{
			NetReader::readUrl( self::$baseUrl.urlencode( $url ) );
		}
		catch( Exception $e ){
			self::$lastError	= $e->getMessage();
			return FALSE;
		}
		return TRUE;
	}
}
