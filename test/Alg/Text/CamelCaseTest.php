<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg\Text\CamelCase.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Text;

use CeusMedia\Common\Alg\Text\CamelCase;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg\Text\CamelCase.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CamelCaseTest extends BaseCase
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

		$assertion	= "testAlphaTestRdfString";
		$creation	= CamelCase::encode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRdfString";
		$creation	= CamelCase::encode( $string1, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= CamelCase::encode( $string1, FALSE  );
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

		$assertion	= "testAlphaTestRdfString";
		$creation	= CamelCase::encode( $string2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRdfString";
		$creation	= CamelCase::encode( $string2, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= CamelCase::encode( $string2, FALSE  );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecode()
	{
		$string1	= "testAlphaTestRdfString";

		$assertion	= "test alpha test rdf string";
		$creation	= CamelCase::decode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= CamelCase::decode( $string1, '_' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toPascalCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToPascalCase()
	{
		$string1	= "testAlphaTestRdfString";

		$assertion	= "TestAlphaTestRdfString";
		$creation	= CamelCase::toPascalCase( $string1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toSnakeCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToSnakeCase()
	{
		$string1	= "testAlphaTestRdfString";

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= CamelCase::toSnakeCase( $string1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'validate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidate()
	{
		$string1	= "testAlphaTestRdfString";
		$string2	= "testAlphaTestRDFString";
		$string3	= "TestAlphaTestRdfString";
		$string4	= "TestAlphaTestRdf String";

		$creation	= CamelCase::validate( $string1 );
		$this->assertEquals( TRUE, $creation );

		$creation	= CamelCase::validate( $string2 );
		$this->assertEquals( TRUE, $creation );

		$creation	= CamelCase::validate( $string3 );
		$this->assertEquals( FALSE, $creation );

		$creation	= CamelCase::validate( $string4 );
		$this->assertEquals( FALSE, $creation );
	}
}
