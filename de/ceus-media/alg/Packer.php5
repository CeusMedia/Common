<?php
/**
 *	Calculator for Package Type, Quantity and Price used to Pack several Articles by matching Articles in different Package Sizes ordered by Price.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.10.2006
 *	@version			0.1
 */
/**
 *	Calculator for Package Type, Quantity and Price used to Pack several Articles by matching Articles in different Package Sizes ordered by Price.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.10.2006
 *	@version			0.1
 */
class Packer
{
	/**	@var		bool			$_debug		Flag: Enable Debug Mode with Remarks */
	var $_debug	= false;
	/**	@var		array		$_contents	Array of Packages and their maximum Quantity of each Article */
	var $_contents = array();
	/**	@var		array		$_packages	Array of Articles and their Quantities */
	var $_packages	= array();
		
	/**
	 *	Constructor
	 *	@access		public
	 *	@param		bool			$debug		Flag: Enable Debug Mode with Remarks
	 *	@return		void
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	public function __construct( $debug = false )
	{
		$this->_debug	= $debug;
	}
	
	/**
	 *	Returns total Price of all Packages used to pack several Articles.
	 *	@access		public
	 *	@param		array		$array		Array of Articles and their Quantities
	 *	@return		array
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function getPrice( $array )
	{
		if( !count( $this->_packages ) )
			trigger_error( "Packer: No Packages definied", E_USER_ERROR );
		if( !count( $this->_contents ) )
			trigger_error( "Packer: No Package Contents definied", E_USER_ERROR );
		$pack	= $this->getPackage( $array );
		$price	= $this->_getPackagePrice( $pack[0] );
		$total	= $pack[1] * $price;
		return $total;
	}


	/**
	 *	Returns fitting Package and Package Quantity for an Array of Articles and their Quantities.
	 *	@access		public
	 *	@param		array		$array		Array of Articles and their Quantities
	 *	@return		array
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function getPackage( $array )
	{
		if( !count( $this->_packages ) )
			trigger_error( "Packer: No Packages definied", E_USER_ERROR );
		if( !count( $this->_contents ) )
			trigger_error( "Packer: No Package Contents definied", E_USER_ERROR );
		$largest	= $this->_getLargestArticle( array_keys( $array ) );
		$package	= $this->_getPackage( $largest );
		return $this->_pack( $array, $package );
	}

	/**
	 *	Sets maximum Quantity of each Article within each Package.
	 *	@access		public
	 *	@param		array		$contents	Array of Packages and their maximum Quantity of each Article
	 *	@return		void
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function setContents( $contents )
	{
		$this->_contents	= $contents;
	}
	
	/**
	 *	Sets Packages and their Prices.
	 *	@access		public
	 *	@param		array		$array		Array of Packages and their Prices, sorted by Prices (!)
	 *	@return		void
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function setPackages( $packages )
	{
		$this->_packages	= $packages;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns a larger Package of current Package, if exists.
	 *	@access		private
	 *	@param		string		$current		Currently selected Package
	 *	@return		string
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function _getLargerPackage( $current )
	{
		$packages	 = $this->_packages;
		do
		{
			$package	= array_shift( $packages );
			if( $package[0] == $current )
			{
				$larger	= array_shift( $packages );
				return $larger[0];
			}
		}
		while( count( $packages ) );
		die( 'no larger package found' );
	}

	/**
	 *	Returns Article with largest Package out of a Liste of Articles.
	 *	@access		private
	 *	@param		array		$list			List of Articles
	 *	@return		string
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function _getLargestArticle( $list )
	{
		$largest	= array_shift( $list );
		$current	= $this->_getPackage( $largest );
		foreach( $list as $article )
		{
			$package		= $this->_getPackage( $article );
			$price		= $this->	_getPackagePrice( $package );
			if( $price > $this->_getPackagePrice( $current ) )
			{
				$current	= $package;
				$largest	= $article;
			}
		}
		return $largest;
	}
	
	/**
	 *	Returns smallest Package for an Article.
	 *	@access		private
	 *	@param		string		$article		Article to get smalles Package for
	 *	@return		string
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function _getPackage( $article )
	{
		foreach( $this->_packages as $package )
		{
			$fits	= $this->_contents[$package[0]][$article];
			if( $this->_debug )
				remark( "_getPackage: ", array( 'package' => $package[0], 'article' => $article, 'fits' => $fits ) );
			if( $fits > 0 )
				return $package[0];
		}
		die( 'no package found for article '.$article );
	}

	/**
	 *	Returns Price of a Package.
	 *	@access		private
	 *	@param		string		$package		Package to get Price for
	 *	@return		float
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function _getPackagePrice( $package ) 
	{
		foreach( $this->_packages as $_package )
			if( $_package[0] == $package )
				return $_package[1];
	}

	/**
	 *	Returns smallest Package out of all Packages. Package List must be sorted by Price.
	 *	@access		private
	 *	@return		string
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function _getSmallestPackage()
	{
		$package	= $this->_packages[0];
		return $package[0];
	}

	/**
	 *	Recursive Packing Method.
	 *	@access		private
	 *	@param		array		$array		Array of Articles and their Quantities
	 *	@param		string		$package		Currently selected Package
	 *	@param		float			$content		Current Content Level of selected Package
	 *	@param		int			$packages	Quantity of selected Package
	 *	@return		array
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	 function _pack( $array, $package, $content = 0, $packages = 1 )
	{
		if( $this->_debug )
			remark( "_pack", array( 'package' => $package, 'content' => $content, 'packages' => $packages ) );
		foreach( $array as $article => $quantity )
		{
			$smallest	= $this->_getPackage( $article );
			if( $this->_debug )
				remark( "__loop", array( 'article' => $article, 'quantity' => $quantity, 'smallest' => $smallest ) );
			if( $this->_getPackagePrice( $smallest ) > $this->_getPackagePrice( $package ) )
				return $this->_repack( $array, $package, $packages );
			$volume	= $quantity / $this->_contents[$package][$article] / $packages;
			if( $this->_debug )
				remark( "volume: ".$volume );
			$content	+= $volume;
			if( $content > 1 )
				return $this->_repack( $array, $package, $packages );
		}
		return array( $package, $packages );
	}

	/**
	 *	Restart Packing Method with larger Package or larger Package Quantity.
	 *	@access		private
	 *	@param		array		$array		Array of Articles and their Quantities
	 *	@param		string		$package		Currently selected Package
	 *	@param		int			$quantity		Quantity of selected Package
	 *	@return		array
	 *	@since		24.10.2006
	 *	@version		0.1
	 */
	function _repack( $array, $package, $quantity )
	{
		if( $this->_debug )
			remark( "__repacking!" );
		$larger	= $this->_getLargerPackage( $package );
		if( $larger )
			return $this->_pack( $array, $larger, 0, $quantity );
		else
		{
			$largest	= $this->_getLargestArticle( array_keys( $array ) );
			$package	= $this->_getPackage( $largest );
			return $this->_pack( $array, $package, 0, ++$quantity );
		}
	}
}
?>