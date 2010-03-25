<?php
/**
 *	...
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		ui.html.css
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.ui.html.css.LinkSelect' );
import( 'de.ceus-media.ui.html.CountryFlagIcon' );
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		ui.html.css
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
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