<?php
/**
 *	Grid Layout.
 *	@category		cmClasses
 *	@package		ui.html.layout
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Grid Layout.
 *	@category		cmClasses
 *	@package		ui.html.layout
 *	@extends		UI_HTML_Element_Abstract
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Layout_Grid extends UI_HTML_Element_Abstract
{
	private $elements					= array();
	private $numberRows					= 0;
	private $numberColumns				= 0;
	private $currentRow					= 0;
	private $currentColumn				= 0;
	private $width						= 0;
	public static $defaultClassTable	= 'layout-grid-table';
	public static $defaultClassRow		= 'layout-grid-row';
	public static $defaultClassCell		= 'layout-grid-cell';

	/**
	 *	Constructor, sets Number of Rows and Columns and calculates Column Width.
	 *	@access		public
	 *	@param		int			$numberRows			Number of Rows
	 *	@param		int			$numberColumns		Number of Columns
	 *	@return		void
	 */
	public function __construct( $numberRows = 1, $numberColumns = 2 )
	{
		if( !is_int( $numberRows ) )
			throw new InvalidArgumentException( 'Rows must be integer' );
		if( !is_int( $numberColumns ))
			throw new InvalidArgumentException( 'Columsn must be integer' );

		$this->numberRows		= $numberRows;
		$this->numberColumns	= $numberColumns;
		$this->currentColumn	= 0;
		$this->currentRow		= 0;
		$this->width			= floor( 100 / $this->numberColumns );
		$this->setClass( self::$defaultClassTable );
	}

	/**
	 *	Adds another Element to Grid.
	 *	@access		public
	 *	@param		mixed		$mElement		String or Instance of UI_HTML_Element_Abstract
	 *	@return		void
	 *	@throws		InvalidArgumentException
	 *	@throws		OutOfBoundsException
	 */
	public function add( $mElement )
	{
		if( !( $mElement instanceof UI_HTML_Element_Abstract || is_string( $mElement ) ) )
			throw new InvalidArgumentException( 'Must be String or extend UI_HTML_Element_Abstract' );

		if( $this->currentColumn > $this->numberColumns - 1 )
		{
			$this->currentColumn	= 0;
			$this->currentRow++;
		}

		if( $this->currentRow > $this->numberRows - 1 )
			throw new OutOfBoundsException( 'No space left in Container' );
	
		$this->elements[$this->currentRow][$this->currentColumn] = $mElement;
		$this->currentColumn++;
	}

	/**
	 *	...
	 *	@access		public
	 *	@return		string			Rendered Grid Layout
	 */
	public function render()
	{
		$list	= array();
		for( $row=0; $row<$this->numberRows; $row++ )
		{
			$innerList	= array();
			for( $column=0; $column<$this->numberColumns; $column++ )
			{
				if( !isset( $this->elements[$row][$column] ) )
					break;
				$element		= $this->elements[$row][$column];
				if( $element instanceof UI_HTML_Element_Abstract )
					$element	= $element->render();
				$attributes	= array( 'class' => self::$defaultClassCell );
				$innerList[]	= "\n    ".$this->renderTag( 'div', "\n      ".$element."\n    ", $attributes );
			}
			$innerList	= join( $innerList )."\n  ";
			$attributes	= array( 'class' => self::$defaultClassRow );
			$list[]		= "\n  ".$this->renderTag( 'div', $innerList, $attributes );
		}
		$list			= join( $list )."\n";
		
		$attributes	= array( 'class'	=> $this->class );
		$grid		= $this->renderTag( 'div', $list, $attributes );
		$commentIn	= '<!--  Layout: Grid > -->';
		$commentOut	= '<!--  < Layout: Grid  -->';
		return $commentIn."\n".$grid."\n".$commentOut."\n";
	}
}
?>