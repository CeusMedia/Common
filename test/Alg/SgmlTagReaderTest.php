<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg_SgmlTagReader.
 *	@package		Tests.Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg;

use CeusMedia\Common\Alg\SgmlTagReader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg_SgmlTagReader.
 *	@package		Tests.Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class SgmlTagReaderTest extends BaseCase
{
	public $tag1	= '<a href="http://google.com/" target="_blank" class=\'test-class other\'>Google</a>';
	public $tag2	= '<body font-color="#FF0000" onFocus="this.blur()">';

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$reader	= new SgmlTagReader;
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
	 *	Tests Method 'getNodeName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNodeName()
	{
		$assertion	= "a";
		$creation	= SgmlTagReader::getNodeName( $this->tag1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "body";
		$creation	= SgmlTagReader::getNodeName( $this->tag2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAttributes'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAttributes()
	{
		$assertion	= array(
			'href'		=> "http://google.com/",
			'target'	=> "_blank",
			'class'		=> "test-class other"
		);
		$creation	= SgmlTagReader::getAttributes( $this->tag1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'font-color'	=> "#FF0000",
			'onFocus'		=> "this.blur()",
		);
		$creation	= SgmlTagReader::getAttributes( $this->tag2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetContent()
	{
		$assertion	= "Google";
		$creation	= SgmlTagReader::getContent( $this->tag1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= SgmlTagReader::getContent( $this->tag2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTagData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTagData()
	{
		$assertion	= array(
			'nodename'		=> "a",
			'content'		=> "Google",
			'attributes'	=> array(
				'href'		=> "http://google.com/",
				'target'	=> "_blank",
				'class'		=> "test-class other"
			)
		);
		$creation	= SgmlTagReader::getTagData( $this->tag1 );
		$this->assertEquals( $assertion, $creation );
	}
}
