<?php
/**
 *	Abstract HTML Tag Base.
 *	@category	cmClasses
 *	@package	ui.html.element
 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since		0.7.0
 *	@version	$Id$
 */
/**
 *	Abstract HTML Tag Base.
 *	@category	cmClasses
 *	@package	ui.html.element
 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since		0.7.0
 *	@version	$Id$
 */
abstract class UI_HTML_Element_Abstract
{
	protected $id		= NULL;
	protected $class	= NULL;
	protected $content	= array();

	public function addClass( $class )
	{
		if( is_array( $class ) )
			$class	= implode( ' ', $class );
		$this->class	.= $this->class ? ' ' : '';
		$this->class	.= $class;
	}

	public function removeClass( $class )
	{
		$list	= array();
		foreach( explode( ' ', $this->class ) as $current )
			if( $current !== $class )
				$list[]	= $current;
		$this->setClass( $list );
	}

	public function addContent( $content, $emptyBefore = FALSE )
	{
		if( $emptyBefore )
			$this->content	= array();
		$this->content[]	= $content;
	}

#	public function addEvent( $name, $content )
#	{
#		$key	= 'on'.$name;
#		$this->attributes[$key]	.= $this->attributes[$key] ? ';'.$content : $content;
#	}
	
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

	protected function renderAttributes()
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
	}

	protected static function renderTag( $tagName, $content, $attributes = array() )
	{
		return UI_HTML_Tag::create( $tagName, $content, $attributes );
/*		if( !is_null( $attributes ) && !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an Array.' );
		$tagName	= strtolower( $tagName );
		$list	= array();
		if( !is_null( $attributes ) && is_array( $attributes ) )
			foreach( $attributes as $key => $value )
				if( $value !== NULL && $value !== FALSE )
	#			if( !empty( $value ) )
					$list[]	= strtolower( $key ).'="'.$value.'"';
		$attributes	= implode( " ", $list );
		if( $attributes )
			$attributes	= " ".$attributes;
		$unsetContent	= !( $content !== NULL && $content !== FALSE );
		if( $unsetContent && $tagName !== "style" )
			$tag	= "<".$tagName.$attributes."/>";
		else
			$tag	= "<".$tagName.$attributes.">".$content."</".$tagName.">";
		return $tag;*/
	}

	public function setClass( $class )
	{
		if( is_array( $class ) )
			$class	= implode( ' ', $class );
		$this->class	= $class;
	}

	public function setContent( $content )
	{
		$this->addContent( $content, TRUE );
	}
	
	public function setId( $id )
	{
		if( !is_string( $id ) )
			throw new InvalidArgumentException( 'Has to be string' );
		$this->id	= $id;
	}
}
?>