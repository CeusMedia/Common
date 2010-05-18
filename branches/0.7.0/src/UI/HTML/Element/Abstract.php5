<?php
/**
 *	Abstract HTML Tag Base.
 *	@category		cmClasses
 *	@package		UI.HTML.Element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Abstract HTML Tag Base.
 *	@category		cmClasses
 *	@package		UI.HTML.Element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
abstract class UI_HTML_Element_Abstract
{
	protected $id		= NULL;
	protected $class	= NULL;
	protected $content	= array();
	protected $events	= array();

	public function __toString()
	{
		return $this->render();
	}

	public function addClass( $class )
	{
		if( is_array( $class ) )
			$class	= implode( ' ', $class );
		$this->class	.= $this->class ? ' ' : '';
		$this->class	.= $class;
		return $this;
	}

	public function addContent( $content, $emptyBefore = FALSE )
	{
		if( $emptyBefore )
			$this->content	= array();
		$this->content[]	= $content;
		return $this;
	}

	public function addEvent( $name, $content )
	{
		$key		= 'on'.strtolower( $name );
		$content	= trim( $content );
		if( !empty( $this->events[$key] ) )
			$this->events[$key]	.= '; '.$content;
		else
			$this->events[$key]	= $content;
		return $this;
	}

	public function removeClass( $class )
	{
		$list	= array();
		foreach( explode( ' ', $this->class ) as $current )
			if( $current !== $class )
				$list[]	= $current;
		$this->setClass( $list );
		return $this;
	}

	abstract public function render();

	public function renderContent()
	{
		$list	= array();
		foreach( $this->content as $item )
		{
			if( $item instanceof UI_HTML_Element_Abstract )
				$item	= $item->render();
			if( !is_string( $item ) )
				throw new InvalidArgumentException( 'Must be String or extend UI_HTML_Element_Abstract' );
			$list[]	= $item;
		}
		return join( $list );
	}

/*	protected function renderAttributes()
	{
		$list		= array();
		$members	= get_object_vars( $this );
		if( empty( $members ) )
			return "";
		foreach( $members as $key => $value )
		{
			if( $value === NULL )
				continue;
			if( $key == "content" )
				continue;
			if( is_array( $value ) )
				$value	= implode( ' ', $value );
			$value	= addslashes( (string) $value );
			$list[]	= strtolower( $key ).'="'.$value.'"';
		}
		$list	= implode( " ", $list );
		return " ".$list;
	}*/

/*	protected function renderEvents()
	{
		$list		= array();
		foreach( $this->events as $key => $value )
		{
			if( is_array( $value ) )
				$value	= implode( ' ', $value );
			$value	= addslashes( (string) $value );
			$list[]	= $key.'="'.$value.'"';
		}
		$list	= implode( ' ', $list );
		return ' '.$list;
	}*/

	protected static function renderTag( $tagName, $content, $attributes = array() )
	{
		return UI_HTML_Tag::create( $tagName, $content, $attributes );
	}

	public function setClass( $class )
	{
		if( is_array( $class ) )
			$class	= implode( ' ', $class );
		$this->class	= $class;
		return $this;
	}

	public function setContent( $content )
	{
		$this->addContent( $content, TRUE );
		return $this;
	}
	
	public function setId( $id )
	{
		if( !is_string( $id ) )
			throw new InvalidArgumentException( 'Has to be string' );
		$this->id	= $id;
		return $this;
	}
}
?>