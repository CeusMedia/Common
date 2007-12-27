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
	/**
	 *	Constructor, references Output Objects.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $useWikiParser = false )
	{
		parent::__construct( $useWikiParser );
	}
	
	public function buildContent()
	{
		return "";
	}
	
	public function buildControl()
	{
		return "";
	}

	public function buildExtra()
	{
		return "";
	}

	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int		$count_all		Total mount of total entries
	 *	@param		int		$limit			Maximal amount of displayed entries
	 *	@param		int		$offset			Currently offset entries
	 *	@param		array	$options		Array of Options to set
	 *	@return		string
	 */
	public function buildPaging( $count_all, $limit, $offset, $options = array())
	{
		import( 'de.ceus-media.ui.html.Paging' );
		$request	= Framework_Krypton_Core_Registry::getStatic( "request" );
		$link		= $request->get( 'link');
		$words		= $this->words['main']['paging'];
		
		$paging			= new UI_HTML_Paging;
		$paging->setOption( 'uri', "index.php5" );
		$paging->setOption( 'param', array( 'link'	=> $link ) );
		$paging->setOption( 'indent', "" );

		foreach( $options as $key => $value )
			$paging->setOption( $key, $value );

		$paging->setOption( 'text_first', $words['first'] );
		$paging->setOption( 'text_previous', $words['previous'] );
		$paging->setOption( 'text_next', $words['next'] );
		$paging->setOption( 'text_last', $words['last'] );
		$paging->setOption( 'text_more', $words['more'] );

		$pages	= $paging->build( $count_all, $limit, $offset );
		return $pages;
	}
}
?>