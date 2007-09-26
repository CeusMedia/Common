<?php
import( 'de.ceus-media.adt.set.Set' );
import( 'de.ceus-media.adt.set.Pair' );
/**
 *	Map of Pairs.
 *	@package		adt.set
 *	@extends		Set
 *	@uses			Pair
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Map of Pairs.
 *	@package		adt.set
 *	@extends		Set
 *	@uses			Pair
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Map extends Set
{
	/**	@var	Set		$domains	Set of Domain Elements */
	var $domains;
	/**	@var	Set		$images		Set of Image Elements */
	var $images;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Set		$domains	Set of Domain Elements
	 *	@param		Set		$images		Set of Image Elements
	 *	@return		void
	 */
	public function __construct( $domains, $images )
	{
		parent::__construct();
		$this->domains	= $domains;
		$this->images	= $images;
	}


	/**
	 *	Adds a new Pair.
	 *	@access		public
	 *	@param		Element		$domain		Key Element
	 *	@param		Element		$image		Value Element
	 *	@return		bool
	 */
	public function addPair( $domain, $image )
	{
		if( !$this->isMapped( $domain, $image ) )
		{
			$pair = new Pair( $domain, $image );
			$this->add( $pair );
			return true;
		}
		return false;
	}

	/**
	 *	no desc yet.
	 *	@access		public
	 *	@param		Element		$domain		Key Element
	 *	@param		Element		$image		Value Element
	 *	@return		bool
	 */
	public function isMapped( $domain, $image )
	{
		$this->rewind();
		while( $this->hasNext() )
		{
			$pair = $this->getNext();
			if( $pair->getKey() == $domain && $pair->getValue() == $image )
				return true;
		}
		return false;
	}

	/**
	 *	no desc yet.
	 *	@access		public
	 *	@param		Element		$image		Value Element
	 *	@return		Set
	 */
	public function getDomain( $image )
	{
		$domain = new Set();
		$this->rewind();
		while( $this->hasNext() )
		{
			$pair = $this->getNext();
			if( $pair->getValue() == $image )
			{
				$domain->add( $pair->getKey() );
			}
		}
		return $domain;
	}

	/**
	 *	no desc yet.
	 *	@access		public
	 *	@param		Element		$domain		Key Element
	 *	@return		Set
	 */
	public function getImage()
	{
		$image = new Set();
		$this->rewind();
		while( $this->hasNext() )
		{
			$pair = $this->getNext();
			if( $pair->getKey() == $image )
			{
				$image->add( $pair->getValue() );
			}
		}
		return $image;
	}

	/**
	 *	Indicates wheter this Map is injective.
	 *	@access		public
	 *	@return		bool
	 */
	public function isInjective()
	{
		$images = new Set();
		$pairimages = new Set();
		$this->images->rewind();
		while( $this->images->hasNext() )
		{
			$image = $this->images->getNext();
			$this->rewind();
			while( $this->hasNext() )
			{
				$pair = $this->getNext();
				$pair_image = $pair->getValue();
				if( $pair_image == $image )
				{
					if( !$pairimages->inSet( $pair_image ) )
						$pairimages->add( $pair_image );
					else return false;
				}
			}
		}
		return true;
	}

	/**
	 *	Indicates wheter this Map is surjective.
	 *	@access		public
	 *	@return		bool
	 */
	public function isSurjective()
	{
		$images = new Set();
		$pairimages = new Set();
		$this->images->rewind();
		while( $this->images->hasNext() )
		{
			$image = $this->images->getNext();
			$this->rewind();
			while( $this->hasNext() )
			{
				$pair = $this->getNext();
				$pair_image = $pair->getValue();
				if( $pair_image == $image )
				{
					if( !$pairimages->inSet( $pair_image ) )
						$pairimages->add( $pair_image );
				}
			}
			if( !$pairimages->inSet( $image ) )
				return false;
		}
		return true;
	}

	/**
	 *	Indicates wheter this Map is bijective.
	 *	@access		public
	 *	@return		bool
	 */
	public function isBijective()
	{
		return $this->isInjective() && $this->isSurjective();
	}

	/**
	 *	Returns the Map as String.
	 *	@access		public
	 *	@return		string
	 */
	public function toArray()
	{
		$array = array();
		$this->rewind();
		while( $this->hasNext() )
		{
			$pair	= $this->getNext();
			$key	= $pair->getKey();
			$value	= $pair->getValue();
			$array[$key] = $value;
		}
		return $array;
	}

	/**
	 *	Returns the Map as String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		$array = array();
		$this->rewind();
		while( $this->hasNext() )
		{
			$pair	= $this->getNext();
			$array[]	= "(".$pair->getKey().",".$pair->getValue().")";
		}
		$string = "{".implode( ", ", $array)."}";
		return $string;
	}
}
?>