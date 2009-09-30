<?php
abstract class UI_HTML_Abstract implements Renderable
{
	protected $attributes	= array(
		'class'	=> array()
	);
	protected $content		= NULL;

	public function addClass( $class )
	{
		$this->attributes['class'][]	= $class;	
	}
	
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
			$this->attributes[$key]	= $value;
		}
	}

	public function getAttributes()
	{
		$attributes	= $this->attributes;
		$attributes['class']	=  NULL;
		if( !empty( $this->attributes['class'] ) )
			$attributes['class']	=  implode( " ", $this->attributes['class'] );
		return $attributes;
	}

	protected function renderInner( $content )
	{
		if( $content instanceof Renderable )
			$content	= $content->render();
		return $content;
	}
	
	public function setAttributes( $attributes = array() )
	{
		$this->attributes	= array(
			'class'	=> array()
		);
		$this->addAttributes( $attributes );
	}

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
	
	public function setContent( $content )
	{
		$this->content	= $content;
	}
}
?>