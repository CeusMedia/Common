<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\RSS;

use CeusMedia\Common\XML\RSS\Parser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ParserTest extends BaseCase
{
	protected $file;

	protected $serial;

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$this->file		= dirname( __FILE__ )."/assets/parser.xml";
		$this->serial	= dirname( __FILE__ )."/assets/parser.serial";

		$xml		= file_get_contents( $this->file );

		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= Parser::parse( $xml );

#		file_put_contents( $this->serial, serialize( $creation ) );
		self::assertEquals( $assertion, $creation );
	}
}
