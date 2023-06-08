<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader and Parser for Tracker Log File.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_Log_Tracker
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Log\Tracker;

use CeusMedia\Common\FS\File\Log\ShortReader as LogShortReader;
use RuntimeException;

/**
 *	Reader and Parser for Tracker Log File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log_Tracker
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ShortReader extends LogShortReader
{
	/*	@var		array		$data			Array of Data from parsed Lines */
	protected $data		= [];

	/*	@var		string		$skip			Remote Address to skip (own Requests) */
	protected $skip;

	/*	@var		boolean		$isOpen			Internal status */
	protected $isOpen = FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri			URI of Log File to be parsed
	 *	@param		string		$skip			Remote Address to skip (own Requests)
	 *	@return		void
	 */
	public function __construct( string $uri, string $skip = "" )
	{
		parent::__construct( $uri );
		$this->skip	= $skip;
	}

	/**
	 *	Returns used Browsers of unique Visitors.
	 *	@access		public
	 *	@return 	array
	 *	@noinspection	PhpUnused
	 */
	public function getBrowsers(): array
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		$remote_addrs	= [];
		$browsers		= [];
		foreach( $this->data as $entry ){
			if( $entry['remote_addr'] != $this->skip && $entry['http_user_agent'] ){
				if( isset( $remote_addrs[$entry['remote_addr']] ) ){
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 ){
						if( isset( $browsers[$entry['http_user_agent']] ) )
							$browsers[$entry['http_user_agent']] ++;
						else
							$browsers[$entry['http_user_agent']]	= 1;
					}
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
				else{
					if( isset( $browsers[$entry['http_user_agent']] ) )
						$browsers[$entry['http_user_agent']] ++;
					else
						$browsers[$entry['http_user_agent']]	= 1;
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
			}
		}
		arsort( $browsers );
		return $browsers;
	}

	/**
	 *	Returns parsed Log Data as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getData(): array
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		return $this->data;
	}

	/**
	 *	Calculates Page View Average of unique Visitors.
	 *	@access		public
	 *	@return 	float
	 *	@noinspection	PhpUnused
	 */
	public function getPagesPerVisitor(): float
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		$remote_addrs	= [];
		$visitors		= [];
		$visitor		= 0;
		foreach( $this->data as $entry ){
			if( $entry['remote_addr'] != $this->skip ){
				if( isset( $remote_addrs[$entry['remote_addr']] ) ){
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 ){
						$visitor++;
						$visitors[$visitor]	= 0;
					}
					$visitors[$visitor] ++;
				}
				else{
					$visitor++;
					$visitors[$visitor]	= 1;
					$remote_addrs[$entry['remote_addr']] = $entry['timestamp'];
				}
			}
		}
		return round( array_sum( $visitors ) / count( $visitors ), 1 );
	}

	/**
	 *	Returns Referrers of unique Visitors.
	 *	@access		public
	 *	@param		string|NULL		$skip
	 *	@return 	array
	 *	@noinspection	PhpUnused
	 */
	public function getReferrers( ?string $skip = NULL ): array
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		$referrers		= [];
		foreach( $this->data as $entry ){
			if( $entry['remote_addr'] != $this->skip ){
				if( $entry['http_referer'] ){
					if( $skip && preg_match( "#.*".$skip.".*#si", $entry['http_referer'] ) )
						continue;
					if( isset( $referrers[$entry['http_referer']] ) )
						$referrers[$entry['http_referer']] ++;
					else
						$referrers[$entry['http_referer']]	= 1;
				}
			}
		}
		arsort( $referrers );
		return $referrers;
	}

	/**
	 *	Returns HTML of all tracked Requests.
	 *	@access		public
	 *	@param		int			$max		List Entries (0-all)
	 *	@param		int			$offset		...
	 *	@return 	string
	 *	@noinspection	PhpUnused
	 */
	public function getTable( int $max = 0, int $offset = 0 ): string
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		$data	= $this->data;
		if( $max )
			$data	= array_reverse( $data );

		$lines	= [];
		foreach( $data as $entry )
			if( $entry['remote_addr'] != $this->skip ) {
				if( $offset ) {
					$offset--;
					continue;
				}
				$lines[]	= "<tr><td>".$entry["timestamp"]."</td><td>".$entry['remote_addr']."</td><td>".$entry['request_uri']."</td><!--<td>".$entry['http_referer']."</td><td>".$entry['http_user_agent']."</td>--></tr>";
				if( $max && count( $lines ) >= $max )
					break;
			}
		if( $max )
			$lines	= array_reverse( $lines );
		$lines	= implode( "\n\t", $lines );
		return "<table>".$lines."</table>";
	}

	/**
	 *	Counts tracked unique Visitors.
	 *	@access		public
	 *	@return		int
	 *	@noinspection	PhpUnused
	 */
	public function getVisitors(): int
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		$remote_addrs	= [];
		$counter		= 0;
		foreach( $this->data as $entry ){
			if( $entry['remote_addr'] != $this->skip ){
				if( isset( $remote_addrs[$entry['remote_addr']] ) ){
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 )
						$counter ++;
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
				else{
					$counter ++;
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
			}
		}
		return $counter;
	}

	/**
	 *	Counts tracked Visits.
	 *	@access		public
	 *	@return		int
	 *	@noinspection	PhpUnused
	 */
	public function getVisits(): int
	{
		if( !$this->isOpen )
			throw new RuntimeException( "Log File not read" );
		return count( $this->data );
	}

	/**
	 *	Parses Log File.
	 *	@access		public
	 *	@return		void
	 */
	public function parse()
	{
		$this->read();
		foreach( $this->data as $nr => $line )
			$this->data[$nr]	= array_combine( $this->patterns, $line );
	}

	/**
	 *	Set already parsed Log Data (i.E. from serialized Cache File).
	 *	@access		public
	 *	@param		array		$data			Parsed Log Data
	 *	@return		self
	 */
	public function setData( array $data ): self
	{
		$this->data	= $data;
		$this->isOpen	= TRUE;
		return $this;
	}
}
