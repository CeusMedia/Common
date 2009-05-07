<?php
/**
 *	TestUnit of INI Reader.
 *	@package		Tests.file.yaml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_INI_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
import( 'de.ceus-media.file.ini.Reader' );
/**
 *	TestUnit of INI Reader.
 *	@package		Tests.file.yaml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_INI_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class File_INI_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "file/ini/reader.ini";
		

	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
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
		$reader		= new File_INI_Reader( $this->fileName );
		$creation	= $reader->toArray();
		$this->assertEquals( $assertion, $creation );


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
		$reader		= new File_INI_Reader( $this->fileName, TRUE );
		$creation	= $reader->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	public function testContructNotReserved()
	{
		$reader		= new File_INI_Reader( $this->path."reader.types.ini", FALSE, FALSE );
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
		$this->assertEquals( $assertion, $creation );
	}

	public function testContructReserved()
	{
		$reader		= new File_INI_Reader( $this->path."reader.types.ini", FALSE, TRUE );
		$assertion	= array(
			'bool1'		=> TRUE,
			'bool2'		=> TRUE,
			'bool3'		=> FALSE,
			'bool4'		=> FALSE,
#			'null'		=> NULL,		//  not included after reading because setting Key to NULL means removing the Pair
			'string1'	=> "abc",
			'string2'	=> "xyz",
			'url1'		=> "http://ceusmedia.com/",
			'url2'		=> "http://ceusmedia.com/",
			'email1'	=> "example@example.com",
			'email2'	=> "example@example.com"
		);
		$creation	= $reader->toArray();
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );
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
		$creation	= $this->sections->getProperties( FALSE, 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			"key3"	=> "value3",
			"key4"	=> "value4",
			"key5"	=> "disabled",
		);
		$creation	= $this->sections->getProperties( FALSE, 'section2' );
		$this->assertEquals( $assertion, $creation );

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
		$this->assertEquals( $assertion, $creation );

		$assertion	= "disabled";
		$creation	= $this->list->getProperty( 'key5', FALSE, FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "value3";
		$creation	= $this->sections->getProperty( 'key3', 'section2' );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
		$creation	= $this->list->getProperty( 'key5' );
	}

	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
		$creation	= $this->sections->getProperty( 'key3', 'section3' );
	}

	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
		$creation	= $this->sections->getProperty( 'key5', 'section2' );
	}
	/**
	 *	Tests Exception method 'getProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPropertyException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );	
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
	 *	Tests Method 'hasProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasProperty()
	{
		$assertion	= TRUE;
		$creation	= $this->list->hasProperty( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->list->hasProperty( 'key5' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->list->hasProperty( 'key6' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->hasProperty( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->hasSection( 'section3' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'hasSection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSectionException()
	{
		$this->setExpectedException( 'RuntimeException' );
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->list->isActiveProperty( 'key5' );
		$this->assertEquals( $assertion, $creation );


		$assertion	= TRUE;
		$creation	= $this->sections->isActiveProperty( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->sections->isActiveProperty( 'key1', 'section2' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'isActiveProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsActivePropertyException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$creation	= $this->sections->isActiveProperty( 'key1' );
	}

	/**
	 *	Tests Exception of Method 'isActiveProperty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsActivePropertyException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$creation	= $this->sections->isActiveProperty( 'key1', 'section3' );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray()
	{
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->sections->usesSections();
		$this->assertEquals( $assertion, $creation );
	}
}
?>