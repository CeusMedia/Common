<?php
declare( strict_types = 1 );

/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\XML\RSS;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\XML\RSS\Parser;

/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ParserTest extends BaseCase
{

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$this->file		= dirname( __FILE__ )."/parser.xml";
		$this->serial	= dirname( __FILE__ )."/parser.serial";

		$xml		= file_get_contents( $this->file );

		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= Parser::parse( $xml );

#		file_put_contents( $this->serial, serialize( $creation ) );
		$this->assertEquals( $assertion, $creation );
	}
}
