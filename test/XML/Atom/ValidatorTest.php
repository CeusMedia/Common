<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\Atom;

use CeusMedia\CommonTest\BaseCase;
use CeusMedia\Common\XML\Atom\Validator;

/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ValidatorTest extends BaseCase
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
		$creation	= Validator::getErrors();
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
		$creation	= Validator::getFirstError();
		$this->assertEquals( $assertion, $creation );
	}
}
