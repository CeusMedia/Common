<?php
/**
 *	TestUnit of XML_Atom_Parser.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.05.2008
 *
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML_Atom_Parser.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.05.2008
 *
 */
class Test_XML_Atom_ParserTest extends Test_Case
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
		$parser	= new Test_XML_Atom_ParserInstance;

		$entry		= $parser->getProtectedVar( 'emptyEntry' );
		$assertion	= $parser->getProtectedVar( 'emptyText' );

		$creation	= $entry['content'];
		$this->assertEquals( $assertion, $creation );

		$creation	= $entry['summary'];
		$this->assertEquals( $assertion, $creation );

		$creation	= $entry['title'];
		$this->assertEquals( $assertion, $creation );

		$entry		= $parser->getProtectedVar( 'emptyEntry' );
		$source		= $entry['source'];
		unset( $entry['source'] );
		$assertion	= $source;
		$creation	= $entry;
		$this->assertEquals( $assertion, $creation );
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
		$parser	= new XML_Atom_Parser();
		$parser->parse( $xml );
		$data	= array(
			'channel'	=> $parser->channelData,
			'entries'	=> $parser->entries
		);
#		file_put_contents( $serial, serialize( $data ) );


		$assertion	= unserialize( file_get_contents( $serial ) );
		$creation	= $data;
		$this->assertEquals( $assertion, $creation );
	}
}
class Test_XML_Atom_ParserInstance extends XML_Atom_Parser
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
