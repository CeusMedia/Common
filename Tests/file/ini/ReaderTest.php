<?php
/**
 *	TestUnit of INI Reader.
 *	@package		Tests.file.yaml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			INIReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.ini.Reader' );
/**
 *	TestUnit of INI Reader.
 *	@package		Tests.file.yaml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_INI_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_File_INI_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "Tests/file/ini/reader.ini";
		

	public function __construct()
	{
		$this->list		= new File_INI_Reader( $this->fileName );
		$this->sections	= new File_INI_Reader( $this->fileName, true );

	}
	public function testContruct()
	{
		$assertion	= array(
			"key1"	=> "value1",
			"key2"	=> "value2",
			"key3"	=> "value3",
			"key4"	=> "value4",
		);
		$creation	= $this->list->toArray();
		$this->assertEquals( $assertion, $creation );

		$reader	= new File_INI_Reader( $this->fileName, true );
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
		$creation	= $this->sections->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'getComment'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetComment()
	{
		$assertion	= "comment 2";
		$creation	= $this->list->getComment( "key2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->list->getComment( "key3" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->list->getComment( "key5" );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "comment 2";
		$creation	= $this->sections->getComment( "key2", 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->sections->getComment( "key2", 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->sections->getComment( "key5", 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->sections->getComment( "key3", 'section2' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->getComment( "key5", 'section3' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests method 'getCommentedProperties'.
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
				'active'	=> true,
			),
			array(
				'key'		=> "key2",
				'value'		=> "value2",
				'comment'	=> "comment 2",
				'active'	=> true,
			),
			array(
				'key'		=> "key3",
				'value'		=> "value3",
				'comment'	=> "",
				'active'	=> true,
			),
			array(
				'key'		=> "key4",
				'value'		=> "value4",
				'comment'	=> "",
				'active'	=> true,
			),
		);
		$creation	= $this->list->getCommentedProperties();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				array(
					'key'		=> "key1",
					'value'		=> "value1",
					'comment'	=> "comment 1",
					'active'	=> true,
				),
				array(
					'key'		=> "key2",
					'value'		=> "value2",
					'comment'	=> "comment 2",
					'active'	=> true,
				),
			),
			'section2'	=> array(
				array(
					'key'		=> "key3",
					'value'		=> "value3",
					'comment'	=> "",
					'active'	=> true,
				),
				array(
					'key'		=> "key4",
					'value'		=> "value4",
					'comment'	=> "",
					'active'	=> true,
				),
			),
		);
		$creation	= $this->sections->getCommentedProperties();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'getComments'.
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				"key1"	=> "comment 1",
				"key2"	=> "comment 2",
			),
			'section2'	=> array(
			),
		);
		$creation	= $this->sections->getComments();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			"key1"	=> "comment 1",
			"key2"	=> "comment 2",
		);
		$creation	= $this->sections->getComments( 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $this->sections->getComments( 'section2' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->getComments( 'section1' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests method 'getProperties'.
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
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			"key1"	=> "value1",
			"key2"	=> "value2",
		);
		$creation	= $this->sections->getProperties( false, 'section1' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->getProperties( false, 'section3' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests method ''.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetProperty()
	{
		$assertion	= "value3";
		$creation	= $this->list->getProperty( 'key3' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->list->getProperty( 'key5' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}

		$assertion	= "value3";
		$creation	= $this->sections->getProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->getProperty( 'key3', 'section3' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}

		try
		{
			$creation	= $this->sections->getProperty( 'key5', 'section2' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}

		try
		{
			$creation	= $this->sections->getProperty( 'key4' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests method 'getPropertyList'.
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
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'hasProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasProperty()
	{
		$assertion	= true;
		$creation	= $this->list->hasProperty( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->list->hasProperty( 'key5' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->sections->hasProperty( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->sections->hasProperty( 'key1', 'section2' );
		$this->assertEquals( $assertion, $creation );

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
	 *	Tests method 'getSections'.
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
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'hasSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSection()
	{
		try
		{
			$creation	= $this->list->hasSection();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}


		$assertion	= true;
		$creation	= $this->sections->hasSection( 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->sections->hasSection( 'section3' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'isActiveProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsActiveProperty()
	{
		$assertion	= true;
		$creation	= $this->list->isActiveProperty( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->list->isActiveProperty( 'key5' );
		$this->assertEquals( $assertion, $creation );


		$assertion	= true;
		$creation	= $this->sections->isActiveProperty( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->sections->isActiveProperty( 'key1', 'section2' );
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->sections->isActiveProperty( 'key1' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}

		try
		{
			$creation	= $this->sections->isActiveProperty( 'key1', 'section3' );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ){}
	}

	/**
	 *	Tests method 'toArray'.
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
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'toCommentedArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToCommentedArray()
	{
		$assertion	= array(
			array(
				'key'		=> "key1",
				'value'		=> "value1",
				'comment'	=> "comment 1",
				'active'	=> true,
			),
			array(
				'key'		=> "key2",
				'value'		=> "value2",
				'comment'	=> "comment 2",
				'active'	=> true,
			),
			array(
				'key'		=> "key3",
				'value'		=> "value3",
				'comment'	=> "",
				'active'	=> true,
			),
			array(
				'key'		=> "key4",
				'value'		=> "value4",
				'comment'	=> "",
				'active'	=> true,
			),
		);
		$creation	= $this->list->toCommentedArray();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'section1'	=> array(
				array(
					'key'		=> "key1",
					'value'		=> "value1",
					'comment'	=> "comment 1",
					'active'	=> true,
				),
				array(
					'key'		=> "key2",
					'value'		=> "value2",
					'comment'	=> "comment 2",
					'active'	=> true,
				),
			),
			'section2'	=> array(
				array(
					'key'		=> "key3",
					'value'		=> "value3",
					'comment'	=> "",
					'active'	=> true,
				),
				array(
					'key'		=> "key4",
					'value'		=> "value4",
					'comment'	=> "",
					'active'	=> true,
				),
			),
		);
		$creation	= $this->sections->toCommentedArray();
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests method 'usesSections'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUsesSections()
	{
		$assertion	= false;
		$creation	= $this->list->usesSections();
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= $this->sections->usesSections();
		$this->assertEquals( $assertion, $creation );
	}
}
?>