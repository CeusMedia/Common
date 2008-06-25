<?php
import( 'de.ceus-media.file.log.LogFile' );
/**
 *	Reader for Log File.
 *	@package		file.log
 *	@extends		LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Reader for Log File.
 *	@package		file.log
 *	@extends		LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class LogFileReader extends LogFile
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@return		void
	 */
	public function __construct( $uri )
	{
		parent::__construct( $uri );
	}


	/**
	 *	Reads Log File and returns Lines.
	 *	@access		public
	 *	@return		array
	 */
	public function read()
	{
		if( !file_exists( $this->uri ) )
			throw new Exception( "Log File '".$this->uri."' is not existing." );
		if( $fcont = file( $this->uri ) )
		{
			$array = array();
			foreach( $fcont as $line )
				$array[] = trim( $line );
			return $array;
		}
		return array();
	}
}
?>