<?php
/**
 *	Basic User Interface Views, to be extended with UI Blocks.
 *	@package		framework.krypton.view
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
import( 'de.ceus-media.framework.krypton.core.View' );
/**
 *	Basic User Interface Views, to be extended with UI Blocks.
 *	@package		framework.krypton.view
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
class Framework_Krypton_View_Interface extends Framework_Krypton_Core_View
{
	/** @var	array		$ui			User Interface Data */
	protected $ui	= array();

	/**
	 *	Builds Dev Center View.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildDevCenter( $content )
	{
		import( 'de.ceus-media.framework.krypton.view.component.DevCenter' );
		$view	= new Framework_Krypton_View_Component_DevCenter();
		return $view->buildContent( $content );
	}

	/**
	 *	Builds Sub Header View.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildLanguageSwitch()
	{
		$config		= $this->registry->get( 'config' );
		$request	= $this->registry->get( 'request' );
		$language	= $this->registry->get( 'language' );
		$words		= $this->words['main']['switchLanguage'];

		$allowed	= $language->getAllowedLanguages();
		$current	= $language->getLanguage();

		if( count( $allowed ) < 2 )
			return "";
		$list		= array();
		foreach( $allowed as $languageKey )
		{
			$label	= $this->words['main']['languages'][$languageKey];
			$icon	= UI_HTML_Elements::Image( $config['paths.icons']."flags/".$languageKey.".png", $label );
			if( $languageKey != $current )
				$icon	= UI_HTML_Elements::Link( "?link=".$request->get( 'link' )."&switchLanguageTo=".$languageKey, $icon );
			if( $languageKey == $current )
				continue;
			$list[]	= UI_HTML_Elements::ListItem( $icon );
		}
		$ui['list']		= UI_HTML_Elements::unorderedList( $list );
		$ui['heading']	= $words['heading'];
		$ui['caption']	= UI_HTML_Elements::TableCaption( $words['caption'], 'list' );
		return $this->loadTemplate( 'interface.languages', $ui );
	}

	/**
	 *	Builds MetaTag Component for HTML Page using Meta Information from Main Language File, Section 'meta'.
	 *  Also builds automated LogOut if Options are set.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildMetaTags()
	{
		$config		= $this->registry->get( 'config' );
		$auth		= $this->registry->get( 'auth' );
		$words		= $this->registry->get( "words" );
		$ui			= $this->getUserInterfaceData();
		
		$ui['meta']		= $config['metatags'];
		foreach( $words['main']['meta'] as $key => $value )
			if( $value )
				$ui['meta'][$key]	= $value;
		$ui['date']		= date( "c" );
		$ui['referrer']	= getEnv( "HTTP_REFERER" );

		return $this->loadTemplate( 'interface.metatags', $ui );
	}

	/**
	 *	Builds No Script Screen.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildNoScript()
	{
		return $this->loadTemplate( 'interface.noscript' );	
	}

	/**
	 *	Builds JavaScript Link Component.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildScriptLinks()
	{
		return $this->loadTemplate( 'interface.scripts', $this->getUserInterfaceData() );
	}

	/**
	 *	Builds StyleSheet Link Component.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildStyleLinks()
	{
		return $this->loadTemplate( 'interface.styles', $this->getUserInterfaceData() );
	}
	
	/**
	 *	Builds Theme Switch.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildThemeSwitch()
	{
		$config		= $this->registry->get( 'config' );
		$request	= $this->registry->get( 'request' );
		$language	= $this->registry->get( 'language' );
		$words		= $this->words['main']['switchTheme'];

		$current	= $config['layout.theme'];
		$list		= array();
		$dir		= new DirectoryIterator( $config['paths.themes'] );
		foreach( $dir as $entry )
		{
			$theme	= $entry->getFilename();
			if( !( !$entry->isDot() && substr( $theme, 0, 1 ) != "." ) )
				continue;
			$item	= $theme;
			if( $current != $theme )
				$item	= UI_HTML_Elements::Link( "?link=".$request->get( 'link' )."&switchThemeTo=".$theme, $theme );
			$list[]	= UI_HTML_Elements::ListItem( $item );
		}
		if( count( $list ) < 2 )
			return "";
		$ui['list']		= UI_HTML_Elements::unorderedList( $list );
		$ui['heading']	= $words['heading'];
		$ui['caption']	= UI_HTML_Elements::TableCaption( $words['caption'], 'list' );
		return $this->loadTemplate( 'interface.themes', $ui );
	}

	/**
	 *	Builds and returns all Data for User Interface Templates.
	 *	@access		protected
	 *	@return		array
	 */
	protected function getUserInterfaceData()
	{
		if( !$this->ui )
		{
			$config		= $this->registry->get( "config" );
			$session	= $this->registry->get( "session" );
			$language	= $this->registry->get( "language" );

			$cssCompressionSuffix	= "";
			if( isset( $config['compression'][$config['layout.theme']] ) )
				if( $config['compression'][$config['layout.theme']] )
					$cssCompressionSuffix	= $config['layout.compression'];

/*			$this->ui['layout']				= $config['layout.style'];*/
			$this->ui['path_js']			= $config['paths.javascripts'];
			$this->ui['path_js_lib']		= max( $config['paths.javascript_library'], $config['paths.javascript.library'] );
			$this->ui['path_css']			= $config['paths.themes'].$config['layout.theme']."/css/";
			$this->ui['css_compression']	= $cssCompressionSuffix;
			$this->ui['language']			= $language->getLanguage();
			$this->ui["config"]				= $config->getAll();
		}
		return $this->ui;
	}
	
	public function setDescriptionByLink( $link )
	{
		if( isset( $this->words['main']['descriptions'][$link] ) )
			$this->setDescription( $this->words['main']['descriptions'][$link] );
	}

	public function setTitleByLink( $link )
	{
		if( isset( $this->words['main']['titles'][$link] ) )
			$this->setTitle( $this->words['main']['titles'][$link] );
	}
	
	public function setKeywordsByLink( $link )
	{
		if( isset( $this->words['main']['keywords'][$link] ) )
			$this->setKeywords( $this->words['main']['keywords'][$link] );
	}
}
?>