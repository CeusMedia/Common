<?php
/**
 *	TestUnit of Service_Response.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.service.Response' );
/**
 *	TestUnit of Service_Response.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Net_Service_ResponseTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->instance	= new Test_Net_Service_ResponseInstance;
		$this->data		= array(
			'string'	=> "VALUE1",
			'bool'		=> TRUE,
			'double'	=> M_PI,
			'object'	=> new stdClass(),
		);
	}
	
	/**
	 *	Tests Method 'getBase64'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBase64()
	{
		$assertion	= base64_encode( serialize( $this->data ) );
		$creation	= $this->instance->executeProtectedMethod( 'getBase64', serialize( $this->data ) ); 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getJson'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetJson()
	{
		$assertion	= json_encode( $this->data );
		$creation	= $this->instance->executeProtectedMethod( 'getJson', $this->data ); 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getPhp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPhp()
	{
		$assertion	= serialize( $this->data );
		$creation	= $this->instance->executeProtectedMethod( 'getPhp', $this->data ); 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getPhp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWddx()
	{
		$assertion	= wddx_serialize_value( $this->data );
		$creation	= $this->instance->executeProtectedMethod( 'getWddx', $this->data ); 
		$this->assertEquals( $assertion, $creation );
	}
}

class Test_Net_Service_ResponseInstance extends Net_Service_Response
{
	public function executeProtectedMethod( $method, $content, $comment = NULL )
	{
		if( !method_exists( $this, $method ) )
			throw new Exception( 'Method "'.$method.'" is not callable.' );
		return $this->$method( $content, $comment );
	}
}
?>