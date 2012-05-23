<?php
/**
 *	Builder for HTML List Item Elements.
 *
 *	Copyright (c) 2010 Christian Würker (ceusmedia.com)
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
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builder for HTML List Item Elements.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_ListItem extends UI_HTML_Abstract
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$content	Item Content
	 *	@param		array		$attributes Map of Attributes
	 */
	public function __construct( $content = NULL, $attributes = NULL )
	{
		if( !is_null( $content ) )
			$this->setContent( $content );
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );
	}

	/**
	 *	Returns rendered List Item Element.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$content	= $this->renderInner( $this->content );
		return UI_HTML_Tag::create( "li", $content, $this->getAttributes() );
	}
}
?>