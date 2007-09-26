<?php
import( 'de.ceus-media.ui.ErrorHandler' );
/**
 *	Error Handling of User Errors to replace default php error handling with Messaging System.
 *	@package	ui
 *	@extends	ErrorHandler
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		31.07.2005
 *	@version		0.4
 */
/**
 *	Error Handling of User Errors to replace default php error handling with Messaging System.
 *	@package	ui
 *	@extends	ErrorHandler
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		31.07.2005
 *	@version		0.4
 *	@todo		Code Documentation
 */
class UserErrorHandler extends ErrorHandler
{
	var $_messages = array();
	public function __construct()
	{
		parent::__construct( false );
		$this->setOption( 'e_error',			false );
		$this->setOption( 'e_warning',		false );
		$this->setOption( 'e_parse',			false );
		$this->setOption( 'e_notice',			false );
		$this->setOption( 'e_core_error',		false );
		$this->setOption( 'e_core_warning',	false );
		$this->setOption( 'e_compile_error',	false );
		$this->setOption( 'e_compile_warning',	false );
		$this->setOption( 'e_user_error',		true );
		$this->setOption( 'e_user_warning',	true );
		$this->setOption( 'e_user_notice',		true );
		$this->setOption( 'mail',		false );
		$this->setOption( 'abort',	array() );
		$this->setOption( 'send',	array() );
	}

	function warn()
	{
		$this->_messages[] = $this->_implant();
	}
	
	function abort()
	{
		echo $this->_implant();
	}
	
	function getMessages()
	{
		return $this->_messages;
	}
}
?>