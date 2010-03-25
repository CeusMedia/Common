<?php
/**
 *	Reader and Parser for Tracker Log File.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		file.log
 *	@extends		LogFileReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		$Id$
 */
import( 'de.ceus-media.file.log.LogFileReader' );
/**
 *	Reader and Parser for Tracker Log File.
 *	@category		cmClasses
 *	@package		file.log
 *	@extends		LogFileReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		$Id$
 */
class TrackerLogReader extends LogFileReader
{
	/*	@var		string		$patterns		RegEx Patterns to parse Line */
	protected $patterns;
	/*	@var		string		$skip			Remote Address to skip (own Requests) */
	protected $skip;
	/*	@var		array		$data			Array of Data from parsed Lines */
	protected $data	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$logfile		File Name of LogFile to parse
	 *	@param		string		$skip			Remote Address to skip (own Requests)
	 *	@param		bool		$auto_parse		Flag: parse LogFile automaticly
	 *	@return		void
	 */
	public function __construct( $logFile, $skip, $autoParse = false )
	{
		parent::__construct( $logFile );
		$this->patterns	= "@^([0-9]+) \[([0-9:. -]+)\] ([a-z0-9:.-]+) (.*) (.*) \"(.*)\"$@si";
		$this->skip		= $skip;
		if( $autoParse )
			$this->parse();
	}
	
	/**
	 *	Returns parsed Log Data as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 *	Counts tracked Visits.
	 *	@access		public
	 *	@return		int
	 */
	public function getVisits()
	{
		return count( $this->data );
	}
	
	/**
	 *	Counts tracked unique Visitors.
	 *	@access		public
	 *	@return		int
	 */
	public function getVisitors()
	{
		$remote_addrs	= array();	
		$counter	= 0;
		foreach( $this->data as $entry )
		{
			if( $entry['remote_addr'] != $this->skip )
			{
				if( isset( $remote_addrs[$entry['remote_addr']] ) )
				{
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 )
						$counter ++;
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
				else
				{
					$counter ++;
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
			}
		}
		return $counter;
	}
	
	/**
	 *	Returns used Browsers of unique Visitors.
	 *	@access		public
	 *	@return 	array
	 */
	public function getBrowsers()
	{
		$remote_addrs	= array();	
		$browsers		= array();
		foreach( $this->data as $entry )
		{
			if( $entry['remote_addr'] != $this->skip && $entry['useragent'] )
			{
				if( isset( $remote_addrs[$entry['remote_addr']] ) )
				{
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 )
					{
						if( isset( $browsers[$entry['useragent']] ) )
							$browsers[$entry['useragent']] ++;
						else
							$browsers[$entry['useragent']]	= 1;
					}
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
				else
				{
					if( isset( $browsers[$entry['useragent']] ) )
						$browsers[$entry['useragent']] ++;
					else
						$browsers[$entry['useragent']]	= 1;
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
			}
		}
		arsort( $browsers );
		foreach( $browsers as $browser => $count )
			$lines[]	= "<tr><td>".$browser."</td><td>".$count."</td></tr>";
		$lines	= implode( "\n\t", $lines );
		$content	= "<table>".$lines."</table>";
		return $content;
	}
	
	/**
	 *	Returns Referers of unique Visitors.
	 *	@access		public
	 *	@return 	array
	 */
	public function getReferers( $skip )
	{
		$referers		= array();
		foreach( $this->data as $entry )
		{
			if( $entry['remote_addr'] != $this->skip )
			{
				if( $entry['referer_uri'] && !preg_match( "#.*".$skip.".*#si", $entry['referer_uri'] ) )
				{
					if( isset( $referers[$entry['referer_uri']] ) )
						$referers[$entry['referer_uri']] ++;
					else
						$referers[$entry['referer_uri']]	= 1;
				}
			}
		}
		arsort( $referers );
		foreach( $referers as $referer => $count )
			$lines[]	= "<tr><td>".$referer."</td><td>".$count."</td></tr>";
		$lines	= implode( "\n\t", $lines );
		$content	= "<table>".$lines."</table>";
		return $content;
	}

	/**
	 *	Calculates Page View Average of unique Visitors.
	 *	@access		public
	 *	@return 	float
	 */
	public function getPagesPerVisitor()
	{
		$remote_addrs	= array();	
		$visitors		= array();
		$visitor		= 0;
		foreach( $this->data as $entry )
		{
			if( $entry['remote_addr'] != $this->skip )
			{
				if( isset( $remote_addrs[$entry['remote_addr']] ) )
				{
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 )
					{
						$visitor++;
						$visitors[$visitor]	= 0;
					}
					$visitors[$visitor] ++;
				}
				else
				{
					$visitor++;
					$visitors[$visitor]	= 1;
					$remote_addrs[$entry['remote_addr']] = $entry['timestamp'];
				}
			}
		}
		$total	= 0;
		foreach( $visitors as $visitor => $pages )
			$total	+= $pages;
		$pages	= round( $total / count( $visitors ), 1 );
		return $pages;
	}

	/**
	 *	Returns HTML of all tracked Requests.
	 *	@access		public
	 *	@param		int			$max		List Entries (0-all)
	 *	@return 	array
	 */
	public function getTable( $max = 0)
	{
		$data	= $this->data;
		if( $max )
			$data	= array_reverse( $data );
		foreach( $data as $entry )
			if( $entry['remote_addr'] != $this->skip )
			{
				$lines[]	= "<tr><td>".$entry['datetime']."</td><td>".$entry['remote_addr']."</td><td>".$entry['request_uri']."</td><!--<td>".$entry['referer_uri']."</td>--><td>".$entry['useragent']."</td></tr>";
				if( $max && count( $lines ) >= $max )
					break;
			}
		if( $max )
			$lines	= array_reverse( $lines );
		$lines	= implode( "\n\t", $lines );
		$content	= "<table>".$lines."</table>";
		return $content;
	}

	/**
	 *	Parses Log File.
	 *	@access		public
	 *	@return		void	 
	 */
	public function parse()
	{
		$i=0;
		$lines	= $this->read();
		foreach( $lines as $line )
			$this->data[]	= $this->parseLine( $line );
	}
	
	/**
	 *	Set already parsed Log Data (i.E. from serialized Cache File).
	 *	@access		public
	 *	@param		array		data			Parsed Log Data
	 *	@return		void
	 */
	public function setData( $data )
	{
		$this->data	= $data;
	}

	/**
	 *	Parses Log File.
	 *	@access		protected
	 *	@return		array
	 */
	protected function parseLine( $line )
	{
		$data	= preg_replace_callback( $this->patterns, array( $this, 'callback' ), $line );
		return unserialize( $data );
	}

	/**
	 *	Callback for Line Parser.
	 *	@access		protected
	 *	@return		string
	 */
	protected function callback( $matches )
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
}
?>