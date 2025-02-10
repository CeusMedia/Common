<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML_Atom_Parser.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\Atom;

use CeusMedia\CommonTest\BaseCase;
use CeusMedia\Common\XML\Atom\Parser;
use Exception;

/**
 *	TestUnit of XML_Atom_Parser.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ParserTest extends BaseCase
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

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$parser	= new ParserInstance;

		$entry		= $parser->getProtectedVar( 'emptyEntry' );
		$assertion	= $parser->getProtectedVar( 'emptyText' );

		$creation	= $entry['content'];
		self::assertEquals( $assertion, $creation );

		$creation	= $entry['summary'];
		self::assertEquals( $assertion, $creation );

		$creation	= $entry['title'];
		self::assertEquals( $assertion, $creation );

		$entry		= $parser->getProtectedVar( 'emptyEntry' );
		$source		= $entry['source'];
		unset( $entry['source'] );
		$assertion	= $source;
		$creation	= $entry;
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$path	= dirname( __FILE__ )."/";
		$atom	= $path."golem.atom";
		$serial	= $path."golem.serial";

		$xml	= file_get_contents( $atom );
		$parser	= new Parser();
		$parser->parse( $xml );
		$data	= array(
			'channel'	=> $parser->channelData,
			'entries'	=> $parser->entries
		);
#		file_put_contents( $serial, serialize( $data ) );


		$assertion	= unserialize( file_get_contents( $serial ) );
		$creation	= $data;
		self::assertEquals( $assertion, $creation );
	}
}
class ParserInstance extends Parser
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
