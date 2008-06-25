<?php
/**
 *	Class for PHP Execution via Console (for Windows).
 *	@package		console
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.08.2005
 *	@version		0.4
 */
/**
 *	Class for PHP Execution via Console (for Windows).
 *	@package		console
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.08.2005
 *	@version		0.4
 */
class Console_Shell
{
	/**	@var	array	$skip	Commands to skip */
	protected $skip	= array(
		"class",
		"declare",
		"die",
		"echo",
		"exit", 
		"for", 
		"foreach", 
		"function", 
		"global", 
		"if", 
		"include", 
		"include_once",
		"print",
		"require", 
		"require_once", 
		"return", 
		"static", 
		"switch", 
		"while"
		);
	/**	@var	array	$okeq	Valide equation operators */
	protected $okeq = array(
		"===", 
		"!==", 
		"==", 
		"!=", 
		"<=", 
		">="
		);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return 	void
	 */
	public function __construct()
	{
		if( getEnv( 'HTTP_HOST' ) )
			die( "usage in console only." );
		ob_implicit_flush( true );
		ini_set( "html_errors", 0 );
		error_reporting( 7 );
		set_time_limit( 0 ); 
		if( !defined( 'STDIN' ) )
		{
			define( 'STDIN',	fopen( "php://stdin","r" ) );
			define( 'STDOUT',	fopen( "php://stdout","w" ) );
			define( 'STDERR',	fopen( "php://stderr","w" ) );
			register_shutdown_function(
				create_function( '' , 'fclose(STDIN); fclose(STDOUT); fclose(STDERR); return true;' ) );
		}
		$this->run();
	}
	
	/**
	 *	Reads input line from console.
	 *	@access		public
	 *	@return 	void
	 */
	public function readLine( $length = 255)
	{
		$line = fgets ( STDIN, $length );
		return trim ($line);
	}	

	/**
	 *	Reads input lines from console and prints out the answer.
	 *	@access		protected
	 *	@return 	void
	 */
	protected function run()
	{
		fputs( STDOUT, ":> " );
		while( $line = $this->readLine() )
		{
			$line = preg_replace( "/\n*|\r*/", "", $line );
			$line = preg_replace( "/;$/", "", $line );
			if( strlen( $line ) )
			{
				if( $this->isImmediate( $line ) )
					$line = "return( ".$line." )";
				ob_start();
				$ret = eval( "unset(\$line); $line;" );
				if( ob_get_length() == 0)
				{
					if( is_bool( $ret ) )
						echo( $ret ? "true" : "false" );
					else if( is_string( $ret ) )
						echo "'" . addcslashes( $ret, "\0..\37\177..\377" )  . "'";
					else if( !is_null( $ret ) )
						print_r( $ret );
				}
				unset($ret);
				$out = ob_get_contents();
				ob_end_clean();
				if( ( strlen( $out ) > 0) && ( substr( $out, -1 ) != "\n" ) )
					$out .= "\n";
				fputs( STDOUT, "=> ".$out );
				unset( $out );
				fputs( STDOUT, ":> " );
			}
		}
	}

	/**
	 *	Indicates whether a line is immediate executable like equations.
	 *	@access		protected
	 *	@return 	void
	 */
	protected function isImmediate( $line )
	{
		$code = "";
		$sq = $dq = false;
		for( $i = 0; $i < strlen( $line ); $i++ )
		{
			$c = $line{$i};
			if( $c == "'" )
				$sq = !$sq;
			else if( $c == '"')
				$dq = !$dq;
			else if( ( $sq ) ||( $dq ) )
			{
				if( $c == "\\" )
					$i++;
			}
			else
				$code .= $c;
		}
		$code = str_replace( $this->okeq, "", $code );
		if( strcspn( $code, ";{=" ) != strlen( $code ) )
			return false;
		$kw = split( "[^A-Za-z0-9_]", $code );
		foreach( $kw as $i )
			if( in_array( $i, $this->skip ) )
				return false;
		return true;
	}
}
?>