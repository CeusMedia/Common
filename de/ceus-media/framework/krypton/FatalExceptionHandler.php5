<?php
/**
 *	Shows an Exception and quits.
 *	@package		framework.krypton
 *	@uses			Net_Mail_PlainMail
 *	@uses			Net_Mail_Sender
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2007
 *	@version		0.2
 */
/**
 *	Shows an Exception and quits.
 *	@package		framework.krypton
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@uses			Net_Mail_PlainMail
 *	@uses			Net_Mail_Sender
 *	@since			22.04.2007
 *	@version		0.2
 */
class Framework_Krypton_FatalExceptionHandler
{
	/**	@var		string		$break			Line Break Symbol */
	var $break		= "\n";
	/**	@var		string		$line			Line Symbol */
	var $line		= "";
	/**	@var		string		$errorPage		File Name of Error Page Template */
	var $errorPage	= "";
	/**	@var		string		$mail			Mail Address to send Report to */
	var $mail		= "";
	/**	@var		string		$template		File Name of Template to load */
	var $template	= "contents/templates/fatalException.phpt";
	/**	@var		string		$lastFileUrl	URL of last Report File */
	protected $lastFileUrl	= "";

	/**
	 *	Constructur, shows Exception and quits.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( Exception $e = NULL )
	{
		$EXCEPTION_DEBUG_MODE	= defined( 'EXCEPTION_DEBUG_MODE' ) && EXCEPTION_DEBUG_MODE;
		$EXCEPTION_ERROR_PAGE	= defined( 'EXCEPTION_ERROR_PAGE' ) && EXCEPTION_ERROR_PAGE;
		$EXCEPTION_MAIL_TO		= defined( 'EXCEPTION_MAIL_TO' ) && EXCEPTION_MAIL_TO;
		
		$this->setMail( $EXCEPTION_MAIL_TO ? EXCEPTION_MAIL_TO : "" );
		$this->setErrorPage( ( $EXCEPTION_ERROR_PAGE && !$EXCEPTION_DEBUG_MODE ) ? EXCEPTION_ERROR_PAGE : "" );
		if( $e )
		{
			$this->handleException( $e );
		}
	}
	
	/**
	 *	Handles Exception by creating, storing and mailing a Report and showing the Report or a static Error Page.
	 *	@access		public
	 *	@param		Exception		$e			Exception to handle
	 *	@return		string
	 */
	public function handleException( $e )
	{
		$report	= $this->buildReport( $e );
		if( $this->mail )
		{
			import( 'de.ceus-media.net.mail.PlainMail' );
			import( 'de.ceus-media.net.mail.Sender' );
			$mail	= new Net_Mail_PlainMail();
			$mail->setReceiver( $this->mail );
			$mail->setSubject( "[".getEnv( 'HTTP_HOST' )."] Exception: ".$e->getMessage() );
			$mail->setBody( $report );
			$mail->setSender( $this->mail );
			try
			{
				@Net_Mail_Sender::sendMail( $mail );
			}
			catch( Exception $e )
			{
				
			}
		}
		if( $this->errorPage )
		{
			$content	= require_once( $this->errorPage );
			die( $content );
		}
		return $report;
	}
	
