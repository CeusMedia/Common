<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Parcel;

use OutOfRangeException;

/**
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Packer
{
	/**	@var		Factory		$factory			Packet Factory */
	protected Factory $factory;

	/**	@var		array		$articles			Array if possible Articles */
	protected array$articles	= [];

	/**	@var		array		$packets			Array of Packet Types and their Prices */
	protected array $packets	= [];

	/**	@var		array		$packetList			Array of Packets need to pack Articles */
	protected array $packetList	= [];

	/**	@var		array		$volumes		Array of Packets and the Volumes the Articles would need */
	protected array $volumes;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$packets			Packet Definitions
	 *	@param		array		$articles			Article Definitions
	 *	@param		array		$volumes			Volumes of all Articles in all Packages
	 *	@return		void
	 */
	public function __construct( array $packets = [], array $articles = [], array $volumes = [] )
	{
		asort( $packets );
		$this->packets		= $packets;
		$this->articles		= $articles;
		$this->volumes		= $volumes;
		$this->factory		= new Factory( array_keys( $packets ), $articles, $volumes );
	}

	/**
	 *	Calculates Packages for Articles and returns Packet List.
	 *	@access		public
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@return		array
	 */
	public function calculatePackage( array $articleList ): array
	{
		//  reset Packet List
		$this->packetList	= [];

		//  iterate Article List
		foreach( $articleList as $name => $quantity )
			//  and remove all Articles
			if( !$quantity )
				//  without Quantity
				unset( $articleList[$name] );
		//  iterate Article List
		while( $articleList ){
			//  --  ADD FIRST PACKET  --  //
			//  get Largest Article in List
			$largestArticle	= $this->getLargestArticle( $articleList );
			//  no Packets yet in Packet List
			if( !count( $this->packetList ) ){
				//  get smallest Packet Type for Article
				$packetName	= $this->getNameOfSmallestPacketForArticle( $largestArticle );
				//  put Article in new Packet
				$packet		= $this->factory->produce( $packetName, [$largestArticle => 1] );
				//  add Packet to Packet List
				$this->packetList[]	= $packet;
				//  remove Article from Article List
				$this->removeArticleFromList( $articleList, $largestArticle );
				//  step to next Article
				continue;
			}

			//  --  FILL PACKET  --  //
			$found = false;																				//
			//  iterate Packets in Packet List
			for( $i=0; $i<count( $this->packetList ); $i++ ){
				//  get current Packet
				$packet	= $this->getPacket( $i );
				//  get Article Volume in this Packet
				$articleVolume	= $this->volumes[$packet->getName()][$largestArticle];
				//  check if Article will fit in Packet
				if( $packet->hasVolumeLeft( $articleVolume ) )
				{
					//  put Article in Packet
					$packet->addArticle( $largestArticle, $articleVolume );
					//  remove Article From Article List
					$found	= $this->removeArticleFromList( $articleList, $largestArticle );
					//  break Packet Loop
					break;
				}
			}
			//  Article has been put into a Packet
			if( $found )
				//  step to next Article
				continue;

			//  --  RESIZE PACKET  --  //
			//  iterate Packets in Packet List
			for( $i=0; $i<count( $this->packetList ); $i++ ){
				//  get current Packet
				$packet	= $this->getPacket( $i );
				//  there is a larger Packet Type
				while( $this->hasLargerPacket( $packet->getName() ) ){
					$largerPacketName	= $this->getNameOfLargerPacket( $packet->getName() );
					//  get larger Packet
					$articles			= $packet->getArticles();
					//  produce new Packet and add Articles from old Packet
					$largerPacket		= $this->factory->produce( $largerPacketName, $articles );
					//  get Volume of current Article in this Packet
					$articleVolume		= $this->volumes[$largerPacketName][$largestArticle];
					if( $largerPacket->hasVolumeLeft( $articleVolume ) ){
						//  add Article to Packet
						$largerPacket->addArticle( $largestArticle, $articleVolume );
						//  replace old Packet with new Packet
						$this->replacePacket( $i, $largerPacket );
						//  remove Article from Article List
						$found	= $this->removeArticleFromList( $articleList, $largestArticle );
						//  break Packet Loop
						break;
					}
				}
				//  Article has been put into a Packet
				if( $found )
					//  break Packet Loop
					continue;
			}
			//  Article has been put into a Packet
			if( $found )
				//  step to next Article
				continue;

			//  --  ADD NEW PACKET  --  //
			//  get smallest Packet Type for Article
			$packetName	= $this->getNameOfSmallestPacketForArticle( $largestArticle );
			//  produce new Packet and put Article in
			$packet		= $this->factory->produce( $packetName, [$largestArticle => 1] );
				//  add Packet to Packet List
				$this->packetList[]	= $packet;
			//  remove Article from Article List
			$this->removeArticleFromList( $articleList, $largestArticle );
		}
		//  return final Packet List with Articles
		return $this->packetList;
	}

	/**
	 *	Calculates Price of Packets for Articles and returns total Price.
	 *	@access		public
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@return		float
	 */
	public function calculatePrice( array $articleList ): float
	{
		$packetList	= $this->calculatePackage( $articleList );
		return $this->calculatePriceFromPackage( $packetList );
	}

	/**
	 *	Calculates Price of Packets for Articles and returns total Price.
	 *	@access		public
	 *	@param		array		$packetList		Array of Articles and their Quantities.
	 *	@return		float
	 */
	public function calculatePriceFromPackage( array $packetList ): float
	{
		$price	= 0;
		foreach( $packetList as $packet )
			$price	+= $this->packets[$packet->getName()];
		return $price;
	}

	/**
	 *	Returns the largest Article from an Article List by Article Volume.
	 *	@access		protected
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@return		string|NULL
	 */
	protected function getLargestArticle( array $articleList ): ?string
	{
		$largestPacketName	= $this->getNameOfLargestPacket();
		$articleVolumes		= $this->volumes[$largestPacketName];
		asort( $articleVolumes );
		$articleKeys	= array_keys( $articleVolumes );
		do{
			$articleName	= array_pop( $articleKeys );
			if( array_key_exists( $articleName, $articleList ) )
				return $articleName;
		}
		while( $articleKeys );
		return NULL;
	}

	/**
	 *	Returns Name of next larger Packet.
	 *	@access		protected
	 *	@param		string		$packetName			Packet Name to get next larger Packet for
	 *	@return		string|NULL
	 */
	protected function getNameOfLargerPacket( string $packetName ): ?string
	{
		$keys	= array_keys( $this->packets );
		$index	= array_search( $packetName, $keys );
		$sliced	= array_slice( $keys, $index + 1, 1 );
		return array_pop( $sliced );
	}

	/**
	 *	Returns Name of the largest Packet from Packet Definition.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getNameOfLargestPacket(): string
	{
		$packets	= $this->packets;
		asort( $packets );
		return key( array_slice( $packets, -1 ) );
	}

	/**
	 *	Returns Name of the smallest Packet for an Article.
	 *	@access		protected
	 *	@param		string		$articleName		Name of Article to get the smallest Article for
	 *	@return		string|NULL
	 */
	protected function getNameOfSmallestPacketForArticle( string $articleName ): ?string
	{
		foreach( array_keys( $this->packets ) as $packetName )
			if( $this->volumes[$packetName][$articleName] <= 1 )
				return $packetName;
		return NULL;
	}

	public function getPacket( int $index ): Packet
	{
		if( !isset( $this->packetList[$index] ) )
			throw new OutOfRangeException( 'Invalid Packet Index.' );
		return $this->packetList[$index];
	}

	/**
	 *	Indicates whether a larger Packet would be available.
	 *	@access		protected
	 *	@param		string		$packetName			Name of Packet to find a larger Packet for
	 *	@return		bool
	 */
	protected function hasLargerPacket( string $packetName ): bool
	{
		$last	= key( array_slice( $this->packets, -1 ) );
		return $packetName !== $last;
	}

	/**
	 *	Removes an Article from an Article List (by Reference).
	 *	@access		protected
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@param		string		$articleName		Name of Article to remove from Article List
	 *	@return		bool
	 */
	protected function removeArticleFromList( array &$articleList, string $articleName ): bool
	{
		if( $articleList[$articleName] > 0 ){
			if( $articleList[$articleName] == 1 )
				unset( $articleList[$articleName] );
			else
				$articleList[$articleName]--;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Replaces a Packet from current Packet List with another Packet.
	 *	@access		public
	 *	@param		int					$index		Index of Packet to replace
	 *	@param		Packet	$packet		Packet to set for another Packet
	 *	@return		self
	 */
	public function replacePacket( int $index, Packet $packet ): self
	{
		if( !isset( $this->packetList[$index] ) )
			throw new OutOfRangeException( 'Invalid Packet Index.' );
		$this->packetList[$index]	= $packet;
		return $this;
	}
}
