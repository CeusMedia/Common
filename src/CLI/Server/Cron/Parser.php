<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Cron Parser.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Server_Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Server\Cron;

use Exception;

/**
 *	Cron Parser.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Server_Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Parser
{
	/**	@var		array		$jobs			Array of parse Cron Jobs */
	protected array $jobs		= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		Message Log File
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( string $fileName )
	{
		$this->parse( $fileName );
	}

	/**
	 *	Fills numbers with leading Zeros.
	 *	@access		protected
	 *	@param		string		$value			Number to be filled
	 *	@param		integer		$length			Length to fill to
	 *	@return		string
	 */
	protected function fill( string $value, int $length ): string
	{
		if( $length && $value != "*" ){
			if( strlen( $value ) < $length ){
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
	public function getJobs(): array
	{
		return $this->jobs;
	}

	/**
	 *	Parses one numeric entry of Cron Job.
	 *	@access		protected
	 *	@param		string		$value		One numeric entry of Cron Job
	 *	@param		integer		$fill		Length to fill to
	 *	@return		array
	 */
	protected function getValues( string $value, int $fill = 0 ): array
	{
		$values	= [];
		if( substr_count( $value, "-" ) ){
			$parts	= explode( "-", $value );
			$min	= trim( min( $parts ) );
			$max	= trim( max( $parts ) );
			for( $i=$min; $i<=$max; $i++ )
				$values[] = $this->fill( $i, $fill );
		}
		else if( substr_count( $value, "," ) ){
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
	 *	@throws		Exception
	 */
	protected function parse( string $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "Cron Tab File '".$fileName."' is not existing." );
		foreach( file( $fileName ) as $line )
			if( trim( $line ) && !preg_match( "@^#@", $line ) )
				$this->parseJob( $line );
	}

	/**
	 *	Parses one entry of Cron Tab File.
	 *	@access		protected
	 *	@param		string	$string		One entry of Cron Tab File
	 *	@return		void
	 */
	protected function parseJob( string $string )
	{
		$pattern	= "@^( |\t)*(\*|[\d,-]+)( |\t)+(\*|[\d,-]+)( |\t)+(\*|[\d,-]+)( |\t)+(\*|[\d,-]+)( |\t)+(\*|[\d,-]+)( |\t)+(.*)(\r)?\n$@si";
		if( preg_match( $pattern, $string ) ){
			$match	= preg_replace( $pattern, "\\2|||\\4|||\\6|||\\8|||\\10|||\\12", $string );
			$match	= explode( "|||", $match );
			$job	= new Job( $match[5] );
			$job->setOption( "minute",	$this->getValues( $match[0], 2 ) );
			$job->setOption( "hour",	$this->getValues( $match[1], 2 ) );
			$job->setOption( "day",		$this->getValues( $match[2], 2 ) );
			$job->setOption( "month",	$this->getValues( $match[3], 2 ) );
			$job->setOption( "weekday",	$this->getValues( $match[4] ) );
			$this->jobs[]	= $job;
		}
	}
}
