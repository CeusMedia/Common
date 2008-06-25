<?php
import ("de.ceus-media.alg.TimeConverter"); 
/**
 *	Writer for Log File.
 *	@package		file.log
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Writer for Log File.
 *	@package		file.log
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
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
	 *	Adds an Note to Log File.
	 *
	 *	@access		public
	 *	@param		string		$line		Entry to add to Log File
	 *	@return		bool
	 */
	public function note( $line, $format = "datetime" )
	{
		$converter 	= new Alg_TimeConverter();
		$time		= $format ? " [".$converter->convertToHuman( time(), $format )."]" : "";
		$message	= time().$time." ".$line."\n";
		return error_log( $message, 3, $this->uri );
	}
}
?>