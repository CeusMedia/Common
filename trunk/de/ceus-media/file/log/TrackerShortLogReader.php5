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
 *	@extends		ShortLogReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.12.2006
 *	@version		$Id$
 */
import( 'de.ceus-media.file.log.ShortLogReader' );
/**
 *	Reader and Parser for Tracker Log File.
 *	@category		cmClasses
 *	@package		file.log
 *	@extends		ShortLogReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.12.2006
 *	@version		$Id$
 */
class TrackerShortLogReader extends ShortLogReader
{
	/*	@var		array		$data			Array of Data from parsed Lines */
	protected $data	= array();
	/*	@var		string		$skip			Remote Address to skip (own Requests) */
	protected $skip;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri			URI of Log File to be parsed
	 *	@param		string		$skip			Remote Address to skip (own Requests)
	 *	@return		void
	 */
	public function __construct( $uri, $skip = "" )
	{
		parent::__construct( $uri );
		$this->skip	= $skip;
	}
	
	/**
	 *	Returns parsed Log Data as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getData()
	{
		if( $this->_open )
			return $this->data;
//		print_m( debug_backtrace() );
		trigger_error( "Log File not read", E_USER_ERROR );
		return array();
	}
	
	/**
	 *	Counts tracked Visits.
	 *	@access		public
	 *	@return		int
	 */
	public function getVisits()
	{
		if( $this->_open )
			return count( $this->data );
		trigger_error( "Log File not read", E_USER_ERROR );
		return array();
	}
	
	/**
	 *	Counts tracked unique Visitors.
	 *	@access		public
	 *	@return		int
	 */
	public function getVisitors()
	{
		if( !$this->_open )
		{
			trigger_error( "Log File not read", E_USER_ERROR );
			return 0;
		}
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
		if( !$this->_open )
		{
			trigger_error( "Log File not read", E_USER_ERROR );
			return array();
		}
		$remote_addrs	= array();	
		$browsers		= array();
		foreach( $this->data as $entry )
		{
			if( $entry['remote_addr'] != $this->skip && $entry['http_user_agent'] )
			{
				if( isset( $remote_addrs[$entry['remote_addr']] ) )
				{
					if( $remote_addrs[$entry['remote_addr']] < $entry['timestamp'] - 30 * 60 )
					{
						if( isset( $browsers[$entry['http_user_agent']] ) )
							$browsers[$entry['http_user_agent']] ++;
						else
							$browsers[$entry['http_user_agent']]	= 1;
					}
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
				else
				{
					if( isset( $browsers[$entry['http_user_agent']] ) )
						$browsers[$entry['http_user_agent']] ++;
					else
						$browsers[$entry['http_user_agent']]	= 1;
					$remote_addrs[$entry['remote_addr']]	= $entry['timestamp'];
				}
			}
		}
		arsort( $browsers );
		$lines	= array();
		foreach( $browsers as $browser => $count )
			$lines[]	= "<tr><td>".$browser."</td><td>".$count."</td></tr>";
		$lines		= implode( "\n\t", $lines );
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
		if( !$this->_open )
		{
			trigger_error( "Log File not read", E_USER_ERROR );
			return array();
		}
		$referers		= array();
		foreach( $this->data as $entry )
		{
			if( $entry['remote_addr'] != $this->skip )
			{
				if( $entry['http_referer'] && !preg_match( "#.*".$skip.".*#si", $entry['http_referer'] ) )
				{
					if( isset( $referers[$entry['http_referer']] ) )
						$referers[$entry['http_referer']] ++;
					else
						$referers[$entry['http_referer']]	= 1;
				}
			}
		}
		arsort( $referers );
		$lines	= array();
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
		if( !$this->_open )
		{
			trigger_error( "Log File not read", E_USER_ERROR );
			return 0;
		}
		$remote_addrs	= array();	
		$visitors			= array();
		$visitor	= 0;
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
	 *	@return 	string
	 */
	public function getTable( $max = 0, $offset = 0 )
	{
		if( !$this->_open )
		{
			trigger_error( "Log File not read", E_USER_ERROR );
			return array();
		}
		$data	= $this->data;
		if( $max )
			$data	= array_reverse( $data );
			
		foreach( $data as $entry )
			if( $entry['remote_addr'] != $this->skip )
			{
				if( $offset )
				{
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
			$this->data[]	= array_combine( $this->_pattern, $line );
	}
	
	/**
	 *	Set already parsed Log Data (i.E. from serialized Cache File).
	 *	@access		public
	 *	@param		array		$data			Parsed Log Data
	 *	@return		void
	 */
	public function setData( $data )
	{
		$this->data	= $data;
		$this->_open	= true;
	}
}
?>