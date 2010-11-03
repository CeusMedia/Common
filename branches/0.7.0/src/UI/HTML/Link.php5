<?php
/**
 *	Builder for HTML Link Elements.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builder for HTML Link Elements.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Link extends UI_HTML_Abstract
{
	protected $label		= NULL;
	protected $url			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url		Link URL
	 *	@param		string		$label		Link Label
	 *	@param		array		$attributes Map of other Attributes
	 *	@return		void
	 */
	public function __construct( $url = NULL, $label = NULL, $attributes = NULL )
	{
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );	
		if( !is_null( $label ) )
			$this->setContent( $label );	
		$this->setUrl( $url );	
	}

	/**
	 *	Returns rendered Link Element
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$attributes	= $this->getAttributes();
		if( is_array( $attributes['href'] ) )
			$attributes['href']	= ADT_URL_Inference::buildStatic( $attributes['href'] );
		$content	= $this->renderInner( $this->content );
		if( !is_string( $content ) )
			throw new InvalidArgumentException( 'Link label is neither rendered nor renderable' );
		return UI_HTML_Tag::create( "a", $content, $attributes );	
	}
	
	public function setUrl( $url )
	{
		$this->attributes['href']	= $url;
	}
}
?>