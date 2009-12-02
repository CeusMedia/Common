<?php
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.ui.html.css.LinkSelect' );
import( 'de.ceus-media.ui.html.CountryFlagIcon' );

class UI_HTML_CSS_LanguageSwitch
{
	protected $languages	= array();
	
	public function setLanguages( $array )
	{
		$this->languages	= $array;
	}

	protected static function getFlag( $isoCode )
	{
		return UI_HTML_CountryFlagIcon::build( $isoCode );
	}

	public function build( $currentLanguage )
	{
		$list	= new ADT_Tree_Menu_List();
		$icon	= self::getFlag( $currentLanguage );
		$label	= $this->languages[$currentLanguage];
		$span	= UI_HTML_Tag::create( "span", $icon, array( 'class' => "flagIcon" ) );
		$main	= new ADT_Tree_Menu_Item( "#", $span.$label );
		$list->addChild( $main );

		foreach( $this->languages as $languageKey => $languageLabel )
		{
			if( $languageKey == $currentLanguage )
				continue;
			$icon	= self::getFlag( $languageKey );
			$span	= UI_HTML_Tag::create( "span", $icon, array( 'class' => "flagIcon" ) );
			$item	= new ADT_Tree_Menu_Item( "?language=".$languageKey, $span.$languageLabel );
			$main->addChild( $item );
		}
		$code	= UI_HTML_Tree_Menu::buildMenu( $list );
		$code	= UI_HTML_Tag::create( "span", $code, array( 'class' => "menu select" ) );
		return $code;
	}
}
?>