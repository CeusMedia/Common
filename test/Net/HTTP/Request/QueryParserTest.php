<?php
/**
 *	TestUnit of Net_HTTP_Request_QueryParser.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.11.2008
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Net_HTTP_Request_QueryParser.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.11.2008
 *
 */
class Test_Net_HTTP_Request_QueryParserTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArrayException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		Net_HTTP_Request_QueryParser::toArray( "=" );
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArrayException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		Net_HTTP_Request_QueryParser::toArray( "&a=123&=" );
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArrayException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		Net_HTTP_Request_QueryParser::toArray( "&a=321&=123" );
	}

	/**
	 *	Tests Exception of Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArrayException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		Net_HTTP_Request_QueryParser::toArray( "a,321;,123", ";", "," );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray0()
	{
		$query		= "";
		$assertion	= array();
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );

		$query		= "&&&";
		$assertion	= array();
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray1()
	{
		$query		= "a=word&b=123&c=&d";
		$assertion	= array(
			'a'	=> "word",
			'b'	=> 123,
			'c'	=> "",
			'd'	=> NULL,
		);
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray2()
	{
		$query		= "a,word;b,123;c,;d";
		$assertion	= array(
			'a'	=> "word",
			'b'	=> 123,
			'c'	=> "",
			'd'	=> NULL,
		);
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query, ";", "," );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray3()
	{
		$query		= "none&short=*&long=a-z0-9._:#";
		$assertion	= array(
			'none'	=> NULL,
			'short'	=> "*",
			'long'	=> "a-z0-9._:#",
		);
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray4()
	{
		$query		= "array[]=1&array[]=2&array[]=";
		$assertion	= array(
			'array'	=> array(
				"1",
				"2",
				""
			),
		);
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray5()
	{
		$query		= "array[]=1&array[]=2&array[]";
		$assertion	= array(
			'array'	=> array(
				"1",
				"2",
				NULL,
			)
		);
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray6()
	{
		$query		= "array[];array[],1;array[],2;array[],";
		$assertion	= array(
			'array'	=> array(
				NULL,
				"1",
				"2",
				"",
			)
		);
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query, ";", "," );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray7()
	{
		$query		= "__key1&..key2&--key3=test&::key4=123";
		$assertion	= array(
			'__key1'	=> NULL,
			'..key2'	=> NULL,
			'--key3'	=> "test",
			'::key4'	=> "123",
		);
		$creation	= Net_HTTP_Request_QueryParser::toArray( $query );
		$this->assertEquals( $assertion, $creation );
	}
}
