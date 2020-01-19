<?php

/**
 *	TestUnit of ADT_URL.
 *	@package		Tests.CeusMedia_Common_ADT
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			30.11.2018
 *	@version		0.1
 */
require_once dirname( __DIR__ ).'/initLoaders.php';
/**
 *	TestUnit of ADT_URL.
 *	@package		Tests.CeusMedia_Common_ADT
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			30.11.2018
 *	@version		0.1
 */
class Test_ADT_URLTest extends Test_Case
{
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

	protected function create( $url, $baseUrl = NULL ){
		return new ADT_URL( $url, $baseUrl );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__constructException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::__construct();
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__toString()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::__toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::create();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::get();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAbsolute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAbsolute()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getAbsolute();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getAbsolute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAbsoluteException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::getAbsolute();
	}

	/**
	 *	Tests Method 'getRelative'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRelative()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getRelative();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAbsoluteTo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAbsoluteTo()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getAbsoluteTo();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getAbsoluteTo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAbsoluteToException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::getAbsoluteTo();
	}

	/**
	 *	Tests Method 'getRelativeTo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRelativeTo()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getRelativeTo();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFragment'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFragment()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getFragment();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getHost'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetHost()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getHost();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPassword'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPassword()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getPassword();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPath()
	{
		$assertion	= '/';
		$creation	= $this->create( '/' )->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '/a';
		$creation	= $this->create( 'a' )->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '/path';
		$creation	= $this->create( 'https://domain.tld/path' )->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '/path/to/somewhere/';
		$creation	= $this->create( 'https://domain.tld/path/to/somewhere/' )->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '/path/to/somewhere';
		$creation	= $this->create( 'https://domain.tld/path/to/somewhere?width=queryPart#havingFragment' )->getPath();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPort'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPort()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getPort();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getQuery'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetQuery()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getQuery();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getScheme'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetScheme()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getScheme();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getUsername'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetUsername()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::getUsername();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isAbsolute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAbsolute()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::isAbsolute();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isRelative'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsRelative()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::isRelative();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'isRelative'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsRelativeException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::isRelative();
	}

	/**
	 *	Tests Exception of Method 'isRelative'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsRelativeException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::isRelative();
	}

	/**
	 *	Tests Exception of Method 'isRelative'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsRelativeException3()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::isRelative();
	}

	/**
	 *	Tests Exception of Method 'isRelative'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsRelativeException4()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RangeException' );
		ADT_URL::isRelative();
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		$object		= new ADT_URL( 'https://username:password@domain.tld:123/path?with=query#havingFragment' );
		foreach( array(
			'https'				=> $object->getScheme(),
			'domain.tld'		=> $object->getHost(),
			'123'				=> $object->getPort(),
			'username'			=> $object->getUsername(),
			'password'			=> $object->getPassword(),
			'with=query'		=> $object->getQuery(),
			'havingFragment'	=> $object->getFragment(),
		) as $key => $value )
			$this->assertEquals( $key, $value );

//		$this->markTestIncomplete( 'Incomplete Test' );
	}

	/**
	 *	Tests Exception of Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::set();
	}

	/**
	 *	Tests Exception of Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::set();
	}

	/**
	 *	Tests Method 'setAuth'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAuth()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::setAuth();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setDefault'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDefault()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::setDefault();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setDefault'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDefaultException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		ADT_URL::setDefault();
	}

	/**
	 *	Tests Method 'setFragment'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetFragment()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::setFragment();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setHost'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetHost()
	{
		$object		= $this->create( '/' );
		$assertion	= 'myhost.net';
		$creation	= $object->setHost( $assertion )->getHost();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '';
		$creation	= $object->setHost( $assertion )->getHost();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPassword'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPassword()
	{
		$object		= $this->create( '/' );
		$assertion	= 'test123';
		$creation	= $object->setPassword( $assertion )->getPassword();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '';
		$creation	= $object->setPassword( $assertion )->getPassword();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPath()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::setPath();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPathException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RangeException' );
		ADT_URL::setPath();
	}

	/**
	 *	Tests Method 'setQuery'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetQuery()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::setQuery();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setScheme'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetScheme()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= ADT_URL::setScheme();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setUsername'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetUsername()
	{
		$object		= $this->create( '/' );
		$assertion	= 'frank_the-Tank';
		$creation	= $object->setUsername( $assertion )->getUsername();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '';
		$creation	= $object->setUsername( $assertion )->getUsername();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
