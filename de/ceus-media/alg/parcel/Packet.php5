<?php
/**
 *	Packet can contain different Articles and has a defined Volume.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		alg.parcel
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.05.2008
 *	@version		0.5
 */
/**
 *	Packet can contain different Articles and has a defined Volume.
 *	@package		alg.parcel
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.05.2008
 *	@version		0.5
 */
class Alg_Parcel_Packet
{
	/**	@var		string		$name		Name of Packet Size */
	protected $name;
	/**	@var		array		$articles	Array of Articles and their Quantities */
	protected $articles;
	/**	@var		float		$volume		Filled Volume as floating Number between 0 and 1 */
	protected $volume;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Packet Name, must be a defined Packet Size
	 *	@return		void
	 */
	public function __construct( $name )
	{
		$this->name		= $name;
	}

	/**
	 *	Returns Packet as String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		$list	= array();
		foreach( $this->articles as $name => $quantity )
			$list[]	= $name.":".$quantity;
		$articles	= implode( ", ", $list );
		$volume		= round( $this->volume * 100, 0 );
		return "[".$this->name."] {".$articles."} (".$volume."%)";
	}
	
	/**
	 *	Adds an Article to Packet.
	 *	@access		public
	 *	@param		string		$name		Article Name
	 *	@param		string		$volume		Article Volume for this Packet Size
	 *	@return		void
	 */
	public function addArticle( $name, $volume )
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
	public function getArticles()
	{
		return $this->articles;
	}
	
	/**
	 *	Returns Packet Name.
	 *	@access		public
	 *	@return		string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 *	Returns Packet Volume.
	 *	@access		public
	 *	@return		float
	 */
	public function getVolume()
	{
		return $this->volume;
	}
	
	/**
	 *	Checks whether an Article Volume is left in Packet.
	 *	@access		public
	 *	@param		double		$volume		Article Volume for this Packet Size.
	 *	@return		bool
	 */
	public function hasVolumeLeft( $volume )
	{
		$newVolume	= $this->volume + $volume;
		return  $newVolume <= 1;
	}
}
?>