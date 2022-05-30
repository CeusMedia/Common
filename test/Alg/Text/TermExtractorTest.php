<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Alg\Text\TermExtractor.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg\Text;

use CeusMedia\Common\Alg\Text\TermExtractor;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Alg\Text\TermExtractor.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class TermExtractorTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->text		= file_get_contents( $this->path."TermExtractorText.txt" );
		$this->black	= $this->path."TermExtractorBlacklist.list";
		$this->terms1	= parse_ini_file( $this->path."TermExtractorTerms1.ini" );
		$this->terms2	= parse_ini_file( $this->path."TermExtractorTerms2.ini" );

		foreach( $this->terms1 as $key => $value )
			$this->terms1[$key] = (int) $value;
		foreach( $this->terms2 as $key => $value )
			$this->terms2[$key] = (int) $value;
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
	 *	Tests Method 'getTerms'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTerms1()
	{
		$text	= "aa bb bb cc cc cc";
		$assertion	= array(
			"aa"	=> 1,
			"bb"	=> 2,
			"cc"	=> 3
		);
		$creation	= TermExtractor::getTerms( $text );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTerms'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTerms2()
	{
		$assertion	= $this->terms1;
		$creation	= TermExtractor::getTerms( $this->text );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTerms'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTerms3()
	{
		TermExtractor::loadBlackList( $this->black );
		$assertion	= $this->terms2;
		$creation	= TermExtractor::getTerms( $this->text );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'loadBlacklist'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadBlacklist()
	{
		$assertion	= explode( "\n", file_get_contents( $this->black ) );
		TermExtractor::loadBlacklist( $this->black );
		$creation	= TermExtractor::$blacklist;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setBlacklist'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetBlacklist()
	{
		$list		= array( "a", "b", "b" );

		$assertion	= array_unique( $list );
		TermExtractor::setBlacklist( $list );
		$creation	= TermExtractor::$blacklist;
		$this->assertEquals( $assertion, $creation );
	}
}
