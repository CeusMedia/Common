<?php
import( 'de.ceus-media.message.PhpError' );
import( 'de.ceus-media.message.PhpWarning' );
import( 'de.ceus-media.message.PhpNotice' );

define("FATAL", E_USER_ERROR );
define("ERROR", E_USER_WARNING );
define("WARNING", E_USER_NOTICE );

if( !function_exists( 'str_reverse' ) )
{
	function str_reverse( $string )
	{
		$new = "";
		for( $i = 0; $i < strlen( $string ); $i++ )
			$new .= $string[strlen( $string ) - 1 - $i];
		return $new;
	}
}

/**
 *	@todo		Code Documentations
 */
class PHPErrorHandler
{

	public function __construct( $constants = array() )
	{
		foreach( $constants as $key => $value )
			define( $key, $value );
		$old_error_handler = set_error_handler( array( &$this, "__errorHandler" ) );
	}

	/**
	 *	Callback for Error Handling.
	 *	@access		private
	 *	@param		int			level			Debug Level
	 *	@param		int			flag			???
	 *	@return		void
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 */
	function __checkBinaryFlag( $level, $flag )
	{
		if( $flag == 0 )
			return false;
		$c = 0;
		while( $flag != 0 )
		{
			$c++;
			$flag = $flag >> 1;
		}
		$c--;
		$bin = str_reverse( DecBin( $level ) );
		return (bool)$bin[$c];
	}

	/**
	 *	Callback for Error Handling.
	 *	@access		private
	 *	@param		int			errno		Number of Error
	 *	@param		string		errstr		Error Message from PHP
	 *	@param		string		errfile		Filename were Error occured
	 *	@param		string		errline		Line in File were Error occured
	 *	@return		void
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 */
	function __errorHandler( $errno, $errstr, $errfile, $errline )
	{
		$level = error_reporting();
		if( $this->__checkBinaryFlag( $level, $errno ) )
		{
			switch( $errno )
			{
				case FATAL:
					new PhpError( $errstr, 1 );
					exit( 1 );
				case ERROR:
					new PhpWarning( $errstr, 1 );
					break;
				case WARNING:
					new PhpNotice( $errstr, 1 );
					break;

				case E_PARSE:
					new PhpError( $errstr, 1 );
					exit( 1 );
				case E_USER_ERROR:
					new PhpError( $errstr, 1 );
					exit( 1 );
				case E_USER_WARNING:
					new PhpWarning( $errstr, 1 );
					break;
				case E_WARNING:
					new PhpWarning( $errstr, 1 );
					break;

				case E_USER_NOTICE:
					new PhpNotice( $errstr, 1 );
					break;
				case E_NOTICE:
					new PhpNotice( $errstr, 1 );
					break;
				default:
					new PhpNotice( "Unkown error type: [".$errno."] ".$errstr, 1 );
					break;
			}
		}
	}		
}
?>