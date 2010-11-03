<?php
/**
 *	Liquid Grid Layout.
 *	@category		cmClasses
 *	@package		UI.HTML.Layout
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Liquid Grid Layout.
 *	@category		cmClasses
 *	@package		UI.HTML.Layout
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Layout_LiquidGrid extends UI_HTML_Element_Abstract
{
	private $elements							= array();
	protected static $defaultClassGrid			= 'layout-liquid-grid';
	protected static $defaultClassCell			= 'layout-liquid-grid-cell';

	/**
	 *	Constructor, sets Number of Rows and Columns and calculates Column Width.
	 *	@access		public
	 *	@param		int			$iRows			Number of Rows
	 *	@param		int			$iColumns		Number of Columns
	 *	@return		void
	 */
	public function __construct()
	{
		$this->setClass( self::$defaultClassGrid );
	}

	/**
	 *	Adds another Element to Grid.
	 *	@access		public
	 *	@param		mixed		$element		String or Instance of UI_HTML_Element_Abstract
	 *	@return		void
	 *	@throws		InvalidArgumentException
	 */
	public function add( $element )
	{
		if( !( $element instanceof UI_HTML_Element_Abstract || is_string( $element ) ) )
			throw new InvalidArgumentException( 'Must be String or extend UI_HTML_Element_Abstract' );
		$this->elements[]	= $element;
	}

	/**
	 *	...
	 *	@access		public
	 *	@return		string			Rendered Grid Layout
	 */
	public function render()
	{
		$list	= array();
		foreach( $this->elements as $element )
		{
			if( $element instanceof UI_HTML_Element_Abstract )
				$element	= $element->render();
			$attributes	= array( 'class' => self::$defaultClassCell );
			$list[]		= $this->renderTag( 'div', "\n    ".$element."\n  ", $attributes );
		}
		$list		= "\n  ".join( "\n  ", $list )."\n";
		$attributes	= array( 'class' => $this->class );
		$content	= $this->renderTag( 'div', $list, $attributes );
		$commentIn	= '<!--  Layout: Liquid Grid > -->';
		$commentOut	= '<!--  < Layout: Liquid Grid  -->';
		return $commentIn."\n".$content."\n".$commentOut."\n";
	}
}
?>