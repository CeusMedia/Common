<?php
/**
 *	Builder for HTML Form Elements.
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

use CeusMedia\Common\ADT\URL\Inference as Inference;

/**
 *	Builder for HTML Form Elements.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class Form extends Abstraction
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$action		Form Action URI
	 *	@param		string		$content	Form Content
	 *	@param		string		$attributes Map of more Attributes
	 *	@return		void
	 */
	public function __construct( $action, $content = NULL, $attributes = NULL )
	{
		if( !is_null( $action ) )
			$this->setAction( $action );
		if( !is_null( $content ) )
			$this->setContent( $content );
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );
	}

	/**
	 *	Returns rendered Form Element.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$attributes	= $this->getAttributes();
		if( is_array( $attributes['action'] ) )
			$attributes['action']	= Inference::buildStatic( $attributes['action'] );
		$content	= $this->renderInner( $this->content );
		return Tag::create( "form", $content, $attributes );
	}

	/**
	 *	Sets Form Action URI.
	 *	@access		public
	 *	@param		string		$url		Form Action URL
	 *	@return		void
	 */
	public function setAction( $url )
	{
		$this->attributes['action']	= $url;
	}
}
