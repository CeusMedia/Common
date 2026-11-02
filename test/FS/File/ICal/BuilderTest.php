<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_ICal_Builder.
 *	@package		Tests.FS.File,ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\ICal;

use CeusMedia\Common\FS\File\ICal\Builder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_ICal_Builder.
 *	@package		Tests.FS.File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BuilderTest extends BaseCase
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
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Builder::__construct();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Builder::build();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests that folding of long lines keeps the whole content.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildLineFoldingKeepsContent(): void
	{
		$content	= str_repeat( 'abcdefghij', 20 );
		new Builder( "\r\n" );
		$method		= new \ReflectionMethod( Builder::class, 'buildLine' );
		$line		= $method->invoke( NULL, 'description', [], $content );
		self::assertSame( 'DESCRIPTION:'.$content, str_replace( "\r\n ", '', $line ) );
	}

	/**
	 *	Tests that folded lines have at most 75 octets, including parameters and the first line.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildLineFoldingLength(): void
	{
		$content	= str_repeat( 'abcdefghij', 20 );
		$param		= ['tzid' => str_repeat( 'x', 90 ), 'value' => 'DATE'];
		new Builder( "\r\n" );
		$method		= new \ReflectionMethod( Builder::class, 'buildLine' );
		$line		= $method->invoke( NULL, 'dtstart', $param, $content );
		foreach( explode( "\r\n", $line ) as $nr => $part ){
			self::assertLessThanOrEqual( 75, strlen( $part ) );
			if( $nr > 0 )
				self::assertSame( ' ', $part[0] );
		}
		$expected	= 'DTSTART;TZID='.str_repeat( 'x', 90 ).',VALUE=DATE:'.$content;
		self::assertSame( $expected, str_replace( "\r\n ", '', $line ) );
	}

	/**
	 *	Tests that folding does not split UTF-8 multibyte characters.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildLineFoldingKeepsMultibyteCharacters(): void
	{
		$content	= str_repeat( 'äöü€', 30 );
		new Builder( "\r\n" );
		$method		= new \ReflectionMethod( Builder::class, 'buildLine' );
		$line		= $method->invoke( NULL, 'summary', [], $content );
		foreach( explode( "\r\n", $line ) as $part ){
			self::assertLessThanOrEqual( 75, strlen( $part ) );
			self::assertTrue( mb_check_encoding( $part, 'UTF-8' ) );
		}
		self::assertSame( 'SUMMARY:'.$content, str_replace( "\r\n ", '', $line ) );
	}

	/**
	 *	Tests that short lines are not folded.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildLineShort(): void
	{
		new Builder( "\r\n" );
		$method		= new \ReflectionMethod( Builder::class, 'buildLine' );
		self::assertSame( 'SUMMARY:Test', $method->invoke( NULL, 'summary', [], 'Test' ) );
		self::assertSame( 'DTSTART;VALUE=DATE:20261102', $method->invoke( NULL, 'dtstart', ['value' => 'DATE'], '20261102' ) );
	}
}
