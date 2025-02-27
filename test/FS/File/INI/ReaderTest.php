<?php
declare( strict_types = 1 );
/**
 *	TestUnit of INI Reader.
 *	@package		Tests.FS.File.INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\INI;

use CeusMedia\Common\FS\File\INI\Reader;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	TestUnit of INI Reader.
 *	@package		Tests.FS.File.INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."reader.ini";
		$this->list		= new Reader( $this->fileName );
		$this->sections	= new Reader( $this->fileName, true );

	}

	public function testContruct()
	{
		$assertion	= array(
			"key1"	=> "value1",
			"key2"	=> "value2",
			"key3"	=> "value3",
			"key4"	=> "value4",
		);
		$reader		= new Reader( $this->fileName );
		$creation	= $reader->toArray();
		self::assertEquals( $assertion, $creation );


		$assertion	= array(
			"section1"	=> array(
				"key1"	=> "value1",
				"key2"	=> "value2",
			),
			"section2"	=> array(
				"key3"	=> "value3",
				"key4"	=> "value4",
			),
		);
		$reader		= new Reader( $this->fileName, TRUE );
		$creation	= $reader->toArray();
		self::assertEquals( $assertion, $creation );
	}

	public function testContructNotReserved()
	{
		$reader		= new Reader( $this->path."reader.types.ini", FALSE, FALSE );
		$assertion	= array(
			'bool1'		=> "yes",
			'bool2'		=> "true",
			'bool3'		=> "no",
			'bool4'		=> "false",
			'null'		=> "null",
			'string1'	=> "abc",
			'string2'	=> "xyz",
			'url1'		=> "http://ceusmedia.com/",
			'url2'		=> "http://ceusmedia.com/",
			'email1'	=> "example@example.com",
			'email2'	=> "example@example.com"
		);
		$creation	= $reader->toArray();
		self::assertEquals( $assertion, $creation );
	}

	public function testContructReserved()
	{
		$reader		= new Reader( $this->path."reader.types.ini", FALSE, TRUE );
		$assertion	= array(
			'bool1'		=> TRUE,
			'bool2'		=> TRUE,
			'bool3'		=> FALSE,
			'bool4'		=> FALSE,
//  not included after reading because setting Key to NULL means removing the Pair
#			'null'		=> NULL,
			'string1'	=> "abc",
			'string2'	=> "xyz",
			'url1'		=> "http://ceusmedia.com/",
			'url2'		=> "http://ceusmedia.com/",
			'email1'	=> "example@example.com",
			'email2'	=> "example@example.com"
		);
		$creation	= $reader->toArray();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getComment'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetComment()
	{
		$assertion	= "comment 2";
		$creation	= $this->list->getComment( "key2" );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->list->getComment( "key3" );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->list->getComment( "key5" );
		self::assertEquals( $assertion, $creation );


		$assertion	= "comment 2";
		$creation	= $this->sections->getComment( "key2", 'section1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->sections->getComment( "key2", 'section2' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->sections->getComment( "key5", 'section1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->sections->getComment( "key3", 'section2' );
		self::assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->getComment( "key5", 'section3' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests Method 'getCommentedProperties'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetCommentedProperties()
	{
		$assertion	= array(
			array(
				'key'		=> "key1",
				'value'		=> "value1",
				'comment'	=> "comment 1",
				'active'	=> TRUE,
			),
			array(
				'key'		=> "key2",
				'value'		=> "value2",
				'comment'	=> "comment 2",
				'active'	=> TRUE,
			),
			array(
				'key'		=> "key3",
				'value'		=> "value3",
				'comment'	=> "",
				'active'	=> TRUE,
			),
			array(
				'key'		=> "key4",
				'value'		=> "value4",
				'comment'	=> "",
				'active'	=> TRUE,
			),
		);
		$creation	= $this->list->getCommentedProperties();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				array(
					'key'		=> "key1",
					'value'		=> "value1",
					'comment'	=> "comment 1",
					'active'	=> TRUE,
				),
				array(
					'key'		=> "key2",
					'value'		=> "value2",
					'comment'	=> "comment 2",
					'active'	=> TRUE,
				),
			),
			'section2'	=> array(
				array(
					'key'		=> "key3",
					'value'		=> "value3",
					'comment'	=> "",
					'active'	=> TRUE,
				),
				array(
					'key'		=> "key4",
					'value'		=> "value4",
					'comment'	=> "",
					'active'	=> TRUE,
				),
				array(
					'key'		=> "key5",
					'value'		=> "disabled",
					'comment'	=> "",
					'active'	=> FALSE,
				),
			),
		);
		$creation	= $this->sections->getCommentedProperties( FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getComments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetComments()
	{
		$assertion	= array(
			"key1"	=> "comment 1",
			"key2"	=> "comment 2",
		);
		$creation	= $this->list->getComments();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				"key1"	=> "comment 1",
				"key2"	=> "comment 2",
			),
			'section2'	=> array(
			),
		);
		$creation	= $this->sections->getComments();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			"key1"	=> "comment 1",
			"key2"	=> "comment 2",
		);
		$creation	= $this->sections->getComments( 'section1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $this->sections->getComments( 'section2' );
		self::assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->getComments( 'section1' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests Method 'getProperties'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetProperties()
	{
		$assertion	= array(
			"key1"	=> "value1",
			"key2"	=> "value2",
			"key3"	=> "value3",
			"key4"	=> "value4",
		);
		$creation	= $this->list->getProperties();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				"key1"	=> "value1",
				"key2"	=> "value2",
			),
			'section2'	=> array(
				"key3"	=> "value3",
				"key4"	=> "value4",
			),
		);
		$creation	= $this->sections->getProperties();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			"key1"	=> "value1",
			"key2"	=> "value2",
		);
		$creation	= $this->sections->getProperties( FALSE, 'section1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			"key3"	=> "value3",
			"key4"	=> "value4",
			"key5"	=> "disabled",
		);
		$creation	= $this->sections->getProperties( FALSE, 'section2' );
		self::assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->getProperties( FALSE, 'section3' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests Method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetProperty()
	{
		$assertion	= "value3";
		$creation	= $this->list->getProperty( 'key3' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "disabled";
		$creation	= $this->list->getProperty( 'key5', NULL, FALSE );
		self::assertEquals( $assertion, $creation );

		$assertion	= "value3";
		$creation	= $this->sections->getProperty( 'key3', 'section2' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creation	= $this->list->getProperty( 'key5' );
	}

	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creation	= $this->sections->getProperty( 'key3', 'section3' );
	}

	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creation	= $this->sections->getProperty( 'key5', 'section2' );
	}
	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException4()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creation	= $this->sections->getProperty( 'key4' );
	}

	/**
	 *	Tests Method 'getPropertyList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyList()
	{
		$assertion	= array(
			"key1",
			"key2",
			"key3",
			"key4",
		);
		$creation	= $this->list->getPropertyList();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				"key1",
				"key2",
			),
			'section2'	=> array(
				"key3",
				"key4",
			),
		);
		$creation	= $this->sections->getPropertyList();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->hasProperty( 'key1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->list->hasProperty( 'key5' );
		self::assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->list->hasProperty( 'key6' );
		self::assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->hasProperty( 'key1', 'section1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->hasProperty( 'key1', 'section2' );
		self::assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->hasProperty( 'key5' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}

		try
		{
			$creation	= $this->sections->hasProperty( 'key3', 'section3' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests Method 'getSections'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSections()
	{
		try
		{
			$creation	= $this->list->getSections();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}


		$assertion	= array(
			'section1',
			'section2',
		);
		$creation	= $this->sections->getSections();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSection()
	{
		$assertion	= TRUE;
		$creation	= $this->sections->hasSection( 'section1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->hasSection( 'section3' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'hasSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSectionException()
	{
		$this->expectException( 'RuntimeException' );
		$creation	= $this->list->hasSection( "not_relevant" );
	}

	/**
	 *	Tests Method 'isActiveProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsActiveProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->isActiveProperty( 'key1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->list->isActiveProperty( 'key5' );
		self::assertEquals( $assertion, $creation );


		$assertion	= TRUE;
		$creation	= $this->sections->isActiveProperty( 'key1', 'section1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->isActiveProperty( 'key1', 'section2' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'isActiveProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsActivePropertyException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creation	= $this->sections->isActiveProperty( 'key1' );
	}

	/**
	 *	Tests Exception of Method 'isActiveProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsActivePropertyException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$creation	= $this->sections->isActiveProperty( 'key1', 'section3' );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray()
	{
		$assertion	= array(
			"key1"	=> "value1",
			"key2"	=> "value2",
			"key3"	=> "value3",
			"key4"	=> "value4",
		);
		$creation	= $this->list->toArray();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			"key1"	=> "value1",
			"key2"	=> "value2",
			"key3"	=> "value3",
			"key4"	=> "value4",
			"key5"	=> "disabled",
		);
		$creation	= $this->list->toArray( FALSE );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				"key1"	=> "value1",
				"key2"	=> "value2",
			),
			'section2'	=> array(
				"key3"	=> "value3",
				"key4"	=> "value4",
			),
		);
		$creation	= $this->sections->toArray();
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				"key1"	=> "value1",
				"key2"	=> "value2",
			),
			'section2'	=> array(
				"key3"	=> "value3",
				"key4"	=> "value4",
				"key5"	=> "disabled",
			),
		);
		$creation	= $this->sections->toArray( FALSE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'usesSections'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUsesSections()
	{
		$assertion	= FALSE;
		$creation	= $this->list->usesSections();
		self::assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->usesSections();
		self::assertEquals( $assertion, $creation );
	}
}
