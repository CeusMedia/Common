<?php
import( 'de.ceus-media.message.PhpMessage' );
/**
 *	Puts out an notice message.
 *	@package		message
 *	@extends		PhpMessage
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.2005
 *	@version		0.1
 */
/**
 *	Puts out an notice message.
 *	@package		message
 *	@extends		PhpMessage
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.2005
 *	@version		0.1
 */
class PhpNotice extends PhpMessage
{
	/**	@var		array		$prefix			Array of message prefixes */
	var $prefix	= array(
			'console'	=> 'NOTICE: ',
			'html'	=> 'Notice: ',
		);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Message to show
	 *	@param		int			$layers			Amount of layers to hide for backtrace
	 *	@return		void
	 */
	public function __construct( $message = false, $layers = 0 )
	{
		parent::__construct();
		if( $message )
			$this->notify( $message, $layers );
	}
	
	/**
	 *	Puts out a notice message.
	 *	@access		public
	 *	@param		string		$message		Message to show
	 *	@param		int			$layers			Amount of layers to hide for backtrace
	 *	@return		void
	 */
	function notify( $message, $layers = 0 )
	{
		$last = $this->getOption( 'last' );
		$this->setOption( 'timestamp', time() );
		$this->setOption( 'file', $last['file'] );
		if( isset( $last['class'] ) )
			$this->setOption( 'method', $last['class'].$last['type'].$last['function'] );
		else if( isset( $last['function'] ) )
			$this->setOption( 'method', $last['function'] );

		$notice = $this->_render( $message );
		echo $notice;
		if( function_exists( 'debug_backtrace' ) && defined( 'MESSAGE_CHANNEL' ) && MESSAGE_CHANNEL == 'html' )
			echo $this->getBacktrace( 1 + $layers );
	}
}
?>