	/**
	 *	Builds Report from Exception.
	 *	@access		protected
	 *	@param		Exception		$e			Exception to handle
	 *	@return		string
	 */
	protected function buildReport( $e )
	{
		$dev	= "";
		if( ob_get_level() )
			$dev	= ob_get_clean();

		$html	= !getEnv( 'PROMPT' ) && !getEnv( 'SHELL' );
		if( !$html )
		{
			ob_start();
			$this->showException( $e );
			return ob_get_clean();
		}

		$this->break	= $html ? "<br/>\n" : "\n";
		$this->line		= $html ? "<hr/>\n" : str_repeat( "-", 70 ).$this->break;

		ob_start();
		$this->showException( $e );	
		$source	= ob_get_clean();
		$source	= str_replace( "<hr/>", str_repeat( "-", 70 ), $source );
		$source	= str_replace( "<br/>", "", $source );
		ob_start();
		$this->showException( $e, "error" );	
		$error	= ob_get_clean();
		ob_start();
		$this->showException( $e, "trace" );	
		$trace	= ob_get_clean();
		$trace	= str_replace( getCwd(), "", $trace );

		$error	= str_replace( ": ", "</td><td>", $error );
		$error	= str_replace( "<br/>", "</td></tr>\n  <tr><td>", $error );
		$error	= "<table class='grid'>\n  <tr><td>".$error."</td></tr>\n</table>";
		$error	= str_replace( "<tr><td>\n</td></tr>", "", $error );
		
		ob_start();
		$this->showLog( "logs/database/errors.log", 5 );
		$errorLog	= ob_get_clean();

		ob_start();
		$this->showLog( "logs/database/queries.log", 5 );
		$dbLog	= ob_get_clean();

		ob_start();
		$this->showLog( "logs/autoload.log", 5 );
		$load	= ob_get_clean();

		$request	= "No Request set.";
		if( isset( $_REQUEST ) )
		{
			ob_start();
			print_m( $_REQUEST );
			$request	= ob_get_clean();
		}

		$session	= "No Session opened.";
		if( isset( $_SESSION ) )
		{
			ob_start();
			print_m( $_SESSION );
			$session	= ob_get_clean();
		}

		$cookie		= "No Session set.";
		if( isset( $_COOKIE ) )
		{
			ob_start();
			print_m( $_COOKIE );
			$cookie		= ob_get_clean();
		}

		$fileName	= date( "y.m.d-H.i.s" )."_".getEnv( 'REMOTE_ADDR' ).".html";
		$filePath	= "logs/exceptions/";
		$fileUrl	= $filePath.$fileName;

		$this->lastFileUrl	= $fileUrl;

		$body	= require_once( $this->template );
		return $body;
	}
	
	/**
	 *	Sets File Name of Template.
	 *	@access		public
	 *	@param		string		$template	Template to load
	 *	@return		void
	 */
	public function setTemplate( $template )
	{
		$this->template	= $template;
	}

	/**
	 *	Sets File Name of Error Page Template. If set the Handler will (report by mail and) show the Error Page only.
	 *	@access		public
	 *	@param		string		$fileName	File Name of Error Page
	 *	@return		void
	 */
	public function setErrorPage( $fileName )
	{
		$this->errorPage	= $fileName;	
	}
	
	public function setMail( $mail )
	{
		$this->mail	= $mail;
	}

	/**
	 *	Shows Exception in Parts (Error and Trace).
	 *	@access		private
	 *	@param		Exception	$e			Exception
	 *	@param		string		$mode		Parts to show (both|error|trace)
	 *	@return		void
	 */
	private function showException( $e, $mode = "both" )
	{
		$type	= get_class( $e );
		$trace	= $e->getTrace();
		$code	= $e->getCode();
		if( $mode != "trace" )
		{
			print( "Exception: ".$type.$this->break );
			print( "Message: ".$e->getMessage().$this->break );
			print( "File: ".$e->getFile().$this->break );
			print( "Line: ".$e->getLine().$this->break );
			if( $code )
				print( "Code: ".$code.$this->break );
			if( $mode == "error" )
				return;
		}
		$type	= "";
		foreach( $trace as $data )
		{
			extract( $data );
			$class	= isset( $class ) ? $class : "";
			$type	= isset( $type ) ? $type : "";
			print( $this->line );
			print( $class.$type.$function.$this->break );
			print( $file." [".$line."]".$this->break );
		}
	}
	
	/**
	 *	Shows Lines of Log.
	 *	@access		private
	 *	@param		string		$filename	File Name of Log File
	 *	@param		int			$limit		Amount of Lines from the end
	 *	@return		void
	 */
	private function showLog( $filename, $limit )
	{
		if( file_exists( $filename ) )
		{
			$lines	= explode( "\n", trim( file_get_contents( $filename ) ) );
			if( $limit )
			{
				$lines	= array_slice( $lines, -1 * abs( $limit ) );
			}
			$lines	= implode( $this->break, $lines );
			$lines	= preg_replace( "@".$this->break."([0-9]+:)@si", $this->line."\\1", $lines );
			print( $lines );
		}
	}
}
?>
