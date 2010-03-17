<?php
class UI_HTML_OrderedList extends UI_HTML_Abstract
{
	public function __construct( $items = NULL, $attributes = NULL )
	{
		if( !is_null( $items ) )
			$this->addItems( $items );
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
	}
	
	public function addItem( $item )
	{
		$this->listItems[]	= $item;
	}
	
	public function addItems( $items )
	{
		if( $items instanceof UI_HTML_Buffer ) 
			$this->addItem( $items->render() );
		else
			foreach( $items as $item )
				$this->addItem( $item );
	}

	public function render()
	{
		$list	= array();
		foreach( $this->listItems as $item )
			$list[]	= $this->renderInner( $item );
		return UI_HTML_Tag::create( "ol", join( $list ), $this->getAttributes() );
	}
}
?>