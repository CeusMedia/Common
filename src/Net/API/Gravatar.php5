<?php
/**
 *	Generates URL for Gravatar API.
 *
 *	Copyright (c) 2012-2013 Christian Würker (ceusmedia.com)
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
 *	@package		Net.API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://gravatar.com/site/implement/images/php/
 *	@see			http://gravatar.com/site/implement/xmlrpc/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Generates URL for Gravatar API.
 *
 *	@category		cmClasses
 *	@package		Net.API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://gravatar.com/site/implement/images/php/
 *	@see			http://gravatar.com/site/implement/xmlrpc/
 *	@since			0.7.6
 *	@version		$Id$
 *	@todo			test implementations
 *	@todo			code doc
 */
class Net_API_Gravatar{
	
	protected $url		= 'http://www.gravatar.com/avatar/';
	protected $urlRpc	= 'https://secure.gravatar.com/xmlrpc';
	protected $size		= 80;
	protected $default	= 'mm';
	protected $rate		= 'g';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		integer		$size		Size of image (within 1 and 512) in pixels
	 *	@param		string		$rate		Rate to allow atleast (g | pg | r | x)
	 *	@param		string		$default	Default set to use if no Gravatar is available (404 | mm | identicon | monsterid | wavatar)
	 *	@return		void
	 */	
	public function __construct( $size = NULL, $rate = NULL, $default = NULL ){
		if( !is_null( $size ) )
			$this->setSize( $size );
		if( !is_null( $rate ) )
			$this->setRate( $rate );
		if( !is_null( $default ) )
			$this->setDefault( $default );
	}

	protected function callXmlRpc( $email, $method, $arguments ){
		if( !is_array( $arguments ) )
			throw new InvalidArgumentException( 'arguments must be an array' );
		if( !array_key_exists( 'password', $arguments ) )
			throw new InvalidArgumentException( 'argument "password" is missing' );
		$hash		= md5( strtolower( trim( $email ) ) );
		$client		= new XML_RPC_Client( $this->urlRpc.'?user='.$hash );
		return $client->call( 'grav.'.$method, array( (object) $arguments ), TRUE );
	}

	public function exists( $email, $password ){
		$hash		= md5( strtolower( trim( $email ) ) );
		$data		= array( 'password' => $password, 'hashes' => array( $hash ) );
		$response	= $this->callXmlRpc( $email, 'exists', $data );
		return (bool) $response[0][$hash];
	}

	/**
	 *	Returns URL of Gravatar image.
	 *	@access		public
	 *	@param		string		$email			Email address to get Gravatar image for
	 *	@return		string		Gravatar URL
	 */
	public function getUrl( $email ){
		$hash	= md5( strtolower( trim( $email ) ) );
		$query	= array(
			's'	=> $this->size,
			'd'	=> $this->default,
			'r'	=> $this->rate,
		);
		return $this->url.$hash.'?'.http_build_query( $query, NULL, '&amp;' );
	}

	public function listAddresses( $email, $password ){
		$response	= $this->callXmlRpc( $email, 'addresses', array( 'password' => $password ) );
		$ratings	= array( 0 => 'g', 1 => 'pg', 2 => 'r', 3 => 'x' );
		foreach( $response[0] as $address => $data )
			$response[0][$address]['rating']	= $ratings[$data['rating']];
		return $response[0];
	}

	public function listImages( $email, $password ){
		$response	= $this->callXmlRpc( $email, 'userimages', array( 'password' => $password ) );
		$list		= array();
		$ratings	= array( 0 => 'g', 1 => 'pg', 2 => 'r', 3 => 'x' );
		foreach( $response[0] as $hash => $data )
			$list[$hash]	= array( 'rating' => $ratings[$data[0]], 'url' => $data[1] );
		return $list;
	}

	/**
	 *	Returns rendered image HTML code.
	 *	@access		public
	 *	@param		string		$email			Email address to get Gravatar image for
	 *	@param		array		$attributes		Additional HTML tag attributes
	 *	@return		string		Image HTML code 
	 */
	public function renderImage( $email, $attributes = array() ){
		$attributes['src']		= $this->getUrl( $email );
		$attributes['width']	= $this->size;
		$attributes['height']	= $this->size;
		return self::makeNode( 'img', NULL, $attributes );
	}

	/**
	 *	Sets maximum (inclusive) rate.
	 *	@access		public
	 *	@param		string		$rate		Rate to allow atleast (g | pg | r | x)
	 *	@return		void
	 */
	public function setRate( $rate ){
		if( !in_array( $rate, array( 'g', 'pg', 'r', 'x' ) ) )
			throw new InvalidArgumentException( 'Rate must of one of [g,pg,r,x]' );
		$this->rate	= $rate;
	}

	/**
	 *	Sets default image set used if not Gravatar is available.
	 *	@access		public
	 *	@param		string		$default	Default set to use if no Gravatar is available (404 | mm | identicon | monsterid | wavatar)
	 *	@return		void
	 */
	public function setDefault( $default ){
		if( !in_array( $default, array( '404', 'mm', 'identicon', 'monsterid', 'wavatar' ) ) )
			throw new InvalidArgumentException( 'Default set must of one of [404,mm,identicon,monsterid,wavatar]' );
		$this->default	= $default;
	}

	/**
	 *	Sets size of image to get from Gravatar.
	 *	@access		public
	 *	@param		integer		$size		Size of image (within 1 and 512) in pixels
	 *	@return		void
	 */
	public function setSize( $size ){
		if( !is_integer( $size ) )
			throw new InvalidArgumentException( 'Size must be an integer' );
		if( $size < 1 )
			throw new OutOfBoundsException( 'Size must be atleast 1 pixel' );
		if( $size > 512 )
			throw new OutOfBoundsException( 'Size must be atmost 512 pixels' );
		$this->size	= $size;
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.deleteUserImage'.
	 *	@todo		test, code doc
	 */
	public function removeImage( $email, $password, $imageId, $rating = 0 ){
		throw new RuntimeException( 'Not tested yet' );
		$data		= array( 'password' => $password, 'userimage' => $imageId );
		$response	= $this->callXmlRpc( $email, 'deleteUserImage', $data );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.saveData'.
	 *	@todo		test, code doc
	 */
	public function saveImage( $email, $password, $imageDataBase64, $rating = 0 ){
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'saveData', array(
			'password'	=> $password,
			'data'		=> $imageDataBase64,
			'rating'	=> $rating
		) );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.saveUrl'.
	 *	@todo		test, code doc
	 */
	public function saveImageFromUrl( $email, $password, $imageUrl, $rating = 0 ){
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'saveUrl', array(
			'password'	=> $password,
			'url'		=> $imageUrl,
			'rating'	=> $rating
		) );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.useUserimage'.
	 *	@todo		test, code doc
	 */
	public function setAddressImage( $email, $password, $address, $imageId ){
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'useUserimage', array(
			'password'	=> $password,
			'addresses'	=> array( $address ),
			'userimage'	=> $imageId
		) );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.removeImage'.
	 *	@todo		test, code doc
	 */
	public function unsetAddressImage( $email, $password, $address ){
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'removeImage', array(
			'password'	=> $password,
			'addresses'	=> array( $address ),
		) );
		return $response[0];
	}
}
?>
