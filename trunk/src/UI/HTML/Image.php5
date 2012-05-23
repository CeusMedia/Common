<?php
/**
 *	Builder for HTML Image Elements.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builder for HTML Image Elements.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Image extends UI_HTML_Abstract
{
	protected $title	= NULL;
	protected $url		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url		URL
	 *	@param		string		$title		Title
	 *	@param		array		$attributes	Map of other Attributes
	 *	@return		void
	 */
	public function __construct( $url = NULL, $title = NULL, $attributes = array() )
	{
		if( !empty( $url ) )
			$this->setUrl( $url );
		if( !empty( $title ) )
			$this->setTitle( $title );
		if( $attributes )
			$this->setAttributes( $attributes );
	}

	/**
	 *	Returns rendered HTML Element.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$attributes	= $this->getAttributes();
		if( empty( $this->url ) )
			throw new InvalidArgumentException( 'Image URL is empty' );
		$attributes['title']	= (string) $this->title;
		$attributes['alt'] 		= (string) $this->title;
		$attributes['src'] 		= $this->url;
		return UI_HTML_Tag::create( 'img', NULL, $attributes );
	}

	/**
	 *	Sets Title.
	 *	@access		public
	 *	@param		string		$title		Image Title
	 *	@return		void
	 */
	public function setTitle( $title )
	{
		$this->title	= $title;	
	}
	
	/**
	 *	Sets URL.
	 *	@access		public
	 *	@param		string		$url		Image URL
	 *	@return		void
	 */
	public function setUrl( $url )
	{
		$this->url	= $url;	
	}
}
?>