<?php
/**
 *	TestUnit of XML_Atom_Reader.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_Atom_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.atom.Reader' );
/**
 *	TestUnit of XML_Atom_Reader.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_Atom_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.05.2008
 *	@version		0.1
 */
class Tests_XML_Atom_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
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
		$creation	= XML_Atom_Reader::__construct();
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
		$creation	= XML_Atom_Reader::readXml();
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
		$creation	= XML_Atom_Reader::readUrl();
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
		$creation	= XML_Atom_Reader::readFile();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'checkEntryIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckEntryIndex()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= XML_Atom_Reader::checkEntryIndex();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'checkEntryIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckEntryIndexException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		XML_Atom_Reader::checkEntryIndex();
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
		$creation	= XML_Atom_Reader::getChannelAuthors();
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
		$creation	= XML_Atom_Reader::getChannelCategories();
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
		$creation	= XML_Atom_Reader::getChannelContributors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChannelElementAndAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChannelElementAndAttribute()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= XML_Atom_Reader::getChannelElementAndAttribute();
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
		$creation	= XML_Atom_Reader::getChannelGenerator();
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
		$creation	= XML_Atom_Reader::getChannelIcon();
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
		$creation	= XML_Atom_Reader::getChannelId();
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
		$creation	= XML_Atom_Reader::getChannelLinks();
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
		$creation	= XML_Atom_Reader::getChannelLogo();
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
		$creation	= XML_Atom_Reader::getChannelRights();
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
		$creation	= XML_Atom_Reader::getChannelSubtitle();
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
		$creation	= XML_Atom_Reader::getChannelTitle();
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
		$creation	= XML_Atom_Reader::getChannelUpdated();
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
		$creation	= XML_Atom_Reader::getChannelData();
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
		$creation	= XML_Atom_Reader::getEntries();
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
		$creation	= XML_Atom_Reader::getEntry();
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
		$creation	= XML_Atom_Reader::getEntryAuthors();
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
		$creation	= XML_Atom_Reader::getEntryCategories();
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
		$creation	= XML_Atom_Reader::getEntryContent();
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
		$creation	= XML_Atom_Reader::getEntryContributors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEntryElementAndAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEntryElementAndAttribute()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= XML_Atom_Reader::getEntryElementAndAttribute();
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
		$creation	= XML_Atom_Reader::getEntryId();
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
		$creation	= XML_Atom_Reader::getEntryLinks();
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
		$creation	= XML_Atom_Reader::getEntryPublished();
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
		$creation	= XML_Atom_Reader::getEntryRights();
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
		$creation	= XML_Atom_Reader::getEntrySource();
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
		$creation	= XML_Atom_Reader::getEntrySummary();
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
		$creation	= XML_Atom_Reader::getEntryTitle();
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
		$creation	= XML_Atom_Reader::getEntryUpdated();
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
		$creation	= XML_Atom_Reader::getLanguage();
		$this->assertEquals( $assertion, $creation );
	}
}
?>