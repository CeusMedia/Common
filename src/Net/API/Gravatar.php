<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Generates URL for Gravatar API.
 *
 *	Copyright (c) 2012-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://gravatar.com/site/implement/images/php/
 *	@see			http://gravatar.com/site/implement/xmlrpc/
 */

namespace CeusMedia\Common\Net\API;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use CeusMedia\Common\XML\RPC\Client as RpcClient;
use Exception;
use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 *	Generates URL for Gravatar API.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://gravatar.com/site/implement/images/php/
 *	@see			http://gravatar.com/site/implement/xmlrpc/
 *	@todo			test implementations
 *	@todo			code doc
 */
class Gravatar
{
	protected $url		= 'https://secure.gravatar.com/avatar/';
	protected $urlRpc	= 'https://secure.gravatar.com/xmlrpc';
	protected $size		= 80;
	protected $default	= 'mm';
	protected $rate		= 'g';
	protected $defaults	= ['404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro', 'blank'];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		integer|NULL	$size		Size of image (within 1 and 512) in pixels
	 *	@param		string|NULL		$rate		Rate to allow at least (g | pg | r | x)
	 *	@param		string|NULL		$default	Default set to use if no Gravatar is available (404 | mm | identicon | monsterid | wavatar)
	 *	@return		void
	 */
	public function __construct( ?int $size = NULL, ?string $rate = NULL, ?string $default = NULL )
	{
		if( !is_null( $size ) )
			$this->setSize( $size );
		if( !is_null( $rate ) )
			$this->setRate( $rate );
		if( !is_null( $default ) )
			$this->setDefault( $default );
	}

	/**
	 *	@param		string		$email
	 *	@param		string		$method
	 *	@param		array		$arguments
	 *	@return		array
	 *	@throws		Exception
	 */
	protected function callXmlRpc( string $email, string $method, array $arguments ): array
	{
		if( !array_key_exists( 'password', $arguments ) )
			throw new InvalidArgumentException( 'argument "password" is missing' );
		$hash		= md5( strtolower( trim( $email ) ) );
		$client		= new RpcClient( $this->urlRpc.'?user='.$hash );
		return $client->call( 'grav.'.$method, [(object) $arguments] );
	}

	public function exists( string $email, string $password ): bool
	{
		$hash		= md5( strtolower( trim( $email ) ) );
		$data		= ['password' => $password, 'hashes' => [$hash]];
		$response	= $this->callXmlRpc( $email, 'exists', $data );
		return (bool) $response[0][$hash];
	}

	/**
	 *	Returns URL of Gravatar image.
	 *	@access		public
	 *	@param		string		$email			Email address to get Gravatar image for
	 *	@return		string		Gravatar URL
	 */
	public function getUrl( string $email ): string
	{
		$hash	= md5( strtolower( trim( $email ) ) );
		$query	= [
			's'	=> $this->size,
			'd'	=> $this->default,
			'r'	=> $this->rate,
		];
		return $this->url.$hash.'?'.http_build_query( $query, NULL, '&amp;' );
	}

	public function listAddresses( string $email, string $password ): array
	{
		$response	= $this->callXmlRpc( $email, 'addresses', ['password' => $password] );
		$ratings	= [0 => 'g', 1 => 'pg', 2 => 'r', 3 => 'x'];
		foreach( $response[0] as $address => $data )
			$response[0][$address]['rating']	= $ratings[$data['rating']];
		return $response[0];
	}

	public function listImages( string $email, string $password )
	{
		$response	= $this->callXmlRpc( $email, 'userimages', ['password' => $password] );
		$list		= [];
		$ratings	= [0 => 'g', 1 => 'pg', 2 => 'r', 3 => 'x'];
		foreach( $response[0] as $hash => $data )
			$list[$hash]	= ['rating' => $ratings[$data[0]], 'url' => $data[1]];
		return $list;
	}

