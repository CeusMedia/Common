<?php
require_once( "cmClasses/trunk/useClasses.php5" ); 
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
class LanguageSwitch3
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

	public function build( $baseUrl, $currentLanguage, $class )
	{
		$list	= array();
		foreach( $this->languages as $languageKey => $languageLabel )
		{
			$icon	= self::getFlag( $languageKey );
			$label	= $this->languages[$languageKey];
			$span	= UI_HTML_Tag::create( "span", $icon, array( 'class' => "flagIcon" ) );

			$list[]	= array(
				'key'	=> $languageKey,
				'label'	=> $span.$languageLabel,
				'url'	=> $baseUrl.$languageKey
			);
		}
		return UI_HTML_CSS_LinkSelect::build( "language", $list, $currentLanguage, $class );
	}
}

require_once '../LanguageSwitch/languages.php5';
$currentLanguage	= isset( $_GET['language'] ) ? $_GET['language'] : "de";
$currentTheme		= isset( $_GET['theme'] ) ? $_GET['theme'] : "menu.simple.css";
$currentTest		= isset( $_GET['test'] ) ? $_GET['test'] : NULL;
$switch		= new LanguageSwitch3();
$switch->setLanguages( $labels[$currentLanguage] );
$html		= $switch->build( "./?theme=".$currentTheme."&test=".$currentTest."&language=", $currentLanguage, "menu select" );

$code	= require_once( "template.phpt" );
echo $code;
die;
?>