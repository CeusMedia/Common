<?php
/**
 *
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		alg.parcel
 *	@uses			Alg_Parcel_Factory
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.05.2008
 *	@version		$Id$
 */
import( 'de.ceus-media.alg.parcel.Factory' );
/**
 *
 *	@category		cmClasses
 *	@package		alg.parcel
 *	@uses			Alg_Parcel_Factory
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.05.2008
 *	@version		$Id$
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

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$packets			Packet Definitions
	 *	@param		array		$articles			Article Definitions
	 *	@param		array		$volumes			Volumes of all Articles in all Packages
	 *	@return		void
	 */
	public function __construct( $packets, $articles, $volumes )
	{
		asort( $packets );
		$this->packets		= $packets;
		$this->articles		= $articles;
		$this->volumes		= $volumes;
		$this->factory		= new Alg_Parcel_Factory( array_keys( $packets ), $articles, $volumes );
	}

	/**
	 *	Calculates Packages for Articles and returns Packet List.
	 *	@access		public
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@return		array
	 */
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
				$articleVolume	= $this->volumes[$packet->getName()][$largestArticle];						//  get Article Volume in this Packet
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
				while( $this->hasLargerPacket( $packet->getName() ) )									//  there is a larger Packet Type
				{
					$largerPacketName	= $this->getNameOfLargerPacket( $packet->getName() );	
					$articles			= $packet->getArticles();										//  get larger Packet
					$largerPacket		= $this->factory->produce( $largerPacketName, $articles );		//  produce new Packet and add Articles from old Packet
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

	/**
	 *	Calculates Price of Packets for Articles and returns total Price.
	 *	@access		public
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@return		float
	 */
	public function calculatePrice( $articleList )
	{
		$packetList	= $this->calculatePackage( $articleList );
		$price		= $this->calculatePriceFromPackage( $packetList );
		return $price;
	}

	/**
	 *	Calculates Price of Packets for Articles and returns total Price.
	 *	@access		public
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@return		float
	 */
	public function calculatePriceFromPackage( $packetList )
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
	 *	@return		string
	 */
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

	/**
	 *	Returns Name of next larger Packet.
	 *	@access		protected
	 *	@param		string		$packetName			Packet Name to get next larger Packet for
	 *	@return		string
	 */
	protected function getNameOfLargerPacket( $packetName )
	{
		$keys	= array_keys( $this->packets );
		$index	= array_search( $packetName, $keys );
		$next	= array_pop( array_slice( $keys, $index + 1, 1 ) );
		return $next;
	}

	/**
	 *	Returns Name of largest Packet from Packet Definition.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getNameOfLargestPacket()
	{
		$packets	= $this->packets;
		asort( $packets );
		$packetName	= key( array_slice( $packets, -1 ) );
		return $packetName;
	}

	/**
	 *	Returns Name of smallest Packet for an Article.
	 *	@access		protected 
	 *	@param		string		$articleName		Name of Article to get smallest Article for
	 *	@return		string
	 */
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

	/**
	 *	Indicates whether a larger Packet would be available.
	 *	@access		protected
	 *	@param		string		$packetName			Name of Packet to find a larger Packet for
	 *	@param		bool
	 */
	protected function hasLargerPacket( $packetName )
	{
		$last	= key( array_slice( $this->packets, -1 ) );
		return $packetName != $last;
	}

	/**
	 *	Removes an Article from an Article List (by Reference).
	 *	@access		protected
	 *	@param		array		$articleList		Array of Articles and their Quantities.
	 *	@param		string		$articleName		Name of Article to remove from Article List
	 *	@return		bool
	 */
	protected function removeArticleFromList( &$articleList, $articleName )
	{
		if( $articleList[$articleName] > 0 )
		{
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
	 *	@param		Alg_Parcel_packet	$packet		Packet to set for another Packet
	 *	@return		void
	 */
	public function replacePacket( $index, $packet )
	{
		if( !isset( $this->packetList[$index] ) )
			throw new OutOfRangeException( 'Invalid Packet Index.' );
		$this->packetList[$index]	= $packet;
	}
}
?>