	/**
	 *	Returns rendered image HTML code.
	 *	@access		public
	 *	@param		string		$email			Email address to get Gravatar image for
	 *	@param		array		$attributes		Additional HTML tag attributes
	 *	@return		string		Image HTML code
	 */
	public function renderImage( string $email, array $attributes = [] ): string
	{
		$attributes['src']		= $this->getUrl( $email );
		$attributes['width']	= $this->size;
		$attributes['height']	= $this->size;
		return HtmlTag::create( 'img', NULL, $attributes );
	}

	/**
	 *	Sets maximum (inclusive) rate.
	 *	@access		public
	 *	@param		string		$rate		Rate to allow at least (g | pg | r | x)
	 *	@return		self
	 */
	public function setRate( string $rate ): self
	{
		if( !in_array( $rate, ['g', 'pg', 'r', 'x'] ) )
			throw new InvalidArgumentException( 'Rate must of one of [g,pg,r,x]' );
		$this->rate	= $rate;
		return $this;
	}

	/**
	 *	Sets default image set used if not Gravatar is available.
	 *	@access		public
	 *	@param		string		$default	Default set to use if no Gravatar is available (404 | mm | identicon | monsterid | wavatar)
	 *	@return		self
	 */
	public function setDefault( string $default ): self
	{
		if( !in_array( $default, $this->defaults ) )
			throw new InvalidArgumentException( 'Default set must of one of [404,mm,identicon,monsterid,wavatar]' );
		$this->default	= $default;
		return $this;
	}

	/**
	 *	Sets size of image to get from Gravatar.
	 *	@access		public
	 *	@param		integer		$size		Size of image (within 1 and 512) in pixels
	 *	@return		self
	 */
	public function setSize( int $size ): self
	{
		if( $size < 1 )
			throw new OutOfBoundsException( 'Size must be at least 1 pixel' );
		if( $size > 512 )
			throw new OutOfBoundsException( 'Size must be at most 512 pixels' );
		$this->size	= $size;
		return $this;
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.deleteUserImage'.
	 *	@todo		test, code doc
	 *	@noinspection PhpUnreachableStatementInspection
	 */
	public function removeImage( string $email, string $password, string $imageId, $rating = 0 )
	{
		throw new RuntimeException( 'Not tested yet' );
		$data		= ['password' => $password, 'userimage' => $imageId, 'rating'	=> $rating];
		$response	= $this->callXmlRpc( $email, 'deleteUserImage', $data );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.saveData'.
	 *	@todo		test, code doc
	 *	@noinspection PhpUnreachableStatementInspection
	 */
	public function saveImage( string $email, string $password, string $imageDataBase64, $rating = 0 )
	{
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'saveData', [
			'password'	=> $password,
			'data'		=> $imageDataBase64,
			'rating'	=> $rating
		] );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.saveUrl'.
	 *	@todo		test, code doc
	 *	@noinspection PhpUnreachableStatementInspection
	 */
	public function saveImageFromUrl( string $email, string $password, string $imageUrl, $rating = 0 )
	{
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'saveUrl', [
			'password'	=> $password,
			'url'		=> $imageUrl,
			'rating'	=> $rating
		] );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.useUserimage'.
	 *	@todo		test, code doc
	 *	@noinspection PhpUnreachableStatementInspection
	 */
	public function setAddressImage( string $email, string $password, string $address, $imageId )
	{
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'useUserimage', [
			'password'	=> $password,
			'addresses'	=> [$address],
			'userimage'	=> $imageId
		] );
		return $response[0];
	}

	/**
	 *	...
	 *	Implements XML RPC method 'grav.removeImage'.
	 *	@todo		test, code doc
	 *	@noinspection PhpUnreachableStatementInspection
	 */
	public function unsetAddressImage( string $email, string $password, $address )
	{
		throw new RuntimeException( 'Not tested yet' );
		$response	= $this->callXmlRpc( $email, 'removeImage', [
			'password'	=> $password,
			'addresses'	=> [$address],
		] );
		return $response[0];
	}
}
