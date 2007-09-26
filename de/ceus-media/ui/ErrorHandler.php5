<?php
import( 'de.ceus-media.adt.OptionObject' );
/**
 *	Error Handling to replace default php error handling.
 *	@package	ui
 *	@extends	OptionObject
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		31.07.2005
 *	@version		0.1
 */
/**
 *	Error Handling to replace default php error handling.
 *	@package	ui
 *	@extends	OptionObject
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		31.07.2005
 *	@version		0.1
 */
class ErrorHandler extends OptionObject
{
	/**	@var	array		$_templates		Message Templates for different Channels */
	var $_templates = array(
			"console"	=> array(
				E_USER_ERROR		=> "USER ERROR [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_USER_WARNING		=> "USER WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_USER_NOTICE		=> "USER NOTICE [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_CORE_WARNING	=> "CORE WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_CORE_ERROR		=> "CORE WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_NOTICE			=> "NOTICE [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_WARNING			=> "WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_ERROR			=> "ERROR [{errno}] {errstr} in line {errline} of file {errfile}\n",
			),
			"log"	=> array(
				E_USER_ERROR		=> "{timestamp} ({datetime}) USER ERROR [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_USER_WARNING		=> "{timestamp} ({datetime}) USER WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_USER_NOTICE		=> "{timestamp} ({datetime}) USER NOTICE [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_CORE_WARNING	=> "{timestamp} ({datetime}) CORE WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_CORE_ERROR		=> "{timestamp} ({datetime}) CORE WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_NOTICE			=> "{timestamp} ({datetime}) NOTICE [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_WARNING			=> "{timestamp} ({datetime}) WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_ERROR			=> "{timestamp} ({datetime}) ERROR [{errno}] {errstr} in line {errline} of file {errfile}\n",
			),
			"mail"	=> array(
				E_USER_ERROR		=> "USER ERROR [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_USER_WARNING		=> "USER WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_USER_NOTICE		=> "USER NOTICE [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_CORE_WARNING	=> "CORE WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_CORE_ERROR		=> "CORE WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_NOTICE			=> "NOTICE [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_WARNING			=> "WARNING [{errno}] {errstr} in line {errline} of file {errfile}\n",
				E_ERROR			=> "ERROR [{errno}] {errstr} in line {errline} of file {errfile}\n",
			),
			"html"	=> array(
				E_USER_ERROR		=> "<b>User Error</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
				E_USER_WARNING		=> "<b>User Warning</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
				E_USER_NOTICE		=> "<b>User Notice</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
				E_CORE_WARNING	=> "<b>Core Warning</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
				E_CORE_ERROR		=> "<b>Core Error</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
				E_NOTICE			=> "<b>Notice</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
				E_WARNING			=> "<b>Warning</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
				E_ERROR			=> "<b>Error</b> [{errno}] {errstr} in line {errline} of file {errfile}<br />\n",
			)
		);


	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$auto_start		Flag: start handler after construction without further configuration
	 *	@return		void
	 */
	public function __construct( $auto_start = false)
	{
		$this->setOption( 'channels',			array( 'html', 'log', 'mail' ) );
		$this->setOption( 'channel',			'html' );
		$this->setOption( 'debug',			false );
		$this->setOption( 'logfile',			getCwd().'/error.log' );
		$this->setOption( 'mail',				"Christian.Wuerker@CeuS-Media.de" );
		$this->setOption( 'subject',			"Critical Error" );
		$this->setOption( 'abort',			array( E_USER_ERROR, E_ERROR ) );
		$this->setOption( 'send',			array( E_USER_ERROR, E_ERROR ) );
		$this->setOption( 'format_datetime',	'd.m.Y - h:i:s' );
		$this->setOption( 'e_error',			true );
		$this->setOption( 'e_warning',		true );
		$this->setOption( 'e_parse',			true );
		$this->setOption( 'e_notice',			true );
		$this->setOption( 'e_core_error',		true );
		$this->setOption( 'e_core_warning',	true );
		$this->setOption( 'e_compile_error',	true );
		$this->setOption( 'e_compile_warning',	true );
		$this->setOption( 'e_user_error',		true );
		$this->setOption( 'e_user_warning',	true );
		$this->setOption( 'e_user_notice',		true );
		
		if( $auto_start )
			$this->startHandler();
	}

	/**
	 *	Stops current Execution immediatly.
	 *	@access		public
	 *	@return		void
	 */
	function abort()
	{
		exit( 0 );
	}

	/**
	 *	Returns decimal code for error reporting from binary switches.
	 *	@access		public
	 *	@return		int
	 */
	function getReporingCode( $binary = false)
	{
		$options = array_reverse($this->getOptions());
		$bin = "";
		foreach( $options as $key => $value )
			if( substr( $key, 0, 2 ) == "e_" )
				$bin .= (int)$value;

		if( $binary )
			return $bin;

		$dec = bindec( $bin );
		return $dec;
	}

	/**
	 *	Sends Mail to Developer.
	 *	@access		public
	 *	@return		void
	 */
	function send()
	{
		mail( $this->getOption( 'mail' ), $this->getOption( 'subject' ), $this->_implant( 'mail' ) );
	}

	/**
	 *	Start handler after finished configuration.
	 *	@access		public
	 *	@param		string		$channel		Channel for output
	 *	@return		int
	 */
	function startHandler()
	{
//		error_reporting( $this->getReporingCode() );
		set_error_handler( array( &$this, "_handleError" ) );				// auf die benutzerdefinierte Fehlerbehandlung umstellen
	}

	/**
	 *	Warns by Message.
	 *	@access		public
	 *	@return		void
	 */
	function warn()
	{
		print( $this->_implant() );
	}

	/**
	 *	Writes Messages to Log File.
	 *	@access		public
	 *	@return		void
	 */
	function log()
	{
		error_log( $this->_implant( 'log' ), 3, $this->getOption( 'logfile' ) );
	}
	
	/**
	 *	Handles errors of all kind.
	 *	@access		private
	 *	@param		int			$errno		Error number
	 *	@param		string		$errstr		Error message
	 *	@param		string		$errfile		File were error occured
	 *	@param		int			$errline		Line were error occured
	 *	@return		void
	 */
	function _handleError($errno, $errstr, $errfile, $errline)				// Fehlerbehandlungsfunktion
	{
		global $HTTP_HOST, $HTTP_USER_AGENT, $REMOTE_ADDR, $REQUEST_URI;
		$this->setOption( 'datetime', date($this->getOption( 'format_datetime' ) ) );
		$this->setOption( 'timestamp', time() );
		$this->setOption( 'errno',	$errno );
		$this->setOption( 'errstr',	$errstr );
		$this->setOption( 'errfile',	$errfile );
		$this->setOption( 'errline',	$errline );
		$this->setOption( 'HTTP_HOST',			$HTTP_HOST );
		$this->setOption( 'HTTP_USER_AGENT',	$HTTP_USER_AGENT );
		$this->setOption( 'REMOTE_ADDR',		$REMOTE_ADDR );
		$this->setOption( 'REQUEST_URI',		$REQUEST_URI );
		
		if( $this->getOption( 'logfile' ) )
			$this->log();
		if ( $this->getOption( 'mail' ) && in_array( $errno, $this->getOption( 'send' ) ) )
			$this->send();
		$code = $this->getReporingCode();
		if($errno <= $code)
		{
			$code	= decbin($code);
			$nulls	= substr_count( decbin( $errno), "0" ) + 1;
			$pos	= strlen( $code ) - $nulls;
			if(substr( $code, $pos, 1) || $this->getOption( 'debug' ))
				$this->warn();
		}
		if( in_array( $errno, $this->getOption( 'abort' ) ) )
			$this->abort();
	}

	/**
	 *	Integrates all information into the channel templates.
	 *	@access		private
	 *	@param		string		$channel		Channel for output
	 *	@return		int
	 */
	function _implant( $channel = false)
	{
		if( !$channel || !in_array( $channel, $this->getOption( 'channels' ) ) )
			$channel = $this->getOption( 'channel' );
		$code	= $this->_templates[$channel][$this->getOption( 'errno' ) ];
		$options	= $this->getOptions();
		$keys	= array();
		$value	= array();
		foreach( $options as $key => $value)
			if( !is_array( $value ) )
			{
				$keys[]	= "{".$key."}";
				$values[]	= $value;
			}
		$code = str_replace( $keys, $values, $code );
		return $code;
	}
}
?>