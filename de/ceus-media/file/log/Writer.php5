<?php
import ("de.ceus-media.adt.TimeConverter"); 
/**
 *	Writer for Log File.
 *	@package		file.log
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Writer for Log File.
 *	@package		file.log
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_Log_Writer
{
	/**	@var		string		$uri		URI of Log File */
	protected $uri;

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
	 *	Adds an entry to the logfile.
	 *
	 *	@access		public
	 *	@param		string		$line		Entry to add to Log File
	 *	@return		void
	 */
	public function note( $line )
	{
		$converter 	= new TimeConverter();
		$message	= time()." [".$converter->convertToHuman( time(), "datetime" )."] ".$line."\n";
		error_log( $message, 3, $this->uri );
	}
}
?>