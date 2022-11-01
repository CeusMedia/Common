<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Calculates real Time by Server time and synchronised Atom time.
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
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	Calculates real Time by Server time and synchronised Atom time.
 *	@category		Library
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class AtomServerTime
{
	/**	@var		string			$syncFile		URI of File with synchronized atom time */
	protected string $syncFile		= "";

	/**	@var		int				$syncTime		Timestamp of last synchronisation */
	protected int $syncTime			 = 0;

	/**	@var		int				$syncDiff		Time difference between server time and atom time */
	protected int $syncDiff			= 0;

	/**	@var		int				$refreshTime	Time distance in seconds for synchronisation update */
	protected int $refreshTime		= 86400;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File with synchronized atom time
	 *	@param		int			$refreshTime	Time distance in seconds for synchronisation update
	 *	@return		void
	 *	@throws		IoException
	 */
	public function __construct( string $fileName = "AtomServerTime.diff", int $refreshTime = 0 )
	{
		$this->syncFile = $fileName;
		if( $refreshTime )
			$this->refreshTime = $refreshTime;
		$this->synchronize();
	}

	/**
	 *	Synchronizes server time with atom time by saving time difference.
	 *	@access		protected
	 *	@return		void
	 *	@throws		IoException
	 */
	protected function synchronize()
	{
		if( file_exists( $this->syncFile ) ){
			$time	= filemtime( $this->syncFile );
			if( ( time() - $time ) < $this->refreshTime ){
				$this->syncTime	= $time;
				$this->syncDiff	= (int) FileReader::load( $this->syncFile );
				return;
			}
		}
		$this->syncTime	= time();
		$this->syncDiff	= $this->syncTime - AtomTime::getTimestamp();
		FileWriter::save( $this->syncFile, (string) $this->syncDiff );
		touch( $this->syncFile );
	}

	/**
	 *	Returns timestamp of last synchronisation.
	 *	@access		public
	 *	@return		int
	 */
	public function getSyncTime(): int
	{
		return $this->syncTime;
	}

	/**
	 *	Returns date of last synchronisation as formatted string.
	 *	@access		public
	 *	@param		string		$format			Date format
	 *	@return		string
	 */
	public function getSyncDate( string $format = "d.m.Y - H:i:s" ): string
	{
		return date( $format, $this->syncTime );
	}

	/**
	 *	Returns time difference between server time and atom time.
	 *	@access		public
	 *	@return		int
	 */
	public function getSyncDiff(): int
	{
		return $this->syncDiff;
	}

	/**
	 *	Returns timestamp.
	 *	@access		public
	 *	@return		int
	 */
	public function getTimestamp(): int
	{
		return  time() + $this->syncDiff;
	}

	/**
	 *	Returns date as formatted string.
	 *	@access		public
	 *	@param		string		$format			Date format
	 *	@return		string
	 *	@link		http://www.php.net/date
	 */
	public function getDate( string $format = "d.m.Y - H:i:s" ): string
	{
		return date( $format, time() + $this->syncDiff );
	}
}
