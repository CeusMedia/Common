<?php
/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.05.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@extends		Test_Case
 *	@uses			XML_Atom_Validator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.05.2008
 *	@version		0.1
 */
class Test_XML_Atom_ValidatorTest extends Test_Case
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
	 *	Tests Method 'getErrors'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetErrors()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= XML_Atom_Validator::getErrors();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFirstError'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFirstError()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= XML_Atom_Validator::getFirstError();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
