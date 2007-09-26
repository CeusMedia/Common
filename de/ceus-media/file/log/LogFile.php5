<?php
import ("de.ceus-media.adt.TimeConverter"); 
/**
 *	Writer for Log File.
 *	@package		file
 *	@subpackage		log
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Writer for Log File.
 *	@package		file
 *	@subpackage		log
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class LogFile
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
	 *	@return		bool
	 */
	public function addEntry( $line )
	{
		$tc = new TimeConverter();
		$entry = time()." [".$tc->convertToHuman( time(), "datetime" )."] ".$line."\n";

		$fp = @fopen( $this->uri, "ab" );
		if( $fp )
		{
			@fwrite( $fp, $entry );
			@fclose( $fp );
			return true;
		}
		return false;
	}
}
?>