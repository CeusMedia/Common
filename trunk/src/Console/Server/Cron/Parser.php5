<?php
/**
 *	Cron Parser.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		Console.Server.Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.01.2006
 *	@version		$Id$
 */
/**
 *	Cron Parser.
 *	@category		cmClasses
 *	@package		Console.Server.Cron
 *	@uses			Console_Server_Cron_Job
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.01.2006
 *	@version		$Id$
 */
class Console_Server_Cron_Parser
{
	/**	@var		array		$jobs			Array of parse Cron Jobs */
	protected $jobs				= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		Message Log File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->parse( $fileName );
	}

	/**
	 *	Fills numbers with leading Zeros.
	 *	@access		protected
	 *	@param		string		$value			Number to be filled
	 *	@param		length		$int			Length to fill to
	 *	@return		string
	 */
	protected function fill( $value, $length )
	{
		if( $length && $value != "*" )
		{
			if( strlen( $value ) < $length )
			{
				$diff	= $length - strlen( $value );
				for( $i=0; $i<$diff; $i++ )
					$value	= "0".$value;
			}
		}
		return $value;
	}

	/**
	 *	Returns parsed Cron Jobs.
	 *	@access		public
	 *	@return		array
	 */
	public function getJobs()
	{
		return $this->jobs;
	}

	/**
	 *	Parses one numeric entry of Cron Job.
	 *	@access		protected
	 *	@param		string		$string		One numeric entry of Cron Job
	 *	@return		void
	 */
	protected function getValues( $value, $fill = 0 )
	{
		$values	= array();
		if( substr_count( $value, "-" ) )
		{
			$parts	= explode( "-", $value );
			$min	= trim( min( $parts ) );
			$max	= trim( max( $parts ) );
			for( $i=$min; $i<=$max; $i++ )
				$values[] = $this->fill( $i, $fill );
		}
		else if( substr_count( $value, "," ) )
		{
			$parts	= explode( ",", $value );
			foreach( $parts as $part )
				$values[]	= $this->fill( $part, $fill );
		}
		else $values[]	= $this->fill( $value, $fill );
		return $values;
	}

	/**
	 *	Parses Cron Tab File.
	 *	@access		protected
	 *	@param		string		$fileName		Cron Tab File
	 *	@return		void
	 */
	protected function parse( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "Cron Tab File '".$fileName."' is not existing." );
		$reader	= new File_Reader( $fileName );
		$lines	= $reader->readArray();
		$lines	= file( $fileName );
		foreach( $lines as $line )
			if( trim( $line ) && !preg_match( "@^#@", $line ) )
				$this->parseJob( $line );
	}

	/**
	 *	Parses one entry of Cron Tab File.
	 *	@access		protected
	 *	@param		string	$string		One entry of Cron Tab File
	 *	@return		void
	 */
	protected function parseJob( $string )
	{
		$pattern	= "@^( |\t)*(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(.*)(\r)?\n$@si";
		if( preg_match( $pattern, $string ) )
		{
			$match	= preg_replace( $pattern, "\\2|||\\4|||\\6|||\\8|||\\10|||\\12", $string );
			$match	= explode( "|||", $match );
			$job	= new Console_Server_Cron_Job( $match[5] );
			$job->setOption( "minute",	$this->getValues( $match[0], 2 ) );
			$job->setOption( "hour",	$this->getValues( $match[1], 2 ) );
			$job->setOption( "day",		$this->getValues( $match[2], 2 ) );
			$job->setOption( "month",	$this->getValues( $match[3], 2 ) );
			$job->setOption( "weekday",	$this->getValues( $match[4] ) );
			$this->jobs[]	= $job;
		}
	}
}
?>