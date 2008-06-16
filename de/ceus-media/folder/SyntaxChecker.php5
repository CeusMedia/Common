<?php
import( 'de.ceus-media.file.SyntaxChecker' );
import( 'de.ceus-media.folder.RecursiveRegexFilter' );
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.Stopwatch' );
/**
 *	Checks Syntax of all PHP Classes and Scripts within a Folder.
 *	@package		folder
 *	@uses			File_SyntaxChecker
 *	@uses			Folder_RecursiveRegexFilter
 *	@uses			UI_DevOutput
 *	@uses			Stopwatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.05.2008
 *	@version		0.1
 */
/**
 *	Checks Syntax of all PHP Classes and Scripts within a Folder.
 *	@package		folder
 *	@uses			File_SyntaxChecker
 *	@uses			Folder_RecursiveRegexFilter
 *	@uses			UI_DevOutput
 *	@uses			Stopwatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.05.2008
 *	@version		0.1
 */
class Folder_SyntaxChecker
{
	/**	@var		object		$checker		Instance of File_SyntaxChecker */
	protected $checker;
	/**	@var		string		$phpExtension	Extension of PHP Files, by default 'php5' */
	public static $phpExtension	= "php5";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->lineBreak	= getEnv( 'HTTP_HOST' ) ? "<br/>" : "\n";
		$this->checker		= new File_SyntaxChecker;
	}

	/**
	 *	Checks Syntax of all PHP Files within a given Folder and returns collected Information as Array.
	 *	For Console you can set 'verbose' to TRUE and you will see the Progress and the Results printed out.
	 *	@access		public
	 *	@param		string		$pathName		Path to Folder to check within
	 *	@param		bool		$verbose		Flag: active Output of Progress and Results
	 *	@return		array
	 */
	public function checkFolder( $pathName, $verbose = FALSE )
	{
		$counter	= 0;
		$invalid	= array();
		$watch		= new Stopwatch;
		$this->failures	= array();
		$index		= new Folder_RecursiveRegexFilter( $pathName, "@\.".self::$phpExtension."$@" );
		foreach( $index as $file )
		{
			$counter++;
			$fileName	= $file->getPathname();
			$shortName	= substr( $fileName, strlen( $pathName) );
			$valid		= $this->checker->checkFile( $file->getPathname() );
			if( !$valid )
				$invalid[$fileName]	= $error	= $this->checker->getShortError();
			if( $verbose )
				remark( $shortName.": ".( $valid ? "valid." : $error ) );
		}
		if( $verbose )
			$this->printResults( $invalid, $counter, $watch->stop( 0, 1 ) );
		$result	= array(
			'numberFiles'	=> $counter,
			'numberErrors'	=> count( $invalid ),
			'listErrors'	=> $invalid,
		);
		return $result;
	}

	/**
	 *	Prints Results.
	 *	@access		protected
	 *	@param		array		$invalid		Array of Invalid Files and Errors
	 *	@param		int			$counter		Number of checked Files
	 *	@param		double		$time			Time needed to check Folder in Seconds
	 *	@return		void
	 */
	protected function printResults( $invalid, $counter, $time )
	{
		remark( str_repeat( "-", 79 ) );
		if( count( $invalid ) )
		{
			remark( "valid Files: ".( $counter - count( $invalid ) ) );
			remark( "invalid Files: ".count( $invalid ) );
			foreach( $invalid as $fileName => $error )
				remark( "1. ".$fileName.": ".$error );
		}
		else
		{
			remark( "All ".$counter." Files are valid." );;
		}
		remark( "Time: ".$time." sec" );
	}
}
?>