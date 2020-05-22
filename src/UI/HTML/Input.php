<?php
/**
 *	Builder for HTML Input Elements.
 *
 *	Copyright (c) 2010-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builder for HTML Input Elements.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Input extends UI_HTML_Abstract
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Name
	 *	@param		string		$value		Value
	 *	@param		array		$attributes	Map of other Attributes
	 *	@return		void
	 */
	public function __construct( $name = NULL, $value = NULL, $attributes = NULL )
	{
		if( !is_null( $name ) )
			$this->setName( $name );
		if( !is_null( $value ) )
			$this->setValue( $value );
		$attributes['type']	= "text";
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
	}

	/**
	 *	Returns rendered Input Element.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$attributes	= $this->getAttributes();
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Sets Name of Input Element.
	 *	@access		public
	 *	@param		string		$name		Name
	 *	@return		void
	 */
	public function setName( $name )
	{
		$this->attributes['name']	= $name;
	}


	/**
	 *	Sets Value of Input Element.
	 *	@access		public
	 *	@param		string		$value		Value
	 *	@return		void
	 */
	public function setValue( $value )
	{
		$this->attributes['value']	= $value;
	}
}
?>