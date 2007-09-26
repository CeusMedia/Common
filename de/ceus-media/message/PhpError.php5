<?php
import( 'de.ceus-media.message.PhpMessage' );
/**
 *	Puts out an error message.
 *	@package		message
 *	@extends		PhpMessage
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.2005
 *	@version		0.1
 */
/**
 *	Puts out an error message.
 *	@package		message
 *	@extends		PhpMessage
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.2005
 *	@version		0.1
 */
class PhpError extends PhpMessage
{
	/**	@var		array		$prefix			Array of message prefixes */
	var $prefix	= array(
			'console'	=> 'ERROR: ',
			'html'	=> 'Error: ',
		);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Message to show
	 *	@param		int			$layers			Amount of layers to hide for backtrace
	 *	@return		void
	 */
	public function __construct( $message, $layers = 0 )
	{
		parent::__construct();
		$this->_abort( $message, $layers );
	}
	
	/**
	 *	Aborts with message output.
	 *	@access		private
	 *	@param		string		$message		Message to show
	 *	@param		int			$layers			Amount of layers to hide for backtrace
	 *	@return		void
	 */
	function _abort( $message, $layers = 0 )
	{ 
		$last = $this->getOption( 'last' );
		$this->setOption( 'timestamp', time() );
		$this->setOption( 'file', $last['file'] );
		if( isset( $last['class'] ) )
			$this->setOption( 'method', $last['class'].$last['type'].$last['function'] );
		else if( isset( $last['function'] ) )
			$this->setOption( 'method', $last['function'] );

		$error = $this->_render( $message );
		echo $error;
		if( function_exists( 'debug_backtrace' ) && defined( 'MESSAGE_CHANNEL' ) && MESSAGE_CHANNEL == 'html' )
			echo $this->getBacktrace( 1 + $layers );
		die;
	}
}
?>