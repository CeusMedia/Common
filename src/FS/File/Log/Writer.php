<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writer for Log File.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Log;

use CeusMedia\Common\Alg\Time\Converter as TimeConverter;

/**
 *	Writer for Log File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	/**	@var		string		$uri		URI of Log File */
	protected $uri;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@return		void
	 */
	public function __construct( string $uri )
	{
		$this->uri = $uri;
	}

	/**
	 *	Adds a Note to Log File.
	 *
	 *	@access		public
	 *	@param		string			$line		Entry to add to Log File
	 *	@param		string|NULL		$format		...
	 *	@return		bool
	 */
	public function note( string $line, ?string $format = "datetime" ): bool
	{
		$converter 	= new TimeConverter();
		$time		= $format ? " [".$converter->convertToHuman( time(), $format )."]" : "";
		$message	= time().$time." ".$line."\n";
		return error_log( $message, 3, $this->uri );
	}
}
