<?php
/**
 *	Abstract Builder for HTML Elements.
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
 *	Abstract Builder for HTML Elements.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
abstract class UI_HTML_Abstract implements Renderable
{
	protected $attributes	= array(
		'class'	=> array()
	);
	protected $content		= NULL;

	/**
	 *	Adds another Class Attribute of Element.
	 *	@access		public
	 *	@param		string		$class		Class Name
	 *	@return		void
	 */
	public function addClass( $class )
	{
		$this->attributes['class'][]	= $class;	
	}
	
	/**
	 *	Adds Attributes of Element.
	 *	@access		public
	 *	@param		string		$attributes	Map of Element Attributes
	 *	@return		void
	 */
	public function addAttributes( $attributes = array() )
	{
		foreach( $attributes as $key => $value )
		{
			if( $key == 'class' )
			{
				if( is_string( $value ) )
					$value	= explode( " ", $value );
				if( !is_array( $value ) && !( $value instanceof ArrayIterator ) )
					throw new InvalidArgumentException( 'Class attribute must be string, array or iterator' );
				foreach( $value as $class )
					$this->addClass( $class );
				continue;
			}
			else
				$this->attributes[$key]	= $value;
		}
	}

	/**
	 *	Returns set Element Attributes.
	 *	@access		public
	 *	@return		array
	 */
	public function getAttributes()
	{
		$attributes	= $this->attributes;
		$attributes['class']	=  NULL;
		if( !empty( $this->attributes['class'] ) )
			$attributes['class']	=  implode( " ", $this->attributes['class'] );
		return $attributes;
	}

	/**
	 *	Renders Content of Element.
	 *	@access		public
	 *	@return		string
	 */
	protected function renderInner()
	{
		if( $this->content instanceof Renderable )
			$content	= $this->content->render();
		return $content;
	}
	
	/**
	 *	Sets Attributes of Element.
	 *	@access		public
	 *	@param		string		$attributes	Map of Element Attributes
	 *	@return		void
	 */
	public function setAttributes( $attributes = array() )
	{
		if( !empty( $attributes['class'] ) && is_string( $attributes['class'] ) )
			$attributes['class']	= explode( ' ', $attributes['class'] );
		$this->addAttributes( $attributes );
	}

	/**
	 *	Sets Content of Element.
	 *	@access		public
	 *	@param		string		$content		Content of Element
	 *	@return		void
	 */
	public function setClasses( $classes )
	{
		$this->attributes['class']	= array();
		if( is_string( $classes ) )
			$classes	= explode( " ", $classes );
		if( !is_array( $classes ) && !( $classes instanceof ArrayIterator ) )
			throw new InvalidArgumentException( 'Class attribute must be string, array or iterator' );
		foreach( $classes as $class )
			$this->addClass( $class );
	}

	/**
	 *	Sets Content of Element.
	 *	@access		public
	 *	@param		mixed		$content		Content of Element
	 *	@return		void
	 */
	public function setContent( $content )
	{
		$this->content	= $content;
	}
}
?>