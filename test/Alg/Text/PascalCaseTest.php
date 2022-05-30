<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Alg\Text\PascalCase.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg\Text;

use CeusMedia\Common\Alg\Text\PascalCase;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Alg\Text\PascalCase.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PascalCaseTest extends BaseCase
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
	 *	Tests Method 'encode' using String 1.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeWithString1()
	{
		$string1	= "test_alpha__test___RDF string";

		$assertion	= "TestAlphaTestRdfString";
		$creation	= PascalCase::encode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRdfString";
		$creation	= PascalCase::encode( $string1, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= PascalCase::encode( $string1, FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'encode' using String 2.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeWithString2()
	{
		$string2	= "Test_alpha__test___RDF string";

		$assertion	= "TestAlphaTestRdfString";
		$creation	= PascalCase::encode( $string2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRdfString";
		$creation	= PascalCase::encode( $string2, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= PascalCase::encode( $string2, FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecode()
	{
		$string1	= "TestAlphaTestRdfString";

		$assertion	= "test alpha test rdf string";
		$creation	= PascalCase::decode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= PascalCase::decode( $string1, '_' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toCamelCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToCamelCase()
	{
		$string1	= "TestAlphaTestRdfString";

		$assertion	= "testAlphaTestRdfString";
		$creation	= PascalCase::toCamelCase( $string1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toSnakeCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToSnakeCase()
	{
		$string1	= "TestAlphaTestRdfString";

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= PascalCase::decode( $string1, '_' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'validate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidate()
	{
		$string1	= "TestAlphaTestRdfString";
		$string2	= "TestAlphaTestRDFString";
		$string3	= "testAlphaTestRdfString";
		$string4	= "testAlphaTestRdf String";

		$creation	= PascalCase::validate( $string1 );
		$this->assertEquals( TRUE, $creation );

		$creation	= PascalCase::validate( $string2 );
		$this->assertEquals( TRUE, $creation );

		$creation	= PascalCase::validate( $string3 );
		$this->assertEquals( FALSE, $creation );

		$creation	= PascalCase::validate( $string4 );
		$this->assertEquals( FALSE, $creation );
	}
}
