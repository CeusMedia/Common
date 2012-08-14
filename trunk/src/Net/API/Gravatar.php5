<?php
/**
 *	Generates URL for Gravatar API.
 *
 *	Copyright (c) 2012 Christian Würker (ceusmedia.com)
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
 *	@copyright		2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://gravatar.com/site/implement/images/php/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Generates URL for Gravatar API.
 *
 *	@category		cmClasses
 *	@package		Net.API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://gravatar.com/site/implement/images/php/
 *	@since			0.7.6
 *	@version		$Id$
 */
class Net_API_Gravatar{
	
	protected $url		= 'http://www.gravatar.com/avatar/';
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

	/**
	 *	Returns URL of Gravatar image.
	 *	@access		public
	 *	@param		string		$email			Email address to get Gravatar image for
	 *	@return		string		Gravatar URL
	 */
	public function getUrl( $email ){
		$email	= md5( strtolower( trim( $email ) ) );
		$query	= array(
			's'	=> $this->size,
			'd'	=> $this->default,
			'r'	=> $this->rate,
		);
		return $this->url.$email.'?'.http_build_query( $query, NULL, '&amp;' );
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
		return UI_HTML_Tag::create( 'img', NULL, $attributes );
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
}
?>
