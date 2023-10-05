<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML_Atom_Reader.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\Atom;

use CeusMedia\CommonTest\BaseCase;
use CeusMedia\Common\XML\Atom\Reader;

/**
 *	TestUnit of XML_Atom_Reader.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
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
		$creation	= Reader::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'readXml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadXml()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::readXml();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::readUrl();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'readFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadFile()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::readFile();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelAuthors'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelAuthors()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelAuthors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelCategories'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelCategories()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelCategories();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelContributors'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelContributors()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelContributors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelGenerator'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelGenerator()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelGenerator();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelIcon'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelIcon()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelIcon();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelId'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelId()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelId();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelLinks'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelLinks()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelLinks();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelLogo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelLogo()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelLogo();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelRights'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelRights()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelRights();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelSubtitle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelSubtitle()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelSubtitle();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelTitle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelTitle()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelTitle();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelUpdated'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelUpdated()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelUpdated();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelData()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getChannelData();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntries'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntries()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntries();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntry'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntry()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntry();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryAuthors'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryAuthors()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryAuthors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryCategories'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryCategories()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryCategories();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryContent()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryContent();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryContributors'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryContributors()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryContributors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryId'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryId()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryId();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryLinks'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryLinks()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryLinks();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryPublished'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryPublished()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryPublished();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryRights'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryRights()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryRights();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntrySource'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntrySource()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntrySource();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntrySummary'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntrySummary()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntrySummary();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryTitle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryTitle()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryTitle();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryUpdated'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryUpdated()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getEntryUpdated();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getLanguage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLanguage()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getLanguage();
		$this->assertEquals( $assertion, $creation );
	}
}
