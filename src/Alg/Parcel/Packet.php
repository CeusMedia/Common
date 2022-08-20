<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Packet can contain different Articles and has a defined Volume.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Parcel;

use OutOfRangeException;

/**
 *	Packet can contain different Articles and has a defined Volume.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Packet
{
	/**	@var		string		$name		Name of Packet Size */
	protected $name;

	/**	@var		array		$articles	Array of Articles and their Quantities */
	protected $articles			= [];

	/**	@var		float		$volume		Filled Volume as floating Number between 0 and 1 */
	protected $volume			= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Packet Name, must be a defined Packet Size
	 *	@return		void
	 */
	public function __construct( string $name )
	{
		$this->name		= $name;
	}

	/**
	 *	Returns Packet as String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString(): string
	{
		$list	= [];
		foreach( $this->articles as $name => $quantity )
			$list[]	= $name.":".$quantity;
		$articles	= implode( ", ", $list );
		$volume		= round( $this->volume * 100 );
		return "[".$this->name."] {".$articles."} (".$volume."%)";
	}

	/**
	 *	Adds an Article to Packet.
	 *	@access		public
	 *	@param		string		$name		Article Name
	 *	@param		float		$volume		Article Volume for this Packet Size
	 *	@return		void
	 */
	public function addArticle( string $name, float $volume )
	{
		if( !$this->hasVolumeLeft( $volume ) )
			throw new OutOfRangeException( 'Article "'.$name.'" does not fit in this Packet "'.$this->name.'".' );
		if( !isset( $this->articles[$name] ) )
			$this->articles[$name]	= 0;
		$this->articles[$name]++;
		$this->volume	+= $volume;
	}

	/**
	 *	Returns Packet Articles.
	 *	@access		public
	 *	@return		array
	 */
	public function getArticles(): array
	{
		return $this->articles;
	}

	/**
	 *	Returns Packet Name.
	 *	@access		public
	 *	@return		string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 *	Returns Packet Volume.
	 *	@access		public
	 *	@return		float
	 */
	public function getVolume(): float
	{
		return $this->volume;
	}

	/**
	 *	Checks whether an Article Volume is left in Packet.
	 *	@access		public
	 *	@param		float		$volume		Article Volume for this Packet Size.
	 *	@return		bool
	 */
	public function hasVolumeLeft( float $volume ): bool
	{
		$newVolume	= $this->volume + $volume;
		return  $newVolume <= 1;
	}
}
