<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\URL.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT;

use CeusMedia\Common\ADT\URL;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of ADT\URL.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class URLTest extends BaseCase
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

	protected function create( $url, $baseUrl = NULL )
	{
		return new URL( $url, $baseUrl );
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
		URL::__construct();
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
		$creation	= URL::__toString();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::create();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::get();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getAbsolute();
		self::assertEquals( $assertion, $creation );
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
		URL::getAbsolute();
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
		$creation	= URL::getRelative();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getAbsoluteTo();
		self::assertEquals( $assertion, $creation );
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
		URL::getAbsoluteTo();
	}

	/**
	 *	Tests Method 'getRelativeTo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRelativeTo()
	{
		$url1	= new URL( 'http://abc.de/path/' );
		$url2	= new URL( 'http://abc.de/path/nested/abc' );

		$expected	= 'nested/abc';
		self::assertEquals( $expected, $url2->getRelativeTo( $url1 ) );

		$url2	= new URL( 'http://abc.de/abc' );
		$expected	= '../abc';
		self::assertEquals( $expected, $url2->getRelativeTo( $url1 ) );

		$url1	= new URL( 'http://abc.de/a/b/c/' );
		$url2	= new URL( 'http://abc.de/d/e/f/g?p=1' );
		$expected	= '../../../d/e/f/g?p=1';
		self::assertEquals( $expected, $url2->getRelativeTo( $url1 ) );
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
		$creation	= URL::getFragment();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getHost();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getPassword();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= '/a';
		$creation	= $this->create( 'a' )->getPath();
		self::assertEquals( $assertion, $creation );

		$assertion	= '/path';
		$creation	= $this->create( 'https://domain.tld/path' )->getPath();
		self::assertEquals( $assertion, $creation );

		$assertion	= '/path/to/somewhere/';
		$creation	= $this->create( 'https://domain.tld/path/to/somewhere/' )->getPath();
		self::assertEquals( $assertion, $creation );

		$assertion	= '/path/to/somewhere';
		$creation	= $this->create( 'https://domain.tld/path/to/somewhere?width=queryPart#havingFragment' )->getPath();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getPort();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getQuery();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getScheme();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::getUsername();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::isAbsolute();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::isRelative();
		self::assertEquals( $assertion, $creation );
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
		URL::isRelative();
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
		URL::isRelative();
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
		URL::isRelative();
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
		URL::isRelative();
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		$object		= new URL( 'https://username:password@domain.tld:123/path?with=query#havingFragment' );
		foreach( array(
			'https'				=> $object->getScheme(),
			'domain.tld'		=> $object->getHost(),
			'123'				=> $object->getPort(),
			'username'			=> $object->getUsername(),
			'password'			=> $object->getPassword(),
			'with=query'		=> $object->getQuery(),
			'havingFragment'	=> $object->getFragment(),
		) as $key => $value )
			self::assertEquals( $key, $value );

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
		URL::set();
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
		URL::set();
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
		$creation	= URL::setAuth();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::setDefault();
		self::assertEquals( $assertion, $creation );
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
		URL::setDefault();
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
		$creation	= URL::setFragment();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= '';
		$creation	= $object->setHost( $assertion )->getHost();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= '';
		$creation	= $object->setPassword( $assertion )->getPassword();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::setPath();
		self::assertEquals( $assertion, $creation );
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
		URL::setPath();
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
		$creation	= URL::setQuery();
		self::assertEquals( $assertion, $creation );
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
		$creation	= URL::setScheme();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= '';
		$creation	= $object->setUsername( $assertion )->getUsername();
		self::assertEquals( $assertion, $creation );
	}
}
