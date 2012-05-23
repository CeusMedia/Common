<?php
/**
 *	TestUnit of File_PHP_Parser_Regular.
 *	@package		Tests.file.php
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.08.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of File_PHP_Parser_Regular.
 *	@package		Tests.file.php
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_PHP_Parser_Regular
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.08.2008
 *	@version		0.1
 */
class Test_File_PHP_Parser_RegularTest extends PHPUnit_Framework_TestCase
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
		$parser	= new File_PHP_Parser_Regular();
		$this->data		= $parser->parseFile( $this->fileName, $this->path );
		$this->file		= $this->data->getUri();
		$this->class	= array_shift( array_slice( $this->data->getClasses(), 0, 1 ) );
		$this->function	= array_shift( array_slice( $this->data->getFunctions(), 0, 1 ) );
		$this->method1	= array_shift( array_slice( $this->class->getMethods(), 0, 1 ) );
		$this->method2	= array_shift( array_slice( $this->class->getMethods(), 1, 1 ) );
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
		$parser	= new File_PHP_Parser_Regular();
		$data		= $parser->parseFile( $this->fileName, $this->path );
		$this->assertTrue( $data instanceof ADT_PHP_File );

		$creation	= is_array( $data->getClasses() );
		$this->assertTrue(  $creation );

		$creation	= is_array( $data->getFunctions() );
		$this->assertTrue( $creation );

		$creation	= is_array( $data->getTodos() );
		$this->assertTrue( $creation );
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
		
		$parser		= new File_PHP_Parser_Regular();
		$data		= $parser->parseFile( $fileName, $this->path );
		@unlink( $fileName );

		$this->assertTrue( $data instanceof ADT_PHP_File );
	}



	//  --  FILE DATA  --  //

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileData()
	{
		$this->assertTrue( $this->data instanceof ADT_PHP_File );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataName()
	{
		$assertion	= "TestClass.php5";
		$creation	= $this->data->getBasename();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataUri()
	{
		$assertion	= $this->fileName;
		$creation	= $this->data->getUri();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataDescription()
	{
		$assertion	= "Test Class File.\n\nThis is a Description.";
		$creation	= $this->data->getDescription();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataPackage()
	{
		$assertion	= "TestPackage";
		$creation	= $this->data->getPackage();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataAuthor()
	{
		$creation	= is_array( $this->data->getAuthors() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_Author( "Test Writer 1", "test1@writer.tld" ),
			new ADT_PHP_Author( "Test Writer 2", "test2@writer.tld" ),
		);
		$creation	= $this->data->getAuthors();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataSince()
	{
		$assertion	= "today";
		$creation	= $this->data->getSince();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataVersion()
	{
		$assertion	= "0.0.1";
		$creation	= $this->data->getVersion();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataCopyright()
	{
		$creation	= is_array( $this->data->getCopyright() );
		$this->assertTrue( $creation );

		$assertion	= array(
			"2007 Test Writer 1",
			"2008 Test Writer 2",
		);
		$creation	= $this->data->getCopyright();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataLicense()
	{
		$creation	= is_array( $this->data->getLicenses() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_License( "TestLicense 1", "http://test.licence.org/test1.txt" ),
			new ADT_PHP_License( "TestLicense 2", "http://test.licence.org/test2.txt" ),
		);
		$creation	= $this->data->getLicenses();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataSee()
	{
		$creation	= is_array( $this->data->getSees() );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/1',
			'http://sub.domain.tld/2',
		);
		$creation	= $this->data->getSees();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataLink()
	{
		$creation	= is_array( $this->data->getLinks() );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/test1',
			'http://sub.domain.tld/test2',
		);
		$creation	= $this->data->getLinks();
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
		$creation	= is_array( $this->data->getFunctions() );
		$this->assertTrue( $creation );

		$assertion	= "doSomething";
		$creation	= $this->function->getName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionDescription()
	{
		$assertion	= "Do something.";
		$creation	= $this->function->getDescription();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionParam()
	{
		$creation	= is_array( $this->function->getParameters() );
		$this->assertTrue( $creation );

		$assertion	= 3;
		$creation	= count( $this->function->getParameters() );
		$this->assertEquals( $assertion, $creation );
		
		
		$param1	= array_shift( array_slice( $this->function->getParameters(), 0, 1 ) );
		$param2	= array_shift( array_slice( $this->function->getParameters(), 1, 1 ) );
		$param3	= array_shift( array_slice( $this->function->getParameters(), 2, 1 ) );

		$this->assertTrue( is_object( $param1 ) );
		$this->assertTrue( $param1 instanceof ADT_PHP_Parameter );
		$this->assertEquals( "StringBuffer", $param1->getCast() );
		$this->assertEquals( "StringBuffer", $param1->getType() );
		$this->assertFalse( $param1->isReference() );
		$this->assertEquals( "buffer", $param1->getName() );
		$this->assertEquals( NULL, $param1->getDefault() );
		$this->assertEquals( "A String Buffer", $param1->getDescription() );

		$this->assertTrue( is_object( $param2 ) );
		$this->assertTrue( $param2 instanceof ADT_PHP_Parameter );
		$this->assertEquals( "", $param2->getCast() );
		$this->assertEquals( "string", $param2->getType() );
		$this->assertFalse( $param2->isReference() );
		$this->assertEquals( "string", $param2->getName() );
		$this->assertEquals( '"text"', $param2->getDefault() );
		$this->assertEquals( "A String", $param2->getDescription() );

		$this->assertTrue( is_object( $param3 ) );
		$this->assertTrue( $param3 instanceof ADT_PHP_Parameter );
		$this->assertEquals( "", $param3->getCast() );
		$this->assertEquals( "bool", $param3->getType() );
		$this->assertFalse( $param3->isReference() );
		$this->assertEquals( "bool", $param3->getName() );
		$this->assertEquals( "TRUE", $param3->getDefault() );
		$this->assertEquals( "A Boolean", $param3->getDescription() );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionReturn()
	{
		$this->assertTrue( $this->function->getReturn() instanceof ADT_PHP_Return );
		$assertion	= new ADT_PHP_Return( "mixed", "" );
		$this->assertEquals( $assertion, $this->function->getReturn() );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionThrows()
	{
		$creation	= is_array( $this->function->getThrows() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_Throws( "Exception", "if something went unexpectedly wrong" ),
			new ADT_PHP_Throws( "RuntimeException", "if something went wrong" )
		);
		$creation	= $this->function->getThrows();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionAuthor()
	{
		$creation	= is_array( $this->function->getAuthors() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_Author( "Test Writer 3", "test3@writer.tld" ),
			new ADT_PHP_Author( "Test Writer 4", "test4@writer.tld" ),
		);
		$creation	= $this->function->getAuthors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionSince()
	{
		$assertion	= "01.02.03";
		$creation	= $this->function->getSince();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileFileDataFunctionVersion()
	{
		$assertion	= "1.2.3";
		$creation	= $this->function->getVersion();
		$this->assertEquals( $assertion, $creation );
	}


	
	
	//  --  CLASS DATA  --  //

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassData()
	{
		$creation	= is_array( $this->data->getClasses() );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataName()
	{
		$assertion	= "TestClass";
		$creation	= $this->class->getName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataDescription()
	{
		$assertion	= "Test Class.\n\nThis is a Description.";
		$creation	= $this->class->getDescription();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataPackage()
	{
		$assertion	= "TestPackage";
		$creation	= $this->class->getPackage();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataSubPackage()
	{
		$assertion	= "TestSubPackage";
		$creation	= $this->class->getSubpackage();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataAbstract()
	{
		$creation	= $this->class->isAbstract();
		$this->assertTrue( $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataFinal()
	{
		$this->assertFalse( $this->class->isFinal() );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataExtends()
	{
		$assertion	= 'Alpha';
		$creation	= $this->class->getExtendedClass();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataImplements()
	{
		$creation	= is_array( $this->class->getImplementedInterfaces() );
		$this->assertTrue( $creation );

		$assertion	= array_combine( array( 'Beta', 'Gamma' ), array( 'Beta', 'Gamma' ) );
		$creation	= $this->class->getImplementedInterfaces();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataAuthor()
	{
		$creation	= is_array( $this->class->getAuthors() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_Author( "Test Writer 1", "test1@writer.tld" ),
			new ADT_PHP_Author( "Test Writer 2", "test2@writer.tld" ),
		);
		$creation	= $this->class->getAuthors();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataSince()
	{
		$assertion	= "today";
		$creation	= $this->class->getSince();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataVersion()
	{
		$assertion	= "0.0.1";
		$creation	= $this->class->getVersion();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataCopyright()
	{
		$creation	= is_array( $this->class->getCopyright() );
		$this->assertTrue( $creation );

		$assertion	= array(
			"2007 Test Writer 1",
			"2008 Test Writer 2",
		);
		$creation	= $this->class->getCopyright();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataLicense()
	{
		$creation	= is_array( $this->class->getLicenses() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_License( "TestLicense 1", "http://test.licence.org/test1.txt" ),
			new ADT_PHP_License( "TestLicense 2", "http://test.licence.org/test2.txt" ),
		);
		$creation	= $this->class->getLicenses();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataSee()
	{
		$creation	= is_array( $this->class->getSees() );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/1',
			'http://sub.domain.tld/2',
		);
		$creation	= $this->class->getSees();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataLink()
	{
		$creation	= is_array( $this->class->getLinks() );
		$this->assertTrue( $creation );

		$assertion	= array(
			'http://sub.domain.tld/test1',
			'http://sub.domain.tld/test2',
		);
		$creation	= $this->class->getLinks();
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
		$creation	= is_array( $this->class->getMethods() );
		$this->assertTrue( $creation );

		$assertion	= "__construct";
		$creation	= $this->method1->getName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodAbstract()
	{
		$creation	= $this->method1->isAbstract();
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodFinal()
	{
		$creation	= $this->method1->isFinal();
		$this->assertFalse( $creation );

		$creation	= $this->method2->isFinal();
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodStatic()
	{
		$creation	= $this->method1->isStatic();
		$this->assertFalse( $creation );

		$creation	= $this->method2->isStatic();
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodDescription()
	{
		$assertion	= "Description Line 1.\n\nDescription Line 2.\nDescription Line 3.";
		$creation	= $this->method1->getDescription();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodAccess()
	{
		$assertion	= "public";
		$creation	= $this->method1->getAccess();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodParam()
	{
		$creation	= is_array( $this->method1->getParameters() );
		$this->assertTrue( $creation );

		$assertion	= 4;
		$creation	= count( $this->method1->getParameters() );
		$this->assertEquals( $assertion, $creation );
		
		$param1	= array_shift( array_slice( $this->method1->getParameters(), 0, 1 ) );
		$param2	= array_shift( array_slice( $this->method1->getParameters(), 1, 1 ) );
		$param3	= array_shift( array_slice( $this->method1->getParameters(), 2, 1 ) );
		$param4	= array_shift( array_slice( $this->method1->getParameters(), 3, 1 ) );

		$this->assertTrue( is_object( $param1 ) );
		$this->assertTrue( $param1 instanceof ADT_PHP_Parameter );
		$this->assertFalse( $param1->isReference() );
		$this->assertEquals( "object", $param1->getName() );
		$this->assertEquals( "ArrayObject", $param1->getType() );
		$this->assertEquals( "ArrayObject", $param1->getCast() );
		$this->assertEquals( NULL, $param1->getDefault() );
		$this->assertEquals( "An Array Object", $param1->getDescription() );
		
		$this->assertTrue( is_object( $param2 ) );
		$this->assertTrue( $param2 instanceof ADT_PHP_Parameter );
		$this->assertTrue( $param2->isReference() );
		$this->assertEquals( "reference", $param2->getName() );
		$this->assertEquals( "mixed", $param2->getType() );
		$this->assertEquals( NULL, $param2->getCast() );
		$this->assertEquals( NULL, $param2->getDefault() );
		$this->assertEquals( "Reference of unknown Type", $param2->getDescription() );

		$this->assertTrue( is_object( $param3 ) );
		$this->assertTrue( $param3 instanceof ADT_PHP_Parameter );
		$this->assertFalse( $param3->isReference() );
		$this->assertEquals( "array", $param3->getName() );
		$this->assertEquals( "array", $param3->getType() );
		$this->assertEquals( NULL, $param3->getCast() );
		$this->assertEquals( "array()", $param3->getDefault() );
		$this->assertEquals( "An Array", $param3->getDescription() );

		$this->assertTrue( is_object( $param4 ) );
		$this->assertTrue( $param4 instanceof ADT_PHP_Parameter );
		$this->assertFalse( $param4->isReference() );
		$this->assertEquals( "null", $param4->getName() );
		$this->assertEquals( "mixed", $param4->getType() );
		$this->assertEquals( NULL, $param4->getCast() );
		$this->assertEquals( "NULL", $param4->getDefault() );
		$this->assertEquals( "Always NULL", $param4->getDescription() );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodReturn()
	{
		$assertion	= new ADT_PHP_Return( "void", "nothing" );
		$creation	= $this->method1->getReturn();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodThrows()
	{
		$creation	= is_array( $this->method1->getThrows() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_Throws( 'LogicException', 'if something without logic is happening' ),
			new ADT_PHP_Throws( 'BadMethodCallException', 'if a bad method is called' ),
		);
		$creation	= $this->method1->getThrows();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodAuthor()
	{
		$creation	= is_array( $this->method1->getAuthors() );
		$this->assertTrue( $creation );

		$assertion	= array(
			new ADT_PHP_Author( "Test Writer 5", "test5@writer.tld" ),
			new ADT_PHP_Author( "Test Writer 6", "test6@writer.tld" )
		);
		$creation	= $this->method1->getAuthors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodSince()
	{
		$assertion	= "03.02.01";
		$creation	= $this->method1->getSince();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'parseFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseFileClassDataMethodVersion()
	{
		$assertion	= "3.2.1";
		$creation	= $this->method1->getVersion();
		$this->assertEquals( $assertion, $creation );
	}
}
?>