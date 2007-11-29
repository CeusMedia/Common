<?php
/**
 *	Reader for Log File.
 *	@package		file.log
 *	@extends		LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.11.2007
 *	@version		0.1
 */
/**
 *	Reader for Log File.
 *	@package		file.log
 *	@extends		LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.11.2007
 *	@version		0.1
 */
class File_Log_Reader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@return		void
	 */
	public function __construct( $uri )
	{
		$this->uri = $uri;
	}


	/**
	 *	Reads Log File and returns Lines.
	 *	@access		public
	 *	@return		array
	 */
	public function read()
	{
		$lines = array();
		if( !file_exists( $this->uri ) )
			throw new Exception( "Log File '".$this->uri."' is not existing." );
		if( $fcont = file( $this->uri ) )
			foreach( $fcont as $line )
				$lines[] = trim( $line );
		return $lines;
	}
}
?>