<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		ui.html
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		0.1
 */
import( 'de.ceus-media.ui.html.Tag' );
import( 'de.ceus-media.ui.html.JQuery' );
/**
 *	...
 *	@category		cmClasses
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		0.1
 */
class UI_HTML_ContextMenu
{

	public static function buildCode( $context, $content, $id = NULL )
	{
		$label		= UI_HTML_Tag::create( 'div', $context, array( 'class' => 'label' ) );
		$opener		= UI_HTML_Tag::create( 'div', UI_HTML_Tag::create( 'span', '&nabla;' ), array( 'class' => 'opener' ) );
		$options	= UI_HTML_Tag::create( 'div', $content, array( 'class' => 'contextMenu', 'id' => $id ) );
		$html		= UI_HTML_Tag::create( 'div', $label.$opener.$options, array( 'class' => 'cmContextMenu' ) );
		return $html;
	}
	
	public static function buildScript( $selector )
	{
		return UI_HTML_JQuery::buildPluginCall( 'cmContextMenu', $selector );
	}
	
}
?>