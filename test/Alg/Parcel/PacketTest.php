<?php
/**
 *	TestUnit of Alg_Parcel_Packet.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.07.2008
 */
declare( strict_types = 1 );

use CeusMedia\Common\Alg\Parcel\Packet as ParcelPacket;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Alg_Parcel_Packet.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.07.2008
 */
class PacketTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->packet	= new ParcelPacket( 'testPacket' );
		$this->packet->addArticle( 'testArticle1', 0.2 );
		$this->packet->addArticle( 'testArticle2', 0.3 );
		$this->packet->addArticle( 'testArticle3', 0.4 );
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
		$packetName	= "testPacketName";
		$packet		= new ParcelPacket( $packetName );

		$assertion	= $packetName;
		$creation	= $packet->getName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "[testPacket] {testArticle1:1, testArticle2:1, testArticle3:1} (90%)";
		$creation	= (string) $this->packet;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addArticle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddArticle()
	{
		$packet		= new ParcelPacket( 'testPacket' );

		$assertion	= 0;
		$creation	= count( $packet->getArticles() );
		$this->assertEquals( $assertion, $creation );

		$packet->addArticle( 'testArticle1', 0.1 );

		$assertion	= 1;
		$creation	= count( $packet->getArticles() );
		$this->assertEquals( $assertion, $creation );

		$packet->addArticle( 'testArticle1', 0.1 );

		$assertion	= 1;
		$creation	= count( $packet->getArticles() );
		$this->assertEquals( $assertion, $creation );

		$packet->addArticle( 'testArticle2', 0.2 );

		$assertion	= 2;
		$creation	= count( $packet->getArticles() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getArticles'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetArticles()
	{
		$articles	= $this->packet->getArticles();

		$assertion	= 3;
		$creation	= count( $articles );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'testArticle1'	=> 1,
			'testArticle2'	=> 1,
			'testArticle3'	=> 1,
		);
		$creation	= $articles;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetName()
	{
		$assertion	= "testPacket";
		$creation	= $this->packet->getName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getVolume'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetVolume()
	{
		$assertion	= 0.9;
		$creation	= $this->packet->getVolume();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasVolumeLeft'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasVolumeLeft()
	{
		$assertion	= TRUE;
		$creation	= $this->packet->hasVolumeLeft( 0.05 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->packet->hasVolumeLeft( 0.1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->packet->hasVolumeLeft( 0.2 );
		$this->assertEquals( $assertion, $creation );
	}
}
