<?php
import( 'de.ceus-media.server.cron.Job' );
/**
 *	Cron Parser.
 *	@package		console.server.cron
 *	@uses			Console_Server_Cron_Job
 *	@uses			File_Reader
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.6
 */
/**
 *	Cron Parser.
 *	@package		console.server.cron
 *	@uses			Console_Server_Cron_Job
 *	@uses			File_Reader
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2006
 *	@version		0.6
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
		$lines	= reader->readArray();
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