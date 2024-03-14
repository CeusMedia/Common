<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of CLI_Command_ArgumentParser.
 *	@package		Tests.CLI.Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\CLI\Command;

use CeusMedia\Common\CLI\Command\ArgumentParser;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	TestUnit of CLI_Command_ArgumentParser.
 *	@package		Tests.CLI.Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ArgumentParserTest extends BaseCase
{
	protected ArgumentParserInstance $parser;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->parser	= new ArgumentParserInstance();
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
		$this->expectException( 'RuntimeException' );

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
		$this->expectException( 'RuntimeException' );

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

		$parser	= new ArgumentParser();
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
			'force'	=> TRUE,
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
		$this->expectException( 'RuntimeException' );
		$this->parser->setNumberOfMandatoryArguments( 2 );
		$this->parser->parse( "Argument1" );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->parser->setPossibleOptions( array( 'a' => TRUE ) );
		$this->parser->parse( "-b" );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException3()
	{
		$this->expectException( 'RuntimeException' );
		$this->parser->setPossibleOptions( array( 'a' => TRUE ) );
		$this->parser->parse( "-a" );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException4()
	{
		$this->expectException( 'InvalidArgumentException' );
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
class ArgumentParserInstance extends ArgumentParser
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
