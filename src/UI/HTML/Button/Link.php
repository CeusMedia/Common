<?php
/**
 *	Builds HTML Link Button.
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builds HTML Link Button.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Button
 *	@extends		UI_HTML_Button_Abstract
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Button_Link extends UI_HTML_Button_Abstract
{
	public static $defaultClass	= 'neutral';
	protected $parameters	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$parameters		Target URL of linked Button
	 *	@param		mixed		$content		Label String or HTML Object
	 *	@param		array		$attributes		Map of Attributes to set
	 *	@return		void
	 */
	public function __construct( $parameters = array(), $content = NULL, $attributes = array() )
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
		$attributes['onclick']	= 'location.href=\''.ADT_URL_Inference::buildStatic( $this->parameters ).'\';';
		return UI_HTML_Tag::create( 'button', $this->content, $attributes );
	}
}
