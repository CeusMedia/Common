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
abstract class Framework_Krypton_ApplicationRunner
{
	public static $configKeyDatabaseLogPath			= 'database.log.path';
	public static $configKeyDatabaseLogErrors		= 'database.log.errors';
	public static $configKeyDatabaseLogStatements	= 'database.log.statements';
	public static $configKeyLanguages				= 'languages.allowed';
	public static $configKeyPathHtml				= 'paths.html';
	public static $errorTemplate					= 'errors/error.html';

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
		$isConsole	= !getEnv( 'HTTP_HOST' );
		try
		{
			import( 'de.ceus-media.file.configuration.Reader' );
			import( 'de.ceus-media.net.http.LanguageSniffer' );

			import( 'de.ceus-media.framework.krypton.ExceptionHandler' );
			$configPath	= defined( 'CMC_KRYPTON_CONFIG_PATH' ) ? CMC_KRYPTON_CONFIG_PATH : "config/";		//  get Path of Configuration File from System Constants (config/constansts.ini)
			$configFile	= defined( 'CMC_KRYPTON_CONFIG_FILE' ) ? CMC_KRYPTON_CONFIG_FILE : "config.ini";	//  get Name of Configuration File from System Constants (config/constansts.ini)
			$config		= new File_Configuration_Reader( $configPath.$configFile, NULL );

			if( $config->has( self::$configKeyDatabaseLogErrors ) )											//  Database Error Log is defined
			{
				$logPath	= $config[self::$configKeyDatabaseLogPath];										//  get Path of Log from Configuration
				$logUri		= $config[self::$configKeyDatabaseLogErrors];									//  get Name of Log from Configuration
				Framework_Krypton_ExceptionHandler::$logDbErrors		= $logPath.$logUri;					//  set Log in Exception Handler
			}
			if( $config->has( self::$configKeyDatabaseLogStatements ) )
			{
				$logPath	= $config[self::$configKeyDatabaseLogPath];
				$logUri		= $config[self::$configKeyDatabaseLogStatements];
				Framework_Krypton_ExceptionHandler::$logDbStatements	= $logPath.$logUri;
			}

			if( defined( 'CMC_EXCEPTION_MAIL_TO' ) && CMC_EXCEPTION_MAIL_TO )
			{
				Framework_Krypton_ExceptionHandler::$mailReceiver		= CMC_EXCEPTION_MAIL_TO;
			}

			if( defined( 'CMC_EXCEPTION_LOG_PATH' ) && CMC_EXCEPTION_LOG_PATH )
			{
				Framework_Krypton_ExceptionHandler::$logPath			= CMC_EXCEPTION_LOG_PATH;
			}
			if( !defined( 'CMC_KRYPTON_MODE' ) || !CMC_KRYPTON_MODE && !$isConsole )	
			{
				$languages	= $config[self::$configKeyLanguages];
				$languages	= explode( ",", $languages );
				$language	= Net_HTTP_LanguageSniffer::getLanguage( $languages );
				$template	= $config[self::$configKeyPathHtml].$language."/".self::$errorTemplate;
				Framework_Krypton_ExceptionHandler::$errorPage		= $template;
			}
			$report	= Framework_Krypton_ExceptionHandler::handleException( $e );
			print( $report );
			if( !$isConsole )
				die( $report );
			
			$message	= $e->getMessage();
			if( $e->getCode() )
				$message	.=" (Code:".$e->getCode().")";
			die( "\nException: ".$message );

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