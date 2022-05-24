<?php
/**
 *	Abstract HTML Button.
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
 *	@package		CeusMedia_Common_UI_HTML_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */

namespace CeusMedia\Common\UI\HTML\Button;

use CeusMedia\Common\UI\HTML\Abstraction as HtmlAbstraction;
use CeusMedia\Common\UI\HTML\Tag;

/**
 *	Abstract HTML Button.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
abstract class Abstraction extends HtmlAbstraction
{
	public static $defaultClass		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$content		Label String or HTML Object
	 *	@param		array		$attributes		Map of Attributes to set
	 *	@return		void
	 */
	public function __construct( $content, $attributes = array() )
	{
		$this->attributes['type']	= 'button';
		$this->addClass( self::$defaultClass );
		$this->setContent( $content );
		$this->addAttributes( $attributes );
	}

	/**
	 *	Renders Button to HTML String.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		return Tag::create( 'button', $this->content, $this->getAttributes() );
	}
}
