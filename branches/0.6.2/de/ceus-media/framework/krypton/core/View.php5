<?php
import( 'de.ceus-media.framework.krypton.core.Component' );
/**
 *	Basic View Component.
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			UI_HTML_Paging
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
 */
/**
 *	Basic View Component.
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			UI_HTML_Paging
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
 */
class Framework_Krypton_Core_View extends Framework_Krypton_Core_Component
{
	public static $baseUrl	= "index.php5";
	
	/**
	 *	Constructor, references Output Objects.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $useWikiParser = false )
	{
		parent::__construct( $useWikiParser );
	}
	
	/**
	 *	Abstract Method for Content Views.
	 *	@access		public
	 *	@return		string
	 */
	public function buildContent()
	{
		return "";
	}
	
	/**
	 *	Abstract Method for Control Views.
	 *	@access		public
	 *	@return		string
	 */
	public function buildControl()
	{
		return "";
	}

	/**
	 *	Abstract Method for Content Views in Extra Column.
	 *	@access		public
	 *	@return		string
	 */
	public function buildExtra()
	{
		return "";
	}

	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int			$numberTotal	Total mount of total entries
	 *	@param		int			$rowLimit		Number of displayed Rows
	 *	@param		int			$rowOffset		Number of of skipped Rows
	 *	@param		array		$optionMap		Array of Options to set
	 *	@return		string
	 */
	public function buildPaging( $numberTotal, $rowLimit, $rowOffset, $optionMap = array())
	{
		import( 'de.ceus-media.ui.html.Paging' );
		$request	= Framework_Krypton_Core_Registry::getStatic( "request" );
		$link		= $request->get( 'link');
		
		$paging			= new UI_HTML_Paging;
		$paging->setOption( 'uri', self::$baseUrl );
		$paging->setOption( 'param', array( 'link'	=> $link ) );
		$paging->setOption( 'indent', "" );
		$paging->setOption( 'class_link', 'paging' );
		$paging->setOption( 'class_span', 'paging' );
		$paging->setOption( 'class_text', 'text' );

		foreach( $optionMap as $key => $value )
		{
			if( !$paging->hasOption( $key ) )
				throw new InvalidArgumentException( 'Option "'.$key.'" is not a valid Paging Option.' );
			$paging->setOption( $key, $value );
		}

		if( isset( $this->words['main']['paging'] ) )
		{
			$words		= $this->words['main']['paging'];
			if( isset( $words['first'] ) )
				$paging->setOption( 'text_first', $words['first'] );
			if( isset( $words['previous'] ) )
				$paging->setOption( 'text_previous', $words['previous'] );
			if( isset( $words['next'] ) )
				$paging->setOption( 'text_next', $words['next'] );
			if( isset( $words['last'] ) )
				$paging->setOption( 'text_last', $words['last'] );
			if( isset( $words['more'] ) )
				$paging->setOption( 'text_more', $words['more'] );
		}

		$pages	= $paging->build( $numberTotal, $rowLimit, $rowOffset );
		return $pages;
	}
}
?>