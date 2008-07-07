<?php
/**
 *	TestUnit of Net Service Client.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Client
 *	@uses			ADT_OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.service.Client' );
import( 'de.ceus-media.adt.OptionObject' );
/**
 *	TestUnit of Net Service Client.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Client
 *	@uses			ADT_OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Net_Service_ClientTest extends PHPUnit_Framework_TestCase
{
	protected $client;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->client	= new Test_Net_Service_ClientInstance( "http://services.ceus-media.de/public/" );
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$client		= new Test_Net_Service_ClientInstance();

		$assertion	= 32;
		$creation	= strlen( $client->getId() );
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $client->getProtectedVar( 'logFile' );
		$this->assertEquals( $assertion, $creation );


		$client		= new Test_Net_Service_ClientInstance( "http//sub.test.tld", "services.log" );

		$assertion	= 32;
		$creation	= strlen( $client->getId() );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "http//sub.test.tld";
		$creation	= $client->getProtectedVar( 'host' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "services.log";
		$creation	= $client->getProtectedVar( 'logFile' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception Forwarding of Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseException()
	{
		$response	= serialize( new RuntimeException( 'testException' ) );
		try
		{
			$this->client->executeProtectedMethod( 'decodeResponse', $response, 'not_relevant' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( RuntimeException $e )
		{
			$assertion	= 'RuntimeException';
			$creation	= get_class( $e );
			$this->assertEquals( $assertion, $creation );
		
			$assertion	= 'testException';
			$creation	= $e->getMessage();
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'decodeResponse' with PHP Serial.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponsePhp()
	{
		$response	= serialize( array( 'key1' => 'value1' ) );
		$assertion	= unserialize( $response );
		$creation	= $this->client->executeProtectedMethod( 'decodeResponse', $response, 'php' );
		$this->assertEquals( $assertion, $creation );
	}
		
	/**
	 *	Tests Method 'decodeResponse' with JSON.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseJson()
	{
		$response	= json_encode( array( 'key1' => 'value1' ) );
		$assertion	= json_decode( $response );
		$creation	= $this->client->executeProtectedMethod( 'decodeResponse', $response, 'json' );
		$this->assertEquals( $assertion, $creation );
	}
		
	/**
	 *	Tests Method 'decodeResponse' with Text.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseTxt()
	{
		$response	= time();
		$assertion	= $response;
		$creation	= $this->client->executeProtectedMethod( 'decodeResponse', $response, 'txt' );
		$this->assertEquals( $assertion, $creation );
	}
		
	/**
	 *	Tests Method 'decodeResponse' with WDDX.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseWddx()
	{
		$response	= wddx_serialize_value( array( 'key1' => 'value1' ) );
		$assertion	= wddx_deserialize( $response );
		$creation	= $this->client->executeProtectedMethod( 'decodeResponse', $response, 'wddx' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'executeRequest'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecuteRequest()
	{
		$request	= new Test_Net_Service_ClientRequestMock;
		$this->client->setUserAgent( 'test' );
		$this->client->setVerifyPeer( TRUE );
		$this->client->setVerifyHost( TRUE );
		$this->client->setBasicAuth( 'username1', 'password1' );
		
		$response	= $this->client->executeProtectedMethod( 'executeRequest', $request );

		$assertion	= array(
			CURLOPT_SSL_VERIFYPEER	=> TRUE,
			CURLOPT_SSL_VERIFYHOST	=> TRUE,
			CURLOPT_USERAGENT		=> 'test',
			CURLOPT_USERPWD			=> 'username1:password1',
		);
		$creation	= $request->getOptions();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'content'	=> $request->response,
			'status'	=> array(
				CURL_STATUS_HTTP_CODE	=> 200,
			),
			'headers'	=> array(
				'testKey'	=> 'testValue'
			),
		);
		$creation	= $this->client->executeProtectedMethod( 'executeRequest', $request );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'content'	=> gzcompress( $request->response ),
			'status'	=> array(
				CURL_STATUS_HTTP_CODE	=> 200,
			),
			'headers'	=> array(
				'testKey'	=> 'testValue'
			),
		);

		$request->compression	= "deflate";
		$creation	= $this->client->executeProtectedMethod( 'executeRequest', $request, 'deflate' );
		$this->assertEquals( $assertion, $creation );

#		$request->compression	= "gzip";
#		$assertion	= $request->response;
#		$creation	= $this->client->executeProtectedMethod( 'executeRequest', $request, 'gzip' );
#		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$time		= $this->client->get( "getTimestamp" );
		$assertion	= TRUE;
		$creation	= strlen( $time ) == 10;
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= substr( $time, 0, 2 ) >= 12;
		$this->assertEquals( $assertion, $creation );


#		$this->markTestIncomplete( 'Incomplete Test' );
	}

	/**
	 *	Tests Method 'getId'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetId()
	{
		$id			= $this->client->getId();

		$assertion	= TRUE;
		$creation	= is_string( $id );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 32;
		$creation	= strlen( $id );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getRequests'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRequests()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Client::getRequests();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'post'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPost()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$time		= $this->client->post( "getTimestamp" );
		$assertion	= TRUE;
		$creation	= strlen( $time ) == 10;
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= substr( $time, 0, 2 ) >= 12;
		$this->assertEquals( $assertion, $creation );


#		$this->markTestIncomplete( 'Incomplete Test' );
	}

	/**
	 *	Tests Method 'setBasicAuth'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetBasicAuth()
	{
		$this->client->setBasicAuth( "username1", "password1" );

		$assertion	= "username1";
		$creation	= $this->client->getProtectedVar( 'username' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "password1";
		$creation	= $this->client->getProtectedVar( 'password' );
		$this->assertEquals( $assertion, $creation );

	}

	/**
	 *	Tests Method 'setHostAddress'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetHostAddress()
	{
		$this->client->setHostAddress( "http://sub.host.test/" );

		$assertion	= "http://sub.host.test/";
		$creation	= $this->client->getProtectedVar( 'host' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setUserAgent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetUserAgent()
	{
		$this->client->setUserAgent( "testAgent" );

		$assertion	= "testAgent";
		$creation	= $this->client->getProtectedVar( 'userAgent' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setVerifyHost'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetVerifyHost()
	{
		$this->client->setVerifyHost( TRUE );
		$assertion	= TRUE;
		$creation	= $this->client->getProtectedVar( 'verifyHost' );
		$this->assertEquals( $assertion, $creation );

		$this->client->setVerifyHost( FALSE );
		$assertion	= FALSE;
		$creation	= $this->client->getProtectedVar( 'verifyHost' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setVerifyPeer'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetVerifyPeer()
	{
		$this->client->setVerifyPeer( TRUE );
		$assertion	= TRUE;
		$creation	= $this->client->getProtectedVar( 'verifyPeer' );
		$this->assertEquals( $assertion, $creation );

		$this->client->setVerifyPeer( FALSE );
		$assertion	= FALSE;
		$creation	= $this->client->getProtectedVar( 'verifyPeer' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'uncompressResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUncompressResponse()
	{
		$message	= "this is a uncompressed test text.";

		$compressed	= gzcompress( $message );
		$assertion	= $message;
		$creation	= $this->client->executeProtectedMethod( 'uncompressResponse', $compressed, "deflate" );
		$this->assertEquals( $assertion, $creation );

		$compressed	= $message;
		$assertion	= $message;
		$creation	= $this->client->executeProtectedMethod( 'uncompressResponse', $compressed, "not_supported" );
		$this->assertEquals( $assertion, $creation );

#		$compressed	= gzencode( $message );
#		$assertion	= $message;
#		$creation	= $this->client->executeProtectedMethod( 'uncompressResponse', $compressed, "gzip" );
#		$this->assertEquals( $assertion, $creation );
	}
}
class Test_Net_Service_ClientRequestMock extends ADT_OptionObject
{
	public $response	= "This is a Request Response.";
	public $compression	= NULL;
	public $httpCode	= 200;
	public function exec()
	{
		switch( $this->compression )
		{
			case 'deflate':	return gzcompress( $this->response );
			case 'gzip':	return gzencode( $this->response );
			default:		return $this->response;
		}
	}
	
	public function getStatus( $key = NULL )
	{
		$status	= array(
			CURL_STATUS_HTTP_CODE	=> $this->httpCode,
		);
		if( $key && array_key_exists( $key, $status ) )
			return $status[$key];
		return $status;
		
	}

	public function getHeader()
	{
		$status	= array(
			'testKey'	=> "testValue",
		);
		return $status;
		
	}
}
class Test_Net_Service_ClientInstance extends Net_Service_Client
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	public function executeProtectedMethod( $method, $content, $comment = NULL )
	{
		if( !method_exists( $this, $method ) )
			throw new Exception( 'Method "'.$method.'" is not callable.' );
		return $this->$method( $content, $comment );
	}
}
?>