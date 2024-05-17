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
	 *	Tests Method 'getArguments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetArguments(): void
	{
		$arguments	= array( 'a' => "b" );
		$this->parser->setProtectedVar( 'parsed', TRUE );
		$this->parser->setProtectedVar( 'foundArguments', $arguments );

		$assertion	= $arguments;
		$creation	= $this->parser->getArguments();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getArguments'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetArgumentsException(): void
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
	public function testGetOptions(): void
	{
		$options	= array( 'a' => "b" );
		$this->parser->setProtectedVar( 'parsed', TRUE );
		$this->parser->setProtectedVar( 'foundOptions', $options );

		$assertion	= $options;
		$creation	= $this->parser->getOptions();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOptionsException(): void
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
	public function testParse(): void
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
		self::assertEquals( $assertion, $creation );


		$assertion	= array(
			'alpha'	=> "xyz",
			'beta'	=> "123",
			'force'	=> TRUE,
		);
		$creation	= $parser->getOptions();
		ksort( $creation );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseException1(): void
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
	public function testParseException2(): void
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
	public function testParseException3(): void
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
	public function testParseException4(): void
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
	public function testSetNumberOfMandatoryArguments(): void
	{
		$this->parser->setNumberOfMandatoryArguments( 1 );

		$assertion	= 1;
		$creation	= $this->parser->getProtectedVar( 'numberArguments' );
		self::assertEquals( $assertion, $creation );

		$this->parser->setNumberOfMandatoryArguments( 2 );

		$assertion	= 2;
		$creation	= $this->parser->getProtectedVar( 'numberArguments' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPossibleOptions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPossibleOptions(): void
	{
		$options	= array( 'a' => "b" );
		$this->parser->setPossibleOptions( $options );

		$assertion	= $options;
		$creation	= $this->parser->getProtectedVar( 'possibleOptions' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setShortcuts'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetShortcuts(): void
	{
		$options	= array( 'a' => "b" );
		$this->parser->setPossibleOptions( $options );

		$assertion	= $options;
		$creation	= $this->parser->getProtectedVar( 'possibleOptions' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	protected function setUp(): void
	{
		$this->parser	= new ArgumentParserInstance();
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	protected function tearDown(): void
	{
	}
}
class ArgumentParserInstance extends ArgumentParser
{
	public function getProtectedVar( string $varName ): mixed
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	public function setProtectedVar( string $varName, mixed $varValue ): void
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		$this->$varName	= $varValue;
	}
}
