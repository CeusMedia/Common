<?php
/**
 *	TestUnit of Service_Response.
 *	@package		Tests.net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Service_Response.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Net_Service_ResponseTest extends PHPUnit_Framework_TestCase
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
			'status'	=> "data",
			'data'		=> array(
				'string'	=> "VALUE1",
				'bool'		=> TRUE,
				'double'	=> M_PI,
#				'object'	=> new stdClass(),
			)
		);
	}
	
	/**
	 *	Tests Method 'getBase64'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$instance	= new Test_Net_Service_ResponseInstance();
		$assertion	= "Alg_Time_Clock";
		$creation	= get_class( $instance->getProtectedVar( 'clock' ) );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getBase64'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBase64()
	{
		$assertion	= base64_encode( serialize( $this->data['data'] ) );
		$creation	= $this->instance->executeProtectedMethod( 'getBase64', serialize( $this->data['data'] ) ); 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'convertToOutputFormat' with Format Exception.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatException1()
	{
		$this->setExpectedException( 'BadMethodCallException' );
		$this->instance->convertToOutputFormat( "", "invalidFormat" );
	}
	
	/**
	 *	Tests Method 'convertToOutputFormat' with Data Exception.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatException2()
	{
		$message	= "Test Exception";
		$assertion	= $message;
		$structure	= unserialize( $this->instance->convertToOutputFormat( new Exception( $message ), "php" ) );
		$creation	= $structure['data']['message']; 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'convertToOutputFormat' with JSON.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatJson()
	{
		$assertion	= json_encode( $this->data );
		$creation	= $this->instance->convertToOutputFormat( $this->data['data'], "json" ); 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'convertToOutputFormat' with PHP.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatPhp()
	{
		$assertion	= serialize( $this->data );
		$creation	= $this->instance->convertToOutputFormat( $this->data['data'], "php" ); 
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToOutputFormat' with Text.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatTxt()
	{
		$assertion	= $this->data['data']['string'];
		$creation	= $this->instance->convertToOutputFormat( $this->data['data']['string'], "txt" ); 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'convertToOutputFormat' with WDDX.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatWddx()
	{
		$assertion	= wddx_serialize_value( $this->data );
		$creation	= $this->instance->convertToOutputFormat( $this->data['data'], "wddx" ); 
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'convertToOutputFormat' with XML.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatXml1()
	{
		$list	= array();
		foreach( $this->data['data'] as $key => $value )
			$list[]	= '<'.$key.'>'.$value.'</'.$key.'>';
		$assertion	= '
<?xml version="1.0"?>
<response>
  <status>data</status>
  <data>
    '.implode( "\n    ", $list ).'
  </data>
</response>';
		$assertion	= preg_replace( "/\n\r?/", "", $assertion );
		$creation	= $this->instance->convertToOutputFormat( $this->data['data'], "xml" );
		$creation	= preg_replace( "/\n\r?/", "", $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToOutputFormat' with XML.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToOutputFormatXml2()
	{
		$data	= array(
			'colors'	=> array(
				'white',
				'black',
				'red',
				'blue',
				'green'
			)
		);
		$list	= array();
		foreach( $data['colors'] as $color )
			$list[]	= '<color>'.$color.'</color>';
		$assertion	= '
<?xml version="1.0"?>
<response>
  <status>data</status>
  <data>
    <colors>
      '.implode( "\n      ", $list ).'
    </colors>
  </data>
</response>';
		$creation	= $this->instance->convertToOutputFormat( $data, "xml" );
		$assertion	= preg_replace( "/\n\r?/", "", $assertion );
		$creation	= preg_replace( "/\n\r?/", "", $creation );
		$this->assertEquals( $assertion, $creation );
	}
}

class Test_Net_Service_ResponseInstance extends Net_Service_Response
{
	public function executeProtectedMethod( $method, $content )
	{
		if( !method_exists( $this, $method ) )
			throw new Exception( 'Method "'.$method.'" is not callable.' );
		return $this->$method( $content );
	}
	
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
	
	protected function buildResponseStructure( $content, $status )
	{
		$structure	= parent::buildResponseStructure( $content, $status );
		unset( $structure['timestamp'] );
		unset( $structure['duration'] );
		return $structure;
	}
}
?>