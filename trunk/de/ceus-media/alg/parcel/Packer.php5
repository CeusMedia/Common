<?php
import( 'de.ceus-media.alg.parcel.Factory' );
/**
 *
 *	@package		alg.parcel
 *	@uses			Alg_Parcel_Factory
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.5
 */
/**
 *
 *	@package		alg.parcel
 *	@uses			Alg_Parcel_Factory
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.5
 */
class Alg_Parcel_Packer
{
	/**	@var		object		$factory			Packet Factory */
	protected $factory		= array();
	/**	@var		array		$articles			Array if possible Articles */
	protected $articles		= array();
	/**	@var		array		$packets			Array of Packet Types and their Prices */
	protected $packets		= array();
	/**	@var		array		$packetList			Array of Packets need to pack Articles */
	protected $packetList	= array();

	public function __construct( $packets, $articles, $volumes )
	{
		asort( $packets );
		$this->packets		= $packets;
		$this->articles		= $articles;
		$this->volumes		= $volumes;
		$this->factory		= new Alg_Parcel_Factory( $packets, $articles, $volumes );
	}

	public function calculatePackage( $articleList )
	{
		$this->packetList	= array();																	//  reset Packet List

		foreach( $articleList as $name => $quantity )													//  iterate Article List
			if( !$quantity )																			//  and remove all Articles
				unset( $articleList[$name] );															//  without Quantity
		while( $articleList )																			//  iterate Article List
		{
			//  --  ADD FIRST PACKET  --  //
			$largestArticle	= $this->getLargestArticle( $articleList );									//  get Largest Article in List
			if( !count( $this->packetList ) )															//  no Packets yet in Packet List
			{
				$packetName	= $this->getNameOfSmallestPacketForArticle( $largestArticle );				//  get smallest Packet Type for Article
				$packet		= $this->factory->produce( $packetName, array( $largestArticle => 1 ) );	//  put Article in new Packet
				$this->packetList[]	= $packet;															//  add Packet to Packet List
				$this->removeArticleFromList( $articleList, $largestArticle );							//  remove Article from Article List
				continue;																				//  step to next Article
			}

			//  --  FILL PACKET  --  //
			$found = false;																				//  
			for( $i=0; $i<count( $this->packetList ); $i++ )											//  iterate Packets in Packet List
			{
				$packet	= $this->getPacket( $i );														//  get current Packet
				$articleVolume	= $this->volumes[$packet->name][$largestArticle];						//  get Article Volume in this Packet
				if( $packet->hasVolumeLeft( $articleVolume ) )											//  check if Article will fit in Packet
				{
					$packet->addArticle( $largestArticle, $articleVolume );								//  put Article in Packet
					$found	= $this->removeArticleFromList( $articleList, $largestArticle );			//  remove Article From Article List
					break;																				//  break Packet Loop
				}
			}
			if( $found )																				//  Article has been put into a Packet
				continue;																				//  step to next Article

			//  --  RESIZE PACKET  --  //
			for( $i=0; $i<count( $this->packetList ); $i++ )											//  iterate Packets in Packet List
			{
				$packet	= $this->getPacket( $i );														//  get current Packet
				while( $this->hasLargerPacket( $packet->name ) )										//  there is a larger Packet Type
				{
					$largerPacketName	= $this->getNameOfLargerPacket( $packet->name );				//  get larger Packet
					$largerPacket		= $this->factory->produce( $largerPacketName, $packet->articles );	//  produce new Packet and add Articles from old Packet
					$articleVolume		= $this->volumes[$largerPacketName][$largestArticle];			//  get Volume of current Article in this Packet
					if( $largerPacket->hasVolumeLeft( $articleVolume ) )
					{
						$largerPacket->addArticle( $largestArticle, $articleVolume );					//  add Article to Packet
						$this->replacePacket( $i, $largerPacket );										//  replace old Packet with new Packet
						$found	= $this->removeArticleFromList( $articleList, $largestArticle );		//  remove Article from Article List
						break;																			//  break Packet Loop
					}
				}
				if( $found )																			//  Article has been put into a Packet
					continue;																			//  break Packet Loop
			}
			if( $found )																				//  Article has been put into a Packet
				continue;																				//  step to next Article
	
			//  --  ADD NEW PACKET  --  //
			$packetName	= $this->getNameOfSmallestPacketForArticle( $largestArticle );					//  get smallest Packet Type for Article
			$packet		= $this->factory->produce( $packetName, array( $largestArticle => 1 ) );		//  produce new Packet and put Article in
				$this->packetList[]	= $packet;															//  add Packet to Packet List
			$this->removeArticleFromList( $articleList, $largestArticle );								//  remove Article from Article List
		}
		return $this->packetList;																		//  return final Packet List with Articles
	}
	
	public public function calculatePrice( $articleList )
	{
		$price	= 0;
		$packetList	= $this->calculatePackage( $articleList );
		foreach( $packetList as $packet )
			$price	+= $this->packets[$packet->name];
		return $price;
	}

	protected function getLargestArticle( $articleList )
	{
		$largestPacketName	= $this->getNameOfLargestPacket();
		$articleVolumes		= $this->volumes[$largestPacketName];
		asort( $articleVolumes );
		$articleKeys	= array_keys( $articleVolumes );
		do
		{
			$articleName	= array_pop( $articleKeys );
			if( array_key_exists( $articleName, $articleList ) )
				return $articleName;
		}
		while( $articleKeys );
	}
	
	protected function getNameOfLargerPacket( $packetName )
	{
		$keys	= array_keys( $this->packets );
		$index	= array_search( $packetName, $keys );
		$next	= array_pop( array_slice( $keys, $index + 1, 1 ) );
		return $next;
	}
	
	protected function getNameOfLargestPacket()
	{
		$packets	= $this->packets;
		asort( $packets );
		$packetName	= key( array_slice( $packets, -1 ) );
		return $packetName;
	}
	
	protected function getNameOfSmallestPacketForArticle( $articleName )
	{
		foreach( array_keys( $this->packets ) as $packetName )
			if( $this->volumes[$packetName][$articleName] <= 1 )
				return $packetName;
	}

	public function getPacket( $index )
	{
		if( !isset( $this->packetList[$index] ) )
			throw new OutOfRangeException( 'Invalid Packet Index.' );
		return $this->packetList[$index];
	}
	
	protected function hasLargerPacket( $packetName )
	{
		$last	= key( array_slice( $this->packets, -1 ) );
		return $packetName != $last;
	}
		
	protected function removeArticleFromList( &$articleList, $articleName )
	{
		if( $articleList[$articleName] == 1 )
			unset( $articleList[$articleName] );
		else
			$articleList[$articleName]--;
		return TRUE;
	}
	
	public function replacePacket( $index, $packet )
	{
		if( !isset( $this->packetList[$index] ) )
			throw new OutOfRangeException( 'Invalid Packet Index.' );
		$this->packetList[$index]	= $packet;
	}
}
?>