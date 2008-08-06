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