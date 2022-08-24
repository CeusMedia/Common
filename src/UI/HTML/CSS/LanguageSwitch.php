<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */

namespace CeusMedia\Common\UI\HTML\CSS;

use CeusMedia\Common\ADT\Tree\Menu\Collection as MenuCollection;
use CeusMedia\Common\ADT\Tree\Menu\Item as MenuItem;
use CeusMedia\Common\UI\HTML\CountryFlagIcon;
use CeusMedia\Common\UI\HTML\Tag;
use CeusMedia\Common\UI\HTML\Tree\Menu as HtmlTreeMenu;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
class LanguageSwitch
{
	protected $languages	= [];

	public function build( $currentLanguage )
	{
		$list	= new MenuCollection();
		$icon	= self::getFlag( $currentLanguage );
		$label	= $this->languages[$currentLanguage];
		$span	= Tag::create( "span", $icon, array( 'class' => "flagIcon" ) );
		$main	= new MenuItem( "#", $span.$label );
		$list->addChild( $main );

		foreach( $this->languages as $languageKey => $languageLabel )
		{
			if( $languageKey == $currentLanguage )
				continue;
			$icon	= self::getFlag( $languageKey );
			$span	= Tag::create( "span", $icon, array( 'class' => "flagIcon" ) );
			$item	= new MenuItem( "?language=".$languageKey, $span.$languageLabel );
			$main->addChild( $item );
		}
		$code	= HtmlTreeMenu::buildMenu( $list );
		$code	= Tag::create( "span", $code, array( 'class' => "menu select" ) );
		return $code;
	}

	protected static function getFlag( $isoCode )
	{
		return CountryFlagIcon::build( $isoCode );
	}

	public function setLanguages( $array )
	{
		$this->languages	= $array;
	}
}
