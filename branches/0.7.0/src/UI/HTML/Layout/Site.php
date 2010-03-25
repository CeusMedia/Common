<?php
/**
 *	Grid Layout.
 *	@category		cmClasses
 *	@package		ui.html.layout
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@version		$Id$
 */
/**
 *	Grid Layout.
 *	@category		cmClasses
 *	@package		ui.html.layout
 *	@extends		UI_HTML_Element_Abstract
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@version		$Id$
 */
class UI_HTML_Layout_Site extends UI_HTML_Element_Abstract
{
	protected $aTop				= array();
	protected $aBottom			= array();
	protected $aLeft			= array();
	protected $aRight			= array();
	protected $aHeader			= array();
	protected $aMainNavigation	= array();
	protected $aControl			= array();
	protected $aContent			= array();
	protected $aExta			= array();
	protected $aFooter			= array();
	
	/**
	 *	Constructor, sets Number of Rows and Columns and calculates Column Width.
	 *	@access		public
	 *	@param		int			$iRows			Number of Rows
	 *	@param		int			$iColumns		Number of Columns
	 *	@return		void
	 */
	public function __construct()
	{
	}

	public function addHeader( $mContent )
	{
		$this->aHeader[]	= $mContent;
	}

	public function addHeadNavigation( $mContent )
	{
		$this->aHeadNavigation[]	= $mContent;
	}

	public function addTopNavigation( $mContent )
	{
		$this->aTopNavigation[]	= $mContent;
	}

	public function addMainNavigation( $mContent )
	{
		$this->aMainNavigation[]	= $mContent;
	}

	public function addControl( $mContent )
	{
		$this->aControl[]	= $mContent;
	}

	public function addContent( $mContent )
	{
		$this->aContent[]	= $mContent;
	}

	public function addExtra( $mContent )
	{
		$this->aExtra[]	= $mContent;
	}

	public function addFooter( $mContent )
	{
		$this->aFooter[]	= $mContent;
	}

/*
	public function render()
	{
		$sTop			= $this->renderPart( 'aTop' );
		$sHeader		= $this->renderPart( 'aHeader' );
		$sNavigation	= $this->renderPart( 'aNavigation' );
		$sControl		= $this->renderPart( 'aControl' );
		$sContent		= $this->renderPart( 'aContent' );
		$sExtra			= $this->renderPart( 'aExtra' );
		$sFooter		= $this->renderPart( 'aFooter' );
		$sBottom		= $this->renderPart( 'aFooter' );
		
		$sDivTopNavigation	= $this->renderTag( 'div', $sTopNavigation, array( 'id' => 'layout-top-navigation' ) );
		$sDivHeadNavigation	= $this->renderTag( 'div', $sHeadNavigation, array( 'id' => 'layout-head-navigation' ) );
		$sDivMainNavigation	= $this->renderTag( 'div', $sMainNavigation, array( 'id' => 'layout-main-navigation' ) );
		$sDivHeader			= $this->renderTag( 'div', $sHeader, array( 'id' => 'layout-header' ) );
		$sDivControl		= $this->renderTag( 'div', $sControl, array( 'id' => 'layout-control' ) );
		$sDivContent		= $this->renderTag( 'div', $sContent, array( 'id' => 'layout-content' ) );
		$sDivExtra			= $this->renderTag( 'div', $sExtra, array( 'id' => 'layout-extra' ) );
		$sDivFooter			= $this->renderTag( 'div', $sFooter, array( 'id' => 'layout-footer' ) );
	
		$sAll				= $sDivTopNavigation.$sDivHeadNavigation.
		$sDivContainer		= $this->renderTag( 'div', 
	}

	protected renderPart( $key )
	{
		$$key	= array();
		foreach( $this->$key as $mContent )
		{
			if( $mContent instanceof UI_HTML_Element_Abstract )
				$mContent	= $mContent->render();
			$$key[]	= $mContent;
		}
		return join( $$key );
	}
*/

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

		if( $this->iCurrentColumn > $this->iColumns - 1 )
		{
			$this->iCurrentColumn	= 0;
			$this->iCurrentRow++;
		}

		if( $this->iCurrentRow > $this->iRows - 1 )
			throw new OutOfBoundsException( 'No space left in Container' );
	
		$this->aElements[$this->iCurrentRow][$this->iCurrentColumn] = $mElement;
		$this->iCurrentColumn++;
	}

	/**
	 *	...
	 *	@access		public
	 *	@return		string			Rendered Grid Layout
	 */
	public function render()
	{
		$aList	= array();
		for( $iRow=0; $iRow<$this->iRows; $iRow++ )
		{
			$aInnerList	= array();
			for( $iColumn=0; $iColumn<$this->iColumns; $iColumn++ )
			{
				if( !isset( $this->aElements[$iRow][$iColumn] ) )
					break;
				$mElement		= $this->aElements[$iRow][$iColumn];
				if( $mElement instanceof UI_HTML_Element_Abstract )
					$mElement	= $mElement->render();
				$aAttributes	= array( 'class' => self::$sDefaultClassCell );
				$aInnerList[]	= $this->renderTag( 'div', $mElement, $aAttributes );
			}
			$sInnerList		= join( $aInnerList );
			$aAttributes	= array( 'class' => self::$sDefaultClassRow );
			$aList[]		= $this->renderTag( 'div', $sInnerList, $aAttributes );
		}
		$sList			= join( $aList );
		$aAttributes	= array( 'class'	=> $this->sClass );
		return $this->renderTag( 'div', $sList, $aAttributes );
	}
}
?>