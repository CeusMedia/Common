<?php
/**
 *	Builds HTML Link Button.
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

use CeusMedia\Common\ADT\URL\Inference;
use CeusMedia\Common\UI\HTML\Tag;

/**
 *	Builds HTML Link Button.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class Link extends Abstraction
{
	public static $defaultClass	= 'neutral';
	protected $parameters	= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$parameters		Target URL of linked Button
	 *	@param		mixed		$content		Label String or HTML Object
	 *	@param		array		$attributes		Map of Attributes to set
	 *	@return		void
	 */
	public function __construct( $parameters = [], $content = NULL, $attributes = [] )
	{
		parent::__construct( $content, $attributes );
		$this->addClass( self::$defaultClass );
		foreach( $parameters as $key => $value )
			$this->parameters[$key]	= $value;
	}

	/**
	 *	Renders Button to HTML String.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$attributes	= $this->getAttributes();
		$attributes['onclick']	= 'location.href=\''.Inference::buildStatic( $this->parameters ).'\';';
		return Tag::create( 'button', $this->content, $attributes );
	}
}
