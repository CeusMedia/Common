<?php
/**
 *	Packet can contain different Articles and has a defined Volume.
 *	@package		alg.parcel
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.5
 */
/**
 *	Packet can contain different Articles and has a defined Volume.
 *	@package		alg.parcel
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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