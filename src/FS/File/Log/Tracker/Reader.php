<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader and Parser for Tracker Log File.
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
 *	@package		CeusMedia_Common_FS_File_Log_Tracker
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Log\Tracker;

use CeusMedia\Common\FS\File\Log\Reader as LogReader;

/**
 *	Reader and Parser for Tracker Log File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Log_Tracker
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader extends LogReader
{
	/*	@var		string		$patterns		RegEx Patterns to parse Line */
	protected $patterns;

	/*	@var		string		$skip			Remote Address to skip (own Requests) */
	protected $skip;

	/*	@var		array		$data			Array of Data from parsed Lines */
	protected $data	= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$logFile		File Name of LogFile to parse
	 *	@param		string		$skip			Remote Address to skip (own Requests)
	 *	@param		bool		$autoParse		Flag: parse LogFile automatically
	 *	@return		void
	 */
	public function __construct( string $logFile, string $skip, bool $autoParse = FALSE )
	{
		parent::__construct( $logFile );
		$this->patterns	= "@^([0-9]+) \[([0-9:. -]+)\] ([a-z0-9:.-]+) (.*) (.*) \"(.*)\"$@si";
		$this->skip		= $skip;
		if( $autoParse )
			$this->parse();
	}

	/**
	 *	Callback for Line Parser.
	 *	@access		protected
	 *	@param		array		$matches		...
	 *	@return		string
	 */
	protected function callback( array $matches ): string
	{
//		print_m( $matches );
		$data	= array(
			'timestamp'		=> $matches[1],
			'datetime'		=> $matches[2],
			'remote_addr'	=> $matches[3],
			'request_uri'	=> $matches[4],
			'referer_uri'	=> $matches[5],
			'useragent'		=> $matches[6],
		);
		return serialize( $data );
	}

	/**
	 *	Returns used Browsers of unique Visitors.
	 *	@access		public
	 *	@return 	array
	 *	@noinspection	PhpUnused
	 */
	public function getBrowsers(): array
	{
		$remote_addrs	= [];
		$browsers		= [];
		foreach( $this->data as $entry ){
			if( $entry['remote_addr'] != $this->skip && $entry['useragent'] ){
				if( isset( $remote_addrs[$entry['remote_addr']] ) ){
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 ){
						if( isset( $browsers[$entry['useragent']] ) )
							$browsers[$entry['useragent']] ++;
						else
							$browsers[$entry['useragent']]	= 1;
					}
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
				else{
					if( isset( $browsers[$entry['useragent']] ) )
						$browsers[$entry['useragent']] ++;
					else
						$browsers[$entry['useragent']]	= 1;
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
	 *	@param		string|NULL		$skip			Remote Address to skip (own Requests)
	 *	@return 	array
	 *	@noinspection	PhpUnused
	 */
	public function getReferrers( ?string $skip ): array
	{
		$referrers		= [];
		foreach( $this->data as $entry ){
			if( $entry['remote_addr'] != $this->skip ){
				if( $entry['referer_uri'] ){
					if( $skip && preg_match( "#.*".$skip.".*#si", $entry['referer_uri'] ) )
						continue;
					if( isset( $referrers[$entry['referer_uri']] ) )
						$referrers[$entry['referer_uri']] ++;
					else
						$referrers[$entry['referer_uri']]	= 1;
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
	 *	@return 	string
	 *	@noinspection	PhpUnused
	 */
	public function getTable( int $max = 0): string
	{
		$lines	= [];
		$data	= $this->data;
		if( $max )
			$data	= array_reverse( $data );
		foreach( $data as $entry )
			if( $entry['remote_addr'] != $this->skip ){
				$lines[]	= "<tr><td>".$entry['datetime']."</td><td>".$entry['remote_addr']."</td><td>".$entry['request_uri']."</td><!--<td>".$entry['referer_uri']."</td>--><td>".$entry['useragent']."</td></tr>";
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
		$remote_addrs	= [];
		$counter	= 0;
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
		return count( $this->data );
	}

	/**
	 *	Parses Log File.
	 *	@access		public
	 *	@return		void
	 */
	public function parse()
	{
		$lines	= $this->read();
		foreach( $lines as $line )
			$this->data[]	= $this->parseLine( $line );
	}

	/**
	 *	Parses Log File.
	 *	@access		protected
	 *	@param		string		$line			Line to parse
	 *	@return		array
	 */
	protected function parseLine( string $line ): array
	{
		$data	= preg_replace_callback( $this->patterns, array( $this, 'callback' ), $line );
		return unserialize( $data );
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
		return $this;
	}
}
