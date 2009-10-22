<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@uses			Alg_Parcel_Packet
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.05.2008
 *	@version		0.5
 */
import( 'de.ceus-media.alg.parcel.Packet' );
/**
 *	...
 *	@category		cmClasses
 *	@package		alg.parcel
 *	@uses			Alg_Parcel_Packet
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.05.2008
 *	@version		0.5
 *	@todo			Code Doc
 */
class Alg_Parcel_Factory
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
	public function __construct( $packets, $articles, $volumes )
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
	 *	@return		Alg_Parcel_Packet
	 */
	public function produce( $packetName, $articles )
	{
		if( !in_array( $packetName, $this->packets ) )
			throw new InvalidArgumentException( 'Packet "'.$packetName.'" is not a valid Packet.' );
		try
		{
			$packet	= new Alg_Parcel_Packet( $packetName );
			foreach( $articles as $articleName => $articleQuantity )
			{
				if( !in_array( $articleName, $this->articles ) )
					throw new InvalidArgumentException( 'Article "'.$articleName.'" is not a valid Article.' );
				for( $i=0; $i<$articleQuantity; $i++ )
				{
					$volume	= $this->volumes[$packetName][$articleName];
					$packet->addArticle( $articleName, $volume );
				}
			}
			return $packet;
		}
		catch( OutOfRangeException $e )
		{
			throw new OutOfRangeException( 'To much Articles for Packet.' ); 
		}
	}
}
?>