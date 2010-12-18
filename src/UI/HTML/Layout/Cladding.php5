<?php
/**
 *	Grid Layout.
 *	@category		cmClasses
 *	@package		UI.HTML.Layout
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@version		$Id$
 */
/**
 *	Grid Layout.
 *	@category		cmClasses
 *	@package		UI.HTML.Layout
 *	@extends		UI_HTML_Element_Abstract
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@version		$Id$
 */
class UI_HTML_Layout_Cladding extends UI_HTML_Element_Abstract
{
	public $bottom		= NULL;
	public $content		= NULL;
	public $left		= NULL;
	public $right		= NULL;
	public $top			= NULL;
	
	/**
	 *	Constructor, sets Number of Rows and Columns and calculates Column Width.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->setClass( 'layout-cladding' );
		
		$this->bottom	= new UI_HTML_Element_Div();
		$this->bottom->setClass( 'layout-cladding-bottom' );

		$this->content	= new UI_HTML_Element_Div();
		$this->content->setClass( 'layout-cladding-content' );

		$this->left	= new UI_HTML_Element_Div();
		$this->left->setClass( 'layout-cladding-left' );

		$this->right	= new UI_HTML_Element_Div();
		$this->right->setClass( 'layout-cladding-right' );

		$this->top		= new UI_HTML_Element_Div();
		$this->top->setClass( 'layout-cladding-top' );
	}

	public function addBottom( $content )
	{
		$this->top->addContent( $content );
	}

	public function addContent( $content )
	{
		$this->content->addContent( $content );
	}

	public function addLeft( $content )
	{
		$this->left->addContent( $content );
	}

	public function addRight( $content )
	{
		$this->right->addContent( $content );
	}

	public function addTop( $content )
	{
		$this->top->addContent( $content );
	}

	public function render()
	{
		$divBottom	= $this->bottom->render();
		$divContent	= $this->content->render();
		$divLeft	= $this->left->render();
		$divRight	= $this->right->render();
		$divTop		= $this->top->render();
	
		$inner		= "\n    ".$divLeft."\n    ".$divContent."\n    ".$divRight."\n  ";
		$inner		= $this->renderTag( 'div', $inner, array( 'style' => 'display: table-row' ) );
		$all		= "\n  ".$divTop."\n  ".$inner."\n  ".$divBottom."\n";
		$attributes	= array(
			'id'	=> $this->id,
			'class'	=> $this->class,
		);
		$content	= $this->renderTag( 'div', $all, $attributes );
		$commentIn	= '<!--  Layout: Cladding > -->';
		$commentOut	= '<!--  < Layout: Cladding  -->';
		return $commentIn."\n".$content."\n".$commentOut."\n";
	}
}
?>