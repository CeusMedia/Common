<?php
/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.05.2008
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.05.2008
 *
 */
class Test_XML_Atom_ValidatorTest extends BaseCase
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
