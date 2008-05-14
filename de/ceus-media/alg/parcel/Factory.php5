<?php
import( 'de.ceus-media.alg.parcel.Packet' );
/**
 *
 *	@package		alg.parcel
 *	@uses			Alg_Parcel_Packet
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.5
 */
/**
 *
 *	@package		alg.parcel
 *	@uses			Alg_Parcel_Packet
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.5
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
		$this->article	= $articles;
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
		$volume	= 0;
		foreach( $articles as $articleName => $articleQuantity )
			for( $i=0; $i<$articleQuantity; $i++ )
				$volume	+= $this->volumes[$packetName][$articleName];
		if( $volume > 1 )
			throw RangeException( 'To much Articles for Packet' ); 
		$packet	= new Alg_Parcel_Packet( $packetName, $articles, $volume );
		return $packet;
	}
}
?>