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
class UI_HTML_Layout_Site extends UI_HTML_Element_Abstract
{
	protected $aTop				= array();
	protected $aBottom			= array();
	protected $aLeft			= array();
	protected $aRight			= array();
	protected $aHeader			= array();
	protected $aTopNavigation	= array();
	protected $aHeadNavigation	= array();
	protected $aMainNavigation	= array();
	protected $aControl			= array();
	protected $aContent			= array();
	protected $aExtra			= array();
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


	public function render()
	{
		$sTop				= $this->renderPart( 'aTop' );
		$sHeader			= $this->renderPart( 'aHeader' );
		$sTopNavigation		= $this->renderPart( 'aTopNavigation' );
		$sHeadNavigation	= $this->renderPart( 'aHeadNavigation' );
		$sMainNavigation	= $this->renderPart( 'aMainNavigation' );
		$sControl			= $this->renderPart( 'aControl' );
		$sContent			= $this->renderPart( 'aContent' );
		$sExtra				= $this->renderPart( 'aExtra' );
		$sFooter			= $this->renderPart( 'aFooter' );
		$sBottom			= $this->renderPart( 'aFooter' );

		$aDivs	= array();
		if( $this->aTopNavigation )
			$aDivs[]	= $this->renderTag( 'div', $sTopNavigation, array( 'id' => 'layout-top-nav' ) );
		if( $this->aHeader )
		{
			if( $this->aHeadNavigation )
				$sHeader	= $this->renderTag( 'div', $sHeadNavigation, array( 'id' => 'layout-header-nav' ) ).$sHeader;
			$aDivs[]		= $this->renderTag( 'div', $sHeader, array( 'id' => 'layout-header' ) );
		}
		if( $this->aMainNavigation )
			$aDivs[]	= $this->renderTag( 'div', $sMainNavigation, array( 'id' => 'layout-main-navigation' ) );
		if( $this->aControl )
			$aDivs[]	= $this->renderTag( 'div', $sControl, array( 'id' => 'layout-control' ) );
		if( $this->aContent )
			$aDivs[]	= $this->renderTag( 'div', $sContent, array( 'id' => 'layout-content' ) );
		if( $this->aExtra )
			$aDivs[]	= $this->renderTag( 'div', $sExtra, array( 'id' => 'layout-extra' ) );
		if( $this->aFooter )
			$aDivs[]	= $this->renderTag( 'div', $sFooter, array( 'id' => 'layout-footer' ) );
	
		$sAll				= join( $aDivs );
		$sDivContainer		= $this->renderTag( 'div', $sAll, array( 'id' => 'layout-page' ) );
		return $sDivContainer;
	}

	protected function renderPart( $key )
	{
		$$key	= array();
		if( !$this->$key )
			return;

		foreach( $this->$key as $mContent )
		{
			if( $mContent instanceof UI_HTML_Element_Abstract )
				$mContent	= $mContent->render();
			${$key}[]	= $mContent;
		}
		return join( $$key );
	}

}
?>