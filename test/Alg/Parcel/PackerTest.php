<?php
/**
 *	TestUnit of Alg_Parcel_Packer.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.07.2008
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Alg_Parcel_Packer.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.07.2008
 */
class Test_Alg_Parcel_PackerTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->articles	= array(
			'a',
			'b',
			'c'
		);
		$this->packets	= array(
			'S'	=> 2,
			'M'	=> 5,
			'L'	=> 8
		);
		$this->volumes	= array(
			'S'	=> array(
				'a'	=> 0.4,
				'b'	=> 0.8,
				'c'	=> 2
			),
			'M'	=> array(
				'a'	=> 0.2,
				'b'	=> 0.4,
				'c'	=> 1.0
			),
			'L'	=> array(
				'a'	=> 0.1,
				'b'	=> 0.2,
				'c'	=> 0.5
			)
		);
		$this->packer	= new Alg_Parcel_Packer( $this->packets, $this->articles, $this->volumes );
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
	public function testConstruct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'calculatePackage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculatePackage()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::calculatePackage();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'calculatePrice'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculatePrice()
	{
		$articles	= array(
			'a'	=> 1,
			'b'	=> 1,
			'c'	=> 1,
		);
		$assertion	= 8;
		$creation	= $this->packer->calculatePrice( $articles );
		$this->assertEquals( $assertion, $creation );

		$articles	= array(
			'a'	=> 2,
			'b'	=> 2,
			'c'	=> 1,
		);
		$assertion	= 10;
		$creation	= $this->packer->calculatePrice( $articles );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPacket()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::getPacket();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getPacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPacketException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'OutOfRangeException' );
		Alg_Parcel_Packer::getPacket();
	}

	/**
	 *	Tests Exception of Method 'replacePacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReplacePacketException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'OutOfRangeException' );
		Alg_Parcel_Packer::replacePacket();
	}

	/**
	 *	Tests Method 'replacePacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReplacePacket()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::replacePacket();
		$this->assertEquals( $assertion, $creation );
	}
}
