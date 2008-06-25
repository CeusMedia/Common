<?php
/**
 *	Writer for short Log Files.
 *	@package		file.log
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.12.2006
 *	@version		0.1
 */
/**
 *	Writer for short Log Files.
 *	@package		file.log
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.12.2006
 *	@version		0.1
 */
class ShortLogWriter
{
	/*	@var		string		$patterns	Pattern Array filled with Logging Information */
	protected $patterns	= array();
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
		$this->uri	= $uri;
		$patterns	= array(
				time(),
				getEnv( 'REMOTE_ADDR'),
				getEnv( 'REQUEST_URI' ),
				getEnv( 'HTTP_REFERER' ),
				getEnv( 'HTTP_USER_AGENT' )
				);
		$this->setPatterns( $patterns );
	}

	/**
	 *	Adds an entry to the logfile.
	 *	@access		public
	 *	@param		string		$line		Entry to add to Log File
	 *	@return		bool
	 */
	public function note( $line = false)
	{
		if( is_array( $line ) )
			$line	= implode( "|", $line );
		$line	= str_replace( "\n", "\\n", $line );
		$entry	= implode( "|", array_values( $this->patterns ) );
		if( $line )
			$entry = $entry."|".$line;
		$entry	.= "\n";
		$fp = @fopen( $this->uri, "ab" );
		if( $fp )
		{
			@fwrite( $fp, $entry );
			@fclose( $fp );
			return true;
		}
		return false;
	}
	
	/**
	 *	Sets Pattern Array filled with Logging Information.
	 *
	 *	@access		public
	 *	@param		array		$array		Pattern Array filled with Logging Information
	 *	@return		void
	 */
	public function setPatterns( $array )
	{
		if( is_array( $array ) )
			$this->patterns	= $array;
	}
}
?>