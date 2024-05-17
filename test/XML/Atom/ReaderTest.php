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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}
}
