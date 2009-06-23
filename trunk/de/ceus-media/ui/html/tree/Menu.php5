<?php
/**
 *	Builder for Tree Menu.
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
 *	@package		ui.html.tree
 *	@uses			Alg_Tree_Menu_Converter
 *	@uses			UI_HTML_Tag
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		0.1
 */
import( 'de.ceus-media.alg.tree.menu.Converter' );
import( 'de.ceus-media.ui.html.Tag' );
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Builder for Tree Menu.
 *	@package		ui.html.tree
 *	@uses			Alg_Tree_Menu_Converter
 *	@uses			UI_HTML_Tag
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		0.1
 */
class UI_HTML_Tree_Menu
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$rootId			ID Tree Root
	 *	@param		string		$rootLabel		Label of Tree Root
	 *	@return		void
	 */
	public function __construct()
	{
	}

	/**
	 *	Builds Layer Menu from OPML File.
	 *	@access		public
	 *	@param		string		$fileName		URL of OPML File
	 *	@return
	 */
	public function buildMenuFromOpmlFile( $fileName )
	{
		$list	= Alg_Tree_Menu_Converter::convertFromOpmlFile( $fileName, NULL );
		return $this->buildMenuFromMenuList( $list );
	}
	
	/**
	 *	Builds Layer Menu from OPML String.
	 *	@access		public
	 *	@param		string		$opml			OPML String
	 *	@return
	 */
	public function buildMenuFromOpml( $opml )
	{
		$list		= Alg_Tree_Menu_Converter::convertFromOpml( $opml, $this->rootLabel );
		return $this->buildMenuFromMenuList( $list );
	}

	/**
	 *	Builds Layer Menu from Tree Menu Structure.
	 *	@access		public
	 *	@param		ADT_Tree_Menu_List	$list	Tree Menu Structure
	 *	@return
	 */
	public function buildMenuFromMenuList( ADT_Tree_Menu_List $list )
	{
		$tree		= self::buildMenuRecursive( $list );
		$code		= UI_HTML_Tag::create( 'div', $tree, array( 'class' => 'cmTreeMenu' ) );
		return $code;
	}

	/**
	 *	Builds Tree Menu from Tree Menu Structure.
	 *	@access		protected
	 *	@static
	 *	@param		ADT_Tree_Menu_List	$list	Tree Menu Structure
	 *	@param		string				$parent	ID of parent Node
	 *	@param		array				$steps	List of Steps in Tree
	 *	@param		int					$level	Depth Level of Tree
	 *	@return		string
	 */
	protected static function buildMenuRecursive( ADT_Tree_Menu_List $tree, $level = 1 )
	{
		$list	= array();
		foreach( $tree->getChildren() as $child )
		{
			$class		= $child->getAttributes()->get( 'class' );
			$label		= $child->label;
			if( !empty( $child->url ) )
				$label	= UI_HTML_Elements::Link( $child->url, $child->label, $class );
			else
				$label	= UI_HTML_Tag::create( 'span', $child->label );

			$sublist	= "";
			if( $child->hasChildren() )
				$sublist	= "\n".self::buildMenuRecursive( $child, $level+1 );
			$classes	= array( 'level-'.$level );
			if( $child->hasChildren() )
				$classes[]	= "parent";
			$class		= implode( " ", $classes );
			$list[]		= UI_HTML_Elements::ListItem( $label.$sublist, $level, array( 'class' => $class ) );
		}
		$list	= UI_HTML_Elements::unorderedList( $list, $level );
		return $list;
	}
}
?>