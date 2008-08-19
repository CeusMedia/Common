<?php
import( 'de.ceus-media.framework.krypton.Base' );
/**
 *	Main Class of Motrada V2 to realize Actions and build Views.
 *	@package		framework.krypton
 *	@extends		Framework_Krypton_Base
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2006
 *	@version		0.3
 */
/**
 *	Main Class of Motrada V2 to realize Actions and build Views.
 *	@package		framework.krypton
 *	@extends		Framework_Krypton_Base
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2006
 *	@version		0.3
 */
class ConsoleApplication extends Framework_Krypton_Base
{
	/**	@var	Core_Registry	$registry		Instance of Core_Registry */
	protected $registry		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$verbose		Flag: print Information to Console
	 *	@return		void
	 */
	public function __construct( $verbose = TRUE )
	{
#		self::$configFile	= "config.xml";
		$this->initRegistry();							//  must be first
		$this->initConfiguration();						//  must be one of the first
		$this->initEnvironment();						//  must be one of the first
		$this->initDatabase();
		$this->initRequest();
		$this->initLanguage( FALSE );
		self::evaluateArguments();
		self::run( $verbose );
	}

	/**
	 *	Evaluates Arguments.
	 *	@access		protected
	 *	@return		void
	 */
	protected function evaluateArguments()
	{
		//  --  SHOW SYNTAX  --  //
		$request	= Framework_Krypton_Core_Registry::getStatic( 'request' );
		if( count( $request->getAll() ) < 3 )
			die( "Syntax:  runJob <class> [parameter]\nExample: runJob Job_Test session" );
		$request->set( 'class', $request->get( 1 ) );
		$request->set( 'command', $request->get( 'cmd' ) );
		$request->remove( 0 );
		$request->remove( 1 );
		$request->remove( 'cmd' );
	}

	/**
	 *	Runs Job Class as Console Application.
	 *	@access		protected
	 *	@param		bool		$verbose		Flag: print Information to Console
	 *	@return		void
	 */
	protected static function run( $verbose = TRUE )
	{
		$request	= Framework_Krypton_Core_Registry::getStatic( 'request' );
		$className	= $request->get( 'class' );
		$command	= $request->get( 'command' );

		//  --  RUN JOB  --  //
		try
		{
			$classPath	= self::getPathNameOfClass( $className );
			import( 'classes.'.$classPath );
			$job		= new $className();

			if( $verbose )
				remark( "Now running: ".$className );

			$result		= $job->run( $command );
			error_log( time()." ".$className."[".$command."]: ".(string)$result."\n", 3, LOG_JOBS );
		}
		catch( Exception $e )
		{
			self::logException( $e, $className, $verbose );
		}
		if( $verbose )
			echo "\n";
	}

	/**
	 *	Logs Exception Information into Log File.
	 *	@access		protected
	 *	@param		Exception	$e				Exception to log
	 *	@param		string		$className		Name of Job Class
	 *	@param		bool		$verbose		Flag: print Information to Console
	 *	@return		void
	 */
	protected function logException( $e, $className, $verbose = TRUE )
	{
		$trace		= serialize( $e->getTrace() );
		$file		= $e->getFile();
		$line		= $e->getLine();
		$message	= $e->getMessage();
		error_log( time()."|".$className."(".$file."[".$line."]):".$message."|trace:".$trace."\n", 3, LOG_JOBERROR );
		if( $verbose )
			remark( "Unhandled Exception catched. See Job Error Log Reader or ".LOG_JOBERROR."." );
	}
}
?>