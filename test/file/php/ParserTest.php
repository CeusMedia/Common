<?php
/**
 *	TestUnit of File_PHP_Parser.
 *	@package		Tests.file.php
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_PHP_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.08.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.file.php.Parser' );
/**
 *	TestUnit of File_PHP_Parser.
 *	@package		Tests.file.php
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_PHP_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.08.2008
 *	@version		0.1
 */
class File_PHP_ParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= str_replace( "\\", "/", dirname( __FILE__ ) )."/" ;
		$this->fileName	= $this->path."TestClass.php5";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$parser	= new File_PHP_Parser();
		$this->data		= $parser->parseFile( $this->fileName, $this->path );
		$this->file		= $this->data['file'];
		$this->class	= $this->data['class'];
		$this->function	= array_shift( array_slice( $this->file['functions'], 0, 1 ) );
		$this->method1	= array_shift( array_slice( $this->class['methods'], 0, 1 ) );
		$this->method2	= array_shift( array_slice( $this->class['methods'], 1, 1 ) );
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
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFile1()
	{
		$parser	= new File_PHP_Parser();
		$data		= $parser->parseFile( $this->fileName, $this->path );
		
		$this->assertTrue( is_array( $data ) );

		$assertion	= array(
			'file',
			'class',
			'source',
		);
		$creation	= array_keys( $data );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFile2()
	{
		$string		= '<?php\nphpinfo();\n?>';
		$fileName	= $this->path."parser.php";
		file_put_contents( $fileName, $string );
		
		$parser		= new File_PHP_Parser();
		$data		= $parser->parseFile( $fileName, $this->path );
		@unlink( $fileName );

		$this->assertTrue( is_array( $data ) );

		$assertion	= array(
			'class'	=> NULL,
			'file'	=> array(
				'name'			=> "parser.php",
				'uri'			=> $fileName,
				'uses'			=> array(),
				'description'	=> "",
				'package'		=> "",
				'subpackage'	=> "",
				'see'			=> array(),
				'link'			=> array(),
				'license'		=> array(),
				'copyright'		=> array(),
				'author'		=> array(),
				'version'		=> "",
				'since'			=> "",
				'functions'		=> array(),
			),
			'source'	=> $string,
		);
		$this->assertEquals( $assertion, $data );
	}



	//  --  FILE DATA  --  //

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileData()
	{
		$creation	= isset( $this->file );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->file );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataName()
	{
		$creation	= isset( $this->file['name'] );
		$this->assertTrue( $creation );

		$assertion	= "TestClass.php5";
		$creation	= $this->file['name'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataUri()
	{
		$creation	= isset( $this->file['uri'] );
		$this->assertTrue( $creation );

		$assertion	= $this->fileName;
		$creation	= $this->file['uri'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataDescription()
	{
		$creation	= isset( $this->file['description'] );
		$this->assertTrue( $creation );
		
		$assertion	= "Test Class File.\n\nThis is a Description.";
		$creation	= $this->file['description'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataPackage()
	{
		$creation	= isset( $this->file['package'] );
		$this->assertTrue( $creation );

		$assertion	= "TestPackage";
		$creation	= $this->file['package'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataSubPackage()
	{
		$creation	= isset( $this->file['subpackage'] );
		$this->assertTrue( $creation );

		$assertion	= "TestSubPackage";
		$creation	= $this->file['subpackage'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataNoExtends()
	{
		$creation	= isset( $this->file['extends'] );
		$this->assertFalse( $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataNoImplements()
	{
		$creation	= isset( $this->file['implements'] );
		$this->assertFalse( $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataAuthor()
	{
		$creation	= isset( $this->file['author'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->file['author'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			array(
				'name'	=> "Test Writer 1",
				'mail'	=> "test1@writer.tld",
			),
			array(
				'name'	=> "Test Writer 2",
				'mail'	=> "test2@writer.tld",
			),
		);
		$creation	= $this->file['author'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataSince()
	{
		$creation	= isset( $this->file['since'] );
		$this->assertTrue( $creation );

		$assertion	= "today";
		$creation	= $this->file['since'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataVersion()
	{
		$creation	= isset( $this->file['version'] );
		$this->assertTrue( $creation );

		$assertion	= "0.0.1";
		$creation	= $this->file['version'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataCopyright()
	{
		$creation	= isset( $this->file['copyright'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->file['copyright'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			"2007 Test Writer 1",
			"2008 Test Writer 2",
		);
		$creation	= $this->file['copyright'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataLicense()
	{
		$creation	= isset( $this->file['license'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->file['license'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			array(
				'url'	=> "http://test.licence.org/test1.txt",
				'name'	=> "TestLicense 1",
			),
			array(
				'url'	=> "http://test.licence.org/test2.txt",
				'name'	=> "TestLicense 2",
			)
		);
		$creation	= $this->file['license'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataSee()
	{
		$creation	= isset( $this->file['see'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->file['see'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/1',
			'http://sub.domain.tld/2',
		);
		$creation	= $this->file['see'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataLink()
	{
		$creation	= isset( $this->file['link'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->file['link'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/test1',
			'http://sub.domain.tld/test2',
		);
		$creation	= $this->file['link'];
		$this->assertEquals( $assertion, $creation );
	}





	//  --  FUNCTION DATA  --  //

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunction()
	{
		$creation	= isset( $this->file['functions'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->file['functions'] );
		$this->assertTrue( $creation );

		$assertion	= "doSomething";
		$creation	= $this->function['name'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionDescription()
	{
		$creation	= isset( $this->function['description'] );
		$this->assertTrue( $creation );

		$assertion	= "Do something.";
		$creation	= $this->function['description'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionParam()
	{
		$creation	= isset( $this->function['param'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->function['param'] );
		$this->assertTrue( $creation );


#		print_m( $this->function['param'] );
#		die;

		$assertion	= 2;
		$creation	= count( $this->function['param'] );
		$this->assertEquals( $assertion, $creation );
		
		
		$param1	= array_shift( array_slice( $this->function['param'], 0, 1 ) );

		$creation	= is_array( $param1 );
		$this->assertTrue( $creation );

		$assertion	= array(
			'cast'			=> "StringBuffer",
			'type'			=> "string",
			'reference'		=> FALSE,
			'name'			=> "string",
			'default'		=> '"text"',
			'description'	=> "A String",
		);
		$creation	= ( $param1 );
		$this->assertEquals( $assertion, $creation );
		
		$param2	= array_shift( array_slice( $this->function['param'], 1, 1 ) );

		$creation	= is_array( $param2 );
		$this->assertTrue( $creation );

		$assertion	= array(
			'cast'			=> "",
			'type'			=> "bool",
			'reference'		=> FALSE,
			'name'			=> "bool",
			'default'		=> "TRUE",
			'description'	=> "A Boolean",
		);
		$creation	= ( $param2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionReturn()
	{
		$creation	= isset( $this->function['return'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			'type'			=> "mixed",
			'desdcription'	=> ""
		);
		$creation	= isset( $this->function['return'] );
		$this->assertTrue( $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionThrows()
	{
		$creation	= isset( $this->function['throws'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->function['throws'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			"Exception",
			"RuntimeException"
		);
		$creation	= $this->function['throws'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionAuthor()
	{
		$creation	= isset( $this->function['author'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->function['author'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			array(
				'name'	=> "Test Writer 3",
				'mail'	=> "test3@writer.tld",
			),
			array(
				'name'	=> "Test Writer 4",
				'mail'	=> "test4@writer.tld",
			),
		);
		$creation	= $this->function['author'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionSince()
	{
		$creation	= isset( $this->function['since'] );
		$this->assertTrue( $creation );

		$assertion	= "01.02.03";
		$creation	= $this->function['since'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionVersion()
	{
		$creation	= isset( $this->function['version'] );
		$this->assertTrue( $creation );

		$assertion	= "1.2.3";
		$creation	= $this->function['version'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionNoAccess()
	{
		$creation	= isset( $this->function['access'] );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionNoStuff()
	{
		$creation	= isset( $this->function['stuff'] );
		$this->assertFalse( $creation );
	}





	
	//  --  CLASS DATA  --  //

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassData()
	{
		$creation	= isset( $this->data['class'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->data['class'] );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataName()
	{
		$creation	= isset( $this->class['name'] );
		$this->assertTrue( $creation );

		$assertion	= "TestClass";
		$creation	= $this->class['name'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataDescription()
	{
		$creation	= isset( $this->class['description'] );
		$this->assertTrue( $creation );

		$assertion	= "Test Class.\n\nThis is a Description.";
		$creation	= $this->class['description'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataPackage()
	{
		$creation	= isset( $this->class['package'] );
		$this->assertTrue( $creation );

		$assertion	= "TestPackage";
		$creation	= $this->class['package'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataSubPackage()
	{
		$creation	= isset( $this->class['subpackage'] );
		$this->assertTrue( $creation );

		$assertion	= "TestSubPackage";
		$creation	= $this->class['subpackage'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataAbstract()
	{
		$creation	= isset( $this->class['abstract'] );
		$this->assertTrue( $creation );

		$creation	= $this->class['abstract'];
		$this->assertTrue( $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataFinal()
	{
		$creation	= isset( $this->class['final'] );
		$this->assertTrue( $creation );

		$creation	= $this->class['final'];
		$this->assertTrue( $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataExtends()
	{
		$creation	= isset( $this->class['extends'] );
		$this->assertTrue( $creation );

		$assertion	= "Alpha";
		$creation	= $this->class['extends'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataImplements()
	{
		$creation	= isset( $this->class['implements'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->class['implements'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			'Beta',
			'Gamma'
		);
		$creation	= $this->class['implements'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataAuthor()
	{
		$creation	= isset( $this->class['author'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->class['author'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			array(
				'name'	=> "Test Writer 1",
				'mail'	=> "test1@writer.tld",
			),
			array(
				'name'	=> "Test Writer 2",
				'mail'	=> "test2@writer.tld",
			),
		);
		$creation	= $this->class['author'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataSince()
	{
		$creation	= isset( $this->class['since'] );
		$this->assertTrue( $creation );

		$assertion	= "today";
		$creation	= $this->class['since'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataVersion()
	{
		$creation	= isset( $this->class['version'] );
		$this->assertTrue( $creation );

		$assertion	= "0.0.1";
		$creation	= $this->class['version'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataCopyright()
	{
		$creation	= isset( $this->class['copyright'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->class['copyright'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			"2007 Test Writer 1",
			"2008 Test Writer 2",
		);
		$creation	= $this->class['copyright'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataLicense()
	{
		$creation	= isset( $this->class['license'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->class['license'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			array(
				'url'	=> "http://test.licence.org/test1.txt",
				'name'	=> "TestLicense 1",
			),
			array(
				'url'	=> "http://test.licence.org/test2.txt",
				'name'	=> "TestLicense 2",
			)
		);
		$creation	= $this->class['license'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataSee()
	{
		$creation	= isset( $this->class['see'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->class['see'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/1',
			'http://sub.domain.tld/2',
		);
		$creation	= $this->class['see'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataLink()
	{
		$creation	= isset( $this->class['link'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->class['link'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/test1',
			'http://sub.domain.tld/test2',
		);
		$creation	= $this->class['link'];
		$this->assertEquals( $assertion, $creation );
	}






	//  --  METHOD DATA  --  //

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethod()
	{
		$creation	= isset( $this->class['methods'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->class['methods'] );
		$this->assertTrue( $creation );

		$assertion	= "__construct";
		$creation	= $this->method1['name'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodAbstract()
	{
		$creation	= isset( $this->method1['abstract'] );
		$this->assertTrue( $creation );

		$creation	= $this->method1['abstract'];
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodFinal()
	{
		$creation	= isset( $this->method1['final'] );
		$this->assertTrue( $creation );

		$creation	= $this->method1['final'];
		$this->assertFalse( $creation );

		$creation	= isset( $this->method2['final'] );
		$this->assertTrue( $creation );

		$creation	= $this->method2['final'];
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodStatic()
	{
		$creation	= isset( $this->method1['static'] );
		$this->assertTrue( $creation );

		$creation	= $this->method1['static'];
		$this->assertTrue( $creation );

		$creation	= isset( $this->method2['static'] );
		$this->assertTrue( $creation );

		$creation	= $this->method2['static'];
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodDescription()
	{
		$creation	= isset( $this->method1['description'] );
		$this->assertTrue( $creation );

		$assertion	= "Description Line 1.\n\nDescription Line 2.\nDescription Line 3.";
		$creation	= $this->method1['description'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodAccess()
	{
		$creation	= isset( $this->method1['access'] );
		$this->assertTrue( $creation );

		$assertion	= "public";
		$creation	= isset( $this->method1['access'] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodParam()
	{
		$creation	= isset( $this->method1['param'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->method1['param'] );
		$this->assertTrue( $creation );

		$assertion	= 2;
		$creation	= count( $this->method1['param'] );
		$this->assertEquals( $assertion, $creation );
		
		$param1	= array_shift( array_slice( $this->method1['param'], 0, 1 ) );

		$creation	= is_array( $param1 );
		$this->assertTrue( $creation );

		$assertion	= array(
			'cast'			=> "ArrayObject",
			'type'			=> "array",
			'reference'		=> TRUE,
			'name'			=> "param1",
			'default'		=> 'array()',
			'description'	=> "An Array or something...",
		);
		$creation	= ( $param1 );
		$this->assertEquals( $assertion, $creation );
		
		$param2	= array_shift( array_slice( $this->method1['param'], 1, 1 ) );

		$creation	= is_array( $param2 );
		$this->assertTrue( $creation );

		$assertion	= array(
			'cast'			=> "",
			'type'			=> "mixed",
			'reference'		=> TRUE,
			'name'			=> "param2",
			'default'		=> "NULL",
			'description'	=> "unknown Type",
		);
		$creation	= ( $param2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodReturn()
	{
		$creation	= isset( $this->method1['return'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			'type'			=> "void",
			'desdcription'	=> "nothing"
		);
		$creation	= isset( $this->method1['return'] );
		$this->assertTrue( $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodThrows()
	{
		$creation	= isset( $this->method1['throws'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->method1['throws'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			"LogicException",
			"BadMethodCallException"
		);
		$creation	= $this->method1['throws'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodAuthor()
	{
		$creation	= isset( $this->method1['author'] );
		$this->assertTrue( $creation );

		$creation	= is_array( $this->method1['author'] );
		$this->assertTrue( $creation );

		$assertion	= array(
			array(
				'name'	=> "Test Writer 5",
				'mail'	=> "test5@writer.tld",
			),
			array(
				'name'	=> "Test Writer 6",
				'mail'	=> "test6@writer.tld",
			),
		);
		$creation	= $this->method1['author'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodSince()
	{
		$creation	= isset( $this->method1['since'] );
		$this->assertTrue( $creation );

		$assertion	= "03.02.01";
		$creation	= $this->method1['since'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodVersion()
	{
		$creation	= isset( $this->method1['version'] );
		$this->assertTrue( $creation );

		$assertion	= "3.2.1";
		$creation	= $this->method1['version'];
		$this->assertEquals( $assertion, $creation );
	}
}
?>