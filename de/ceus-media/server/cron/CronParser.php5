<?php
import( 'de.ceus-media.server.cron.CronJob' );
/**
 *	CronParser.
 *	@package		server
 *	@subpackage		cron
 *	@uses			CronJob
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.1
 */
/**
 *	CronParser.
 *	@package		server
 *	@subpackage		cron
 *	@uses			CronJob
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.1
 */
class CronParser
{
	/**	@var	array	$_jobs		Array of parse Cron Jobs */
	var $_jobs	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$_crontab	Cron Tab File
	 *	@param		string	$_log		Message Log File
	 *	@return		void
	 */
	public function __construct( $file = NULL )
	{
		if( NULL !== $file )
			$this->parse( $file );
	}
	
	/**
	 *	Parses Cron Tab File.
	 *	@access		public
	 *	@param		string	$file			Cron Tab File
	 *	@return		void
	 */
	function parse( $file )
	{
		if( file_exists( $file ) )
		{
			$lines	= file( $file );
			foreach( $lines as $line )
				if( trim( $line ) && !preg_match( "@^#@", $line ) )
					$this->_parseJob( $line );
		}
		else
			trigger_error( "File '".$file."' is not existing", E_USER_ERROR );
	}
	
	/**
	 *	Returns parsed Cron Jobs.
	 *	@access		public
	 *	@return		array
	 */
	function getJobs()
	{
		return $this->_jobs;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Parses one entry of Cron Tab File.
	 *	@access		public
	 *	@param		string	$string		One entry of Cron Tab File
	 *	@return		void
	 */
	function _parseJob( $string )
	{
		$pattern	= "@^( |\t)*(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(\*|[0-9,-]+)( |\t)+(.*)(\r)?\n$@si";
		if( preg_match( $pattern, $string ) )
		{
			$match	= preg_replace( $pattern, "\\2|||\\4|||\\6|||\\8|||\\10|||\\12", $string );
			$match	= explode( "|||", $match );
			$job	= new CronJob( $match[5] );
			$job->setOption( "minute",	$this->_getValues( $match[0], 2 ) );
			$job->setOption( "hour",	$this->_getValues( $match[1], 2 ) );
			$job->setOption( "day",		$this->_getValues( $match[2], 2 ) );
			$job->setOption( "month",	$this->_getValues( $match[3], 2 ) );
			$job->setOption( "weekday",	$this->_getValues( $match[4] ) );
			$this->_jobs[]	= $job;
		}
	}

	/**
	 *	Parses one numeric entry of Cron Job.
	 *	@access		public
	 *	@param		string	$string		One numeric entry of Cron Job
	 *	@return		void
	 */
	function _getValues( $value, $fill = 0 )
	{
		$values	= array();
		if( substr_count( $value, "-" ) )
		{
			$parts	= explode( "-", $value );
			$min		= trim( min( $parts ) );
			$max	= trim( max( $parts ) );
			for( $i=$min; $i<=$max; $i++ )
				$values[] = $this->_fill( $i, $fill );
		}
		else if( substr_count( $value, "," ) )
		{
			$parts	= explode( ",", $value );
			foreach( $parts as $part )
				$values[]	= $this->_fill( $part, $fill );
		}
		else $values[]	= $this->_fill( $value, $fill );
		return $values;
	}
	
	/**
	 *	Fills numbers with leading Zeros.
	 *	@access		public
	 *	@param		string	$value		Number to be filled
	 *	@param		length	$int			Length to fill to
	 *	@return		string
	 */
	function _fill( $value, $length )
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
}
?>