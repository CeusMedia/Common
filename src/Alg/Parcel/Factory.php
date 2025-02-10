<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	...
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Parcel;

use InvalidArgumentException;
use OutOfRangeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class Factory
{
	/**	@var		array		$articles		List of possible Articles */
	protected $articles;

	/**	@var		array		$packets		Array of possible Packet and their Prices */
	protected $packets;

	/**	@var		array		$volumes		Array of Packets and the Volumes the Articles would need */
	protected $volumes;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$packets		Array of possible Packet and their Prices
	 *	@param		array		$articles		List of possible Articles
	 *	@param		array		$volumes		Array of Packets and the Volumes the Articles would need
	 *	@return		void
	 */
	public function __construct( array $packets = [], array $articles = [], array $volumes = [] )
	{
		$this->packets	= $packets;
		$this->articles	= $articles;
		$this->volumes	= $volumes;
	}

	/**
	 *	Produces a new Packet, filled with given Articles and returns it.
	 *	@access		public
	 *	@param		string		$packetName		Name of Packet Size
	 *	@param		array		$articles		Articles to put into Packet
	 *	@return		Packet
	 */
	public function produce( string $packetName, array $articles ): Packet
	{
		if( !in_array( $packetName, $this->packets ) )
			throw new InvalidArgumentException( 'Packet "'.$packetName.'" is not a valid Packet.' );
		try{
			$packet	= new Packet( $packetName );
			foreach( $articles as $articleName => $articleQuantity ){
				if( !in_array( $articleName, $this->articles ) )
					throw new InvalidArgumentException( 'Article "'.$articleName.'" is not a valid Article.' );
				for( $i=0; $i<$articleQuantity; $i++ ){
					$volume	= $this->volumes[$packetName][$articleName];
					$packet->addArticle( $articleName, $volume );
				}
			}
			return $packet;
		}
		catch( OutOfRangeException $e ){
			throw new OutOfRangeException( 'Too much Articles for Packet.' );
		}
	}
}
