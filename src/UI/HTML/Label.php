<?php
/**
 *	Builder for HTML Label Elements.
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */

namespace CeusMedia\Common\UI\HTML;

/**
 *	Builder for HTML Label Elements.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class Label extends Abstraction
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$label
	 *	@param		string		$relation
	 *	@param		array		$attributes		Map of other Attributes
	 *	@return
	 */
	public function __construct( $label = NULL, $relation = NULL, $attributes = NULL )
	{
		if( !is_null( $label ) )
			$this->setContent( $label );
		if( !is_null( $label ) )
			$this->setAttributes( array( 'for', $relation ) );
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
	}

	/**
	 *	Returns rendered Label Element.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$content	= $this->renderInner( $this->content );
		return Tag::create( 'label', $content, $this->getAttributes() );
	}
}
