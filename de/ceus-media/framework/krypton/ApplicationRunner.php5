<?php
/**
 *	Runs an Krypton Application within a Container secured by an Exception Handler for uncatched Exceptions.
 *	Furthermore all PHP Messages (Warnings, Notices etc) can be converted to Exceptions.
 *	If the Exception Handling fails itself, the resulting Exception with be shown with an Exception Trace Viewer.
 *	This class is abstract and needs to be extended in run().
 *
 *	@package		framework.krypton
 *	@uses			File_Configuration_Reader
 *	@uses			Framework_Krypton_ExceptionHandler
 *	@uses			Framework_Krypton_FatalExceptionHandler
 *	@uses			UI_HTML_Exception_TraceViewer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			29.03.2009
 *	@version		0.1
 */
/**
 *	Runs an Krypton Application within a Container secured by an Exception Handler for uncatched Exceptions.
 *	Furthermore all PHP Messages (Warnings, Notices etc) can be converted to Exceptions.
 *	If the Exception Handling fails itself, the resulting Exception with be shown with an Exception Trace Viewer.
 *	This class is abstract and needs to be extended in run().
 *
 *	@package		framework.krypton
 *	@uses			File_Configuration_Reader
 *	@uses			Framework_Krypton_ExceptionHandler
 *	@uses			Framework_Krypton_FatalExceptionHandler
 *	@uses			UI_HTML_Exception_TraceViewer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			29.03.2009
 *	@version		0.1
 */
abstract class ApplicationRunner
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$initErrorHandler	Set alternative PHP Error Handler in initErrorHandler().
	 *	@return		void
	 */
	public function __construct( $initErrorHandler = FALSE )
	{
		try
		{
			if( $initErrorHandler )
				$this->initErrorHandler();
			$this->run();
		}
		catch( Exception $e )
		{
			$this->handleException( $e );
		}
	}

	/**
	 *	Handles uncatched Exceptions with Krypton's Exception Handler (or FatalExceptionHandler below cmClasses 0.6.6).
	 *	If the Exception Handling fails itself, the resulting Exception with be shown with an Exception Trace Viewer.
	 *	Attention: Needs defined constants set by Krypton's Base::loadConstants.	
	 *	@access		protected
	 *	@param		Exception	$e					Exception to handle.
	 *	@return		void
	 */
	protected function handleException( Exception $e )
	{
		try
		{
			import( 'de.ceus-media.file.configuration.Reader' );
			import( 'de.ceus-media.net.http.LanguageSniffer' );
			if( defined( 'CMC_VERSION' ) && version_compare( CMC_VERSION, "0.6.6", ">=" ) )
			{
				import( 'de.ceus-media.framework.krypton.ExceptionHandler' );
				$config		= new File_Configuration_Reader( CMC_KRYPTON_CONFIG_PATH.CMC_KRYPTON_CONFIG_FILE, NULL );
				if( $config['database.log.errors'] )
					Framework_Krypton_ExceptionHandler::$logDbErrors		= $config['database.log.path'].$config['database.log.errors'];
				if( $config['database.log.statements'] )
					Framework_Krypton_ExceptionHandler::$logDbStatements	= $config['database.log.path'].$config['database.log.statements'];
				if( defined( 'CMC_EXCEPTION_MAIL_TO' ) && CMC_EXCEPTION_MAIL_TO )
					Framework_Krypton_ExceptionHandler::$mailReceiver		= CMC_EXCEPTION_MAIL_TO;
				if( defined( 'CMC_EXCEPTION_LOG_PATH' ) && CMC_EXCEPTION_LOG_PATH )
					Framework_Krypton_ExceptionHandler::$logPath			= CMC_EXCEPTION_LOG_PATH;
				if( !defined( 'CMC_EXCEPTION_DEBUG_MODE' ) || !CMC_EXCEPTION_DEBUG_MODE )
				{
					$languages	= explode( ",", $config['languages.allowed'] );
					$language	= Net_HTTP_LanguageSniffer::getLanguage( $languages );
					Framework_Krypton_ExceptionHandler::$errorPage		= $config['paths.html'].$language."/error.html";
				}
				new Framework_Krypton_ExceptionHandler( $e );
			}
			else
			{
				import( 'de.ceus-media.framework.krypton.FatalExceptionHandler' );
				if( !EXCEPTION_DEBUG_MODE )
				{
					$config		= new File_Configuration_Reader( "config/config.xml", NULL );
					$languages	= explode( ",", $config['languages.allowed'] );
					$language	= Net_HTTP_LanguageSniffer::getLanguage( $languages );
					define( 'EXCEPTION_ERROR_PAGE', "contents/html/".$language."/error.html" );
				}
				$h	= new Framework_Krypton_FatalExceptionHandler;
				print( $h->handleException( $e ) );
			}
		}
		catch( Exception $e )
		{
			import( 'de.ceus-media.ui.html.exception.TraceViewer' );
			new UI_HTML_Exception_TraceViewer( $e );
		}
	}

	/**
	 *	Sets an alternative PHP Error Handler, either with a Lambda Function or a Callback Function.
	 *	@access		protected
	 *	@param		bool		$lambda				Set a Lambda Function as Callback Function.
	 *	@return		void
	 */
	protected function initErrorHandler( $lamba = FALSE )
	{
		if( $lamba )
		{
			$function	= create_function( '$a, $b, $c, $d', 'throw new ErrorException($b, 0, $a, $c, $d);' );
			set_error_handler( $function, E_ALL );
		}
		else
			set_error_handler( array( $this, 'throwErrorException' ), E_ALL );
	}

	/**
	 *	Runs Krypton Application itself and needs to be overwritten with the pure execution lines.
	 *	@access		protected
	 *	@return		void
	 */
	abstract protected function run();

	/**
	 *	Callback Function for PHP Error Handler set in initErrorHandler().
	 *	Converts every PHP Message to an Error Exception and throws it.
	 *	@access		public
	 *	@param		int			$errno				Error Number
	 *	@param		string		$errstr				Error Message
	 *	@param		string		$errfile			File of Error
	 *	@param		int			$errline			Line in File
	 *	@return		void
	 */
	public function throwErrorException( $errno, $errstr, $errfile, $errline )
	{
		throw new ErrorException( $errstr, 0, $errno, $errfile, $errline );
	}
}
?>