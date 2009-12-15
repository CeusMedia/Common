<?php
/**
 *	TestUnit of Console_Command_ArgumentParser.
 *	@package		Tests.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.10.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Console_Command_ArgumentParser.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Console_Command_ArgumentParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.10.2008
 *	@version		0.1
 */
class Test_Console_Command_ArgumentParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->parser	= new Test_Console_Command_ArgumentParserInstance();
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
	 *	Tests Method 'getArguments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetArguments()
	{
		$arguments	= array( 'a' => "b" );
		$this->parser->setProtectedVar( 'parsed', TRUE );
		$this->parser->setProtectedVar( 'foundArguments', $arguments );
		
		$assertion	= $arguments;
		$creation	= $this->parser->getArguments();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getArguments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetArgumentsException()
	{
		$this->setExpectedException( 'RuntimeException' );

		$arguments	= array( 'a' => "b" );
		$this->parser->setProtectedVar( 'foundArguments', $arguments );
		
		$this->parser->getArguments();
	}

	/**
	 *	Tests Method 'getOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOptions()
	{
		$options	= array( 'a' => "b" );
		$this->parser->setProtectedVar( 'parsed', TRUE );
		$this->parser->setProtectedVar( 'foundOptions', $options );
		
		$assertion	= $options;
		$creation	= $this->parser->getOptions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOptionsException()
	{
		$this->setExpectedException( 'RuntimeException' );

		$options	= array( 'a' => "b" );
		$this->parser->setProtectedVar( 'foundOptions', $options );
		
		$this->parser->getOptions();
	}

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$options	= array(
			'alpha'	=> "@[a-z]@i",
			'beta'	=> "@[0-9]@i",
			'force'	=> "",
		);
		$shortcuts	= array(
			'a'		=> 'alpha',
			'b'		=> 'beta',
			'f'		=> 'force',
		);
		$string		= "-a xyz -beta 123 -f Argument1 Argument2";

		$parser	= new Console_Command_ArgumentParser();
		$parser->setNumberOfMandatoryArguments( 2 );
		$parser->setPossibleOptions( $options );
		$parser->setShortcuts( $shortcuts );
		$parser->parse( $string );
		

		$assertion	= array(
			"Argument1",
			"Argument2"
		);
		$creation	= $parser->getArguments();
		$this->assertEquals( $assertion, $creation );


		$assertion	= array(
			'alpha'	=> "xyz",
			'beta'	=> "123",
			'force'	=> NULL,
		);
		$creation	= $parser->getOptions();
		ksort( $creation );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->parser->parse( 1 );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException2()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->parser->setNumberOfMandatoryArguments( 2 );
		$this->parser->parse( "Argument1" );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->parser->setPossibleOptions( array( 'a' => TRUE ) );
		$this->parser->parse( "-b" );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException4()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->parser->setPossibleOptions( array( 'a' => TRUE ) );
		$this->parser->parse( "-a" );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException5()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->parser->setPossibleOptions( array( 'a' => TRUE ) );
		$this->parser->parse( "-b -a" );
	}

	/**
	 *	Tests Method 'setNumberOfMandatoryArguments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetNumberOfMandatoryArguments()
	{
		$this->parser->setNumberOfMandatoryArguments( 1 );
		
		$assertion	= 1;
		$creation	= $this->parser->getProtectedVar( 'numberArguments' );
		$this->assertEquals( $assertion, $creation );

		$this->parser->setNumberOfMandatoryArguments( 2 );
		
		$assertion	= 2;
		$creation	= $this->parser->getProtectedVar( 'numberArguments' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPossibleOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPossibleOptions()
	{
		$options	= array( 'a' => "b" );
		$this->parser->setPossibleOptions( $options );
		
		$assertion	= $options;
		$creation	= $this->parser->getProtectedVar( 'possibleOptions' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setShortcuts'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetShortcuts()
	{
		$options	= array( 'a' => "b" );
		$this->parser->setPossibleOptions( $options );
		
		$assertion	= $options;
		$creation	= $this->parser->getProtectedVar( 'possibleOptions' );
		$this->assertEquals( $assertion, $creation );
	}
}
class Test_Console_Command_ArgumentParserInstance extends Console_Command_ArgumentParser
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	public function setProtectedVar( $varName, $varValue )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		$this->$varName	= $varValue;
	}
}
?>