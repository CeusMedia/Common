<?php
/**
 *	Builder for Layer Menu.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.2
 */
/**
 *	Builder for Layer Menu.
 *	@category		cmClasses
 *	@package		UI.HTML.Tree
 *	@uses			Alg_Tree_Menu_Converter
 *	@uses			UI_HTML_Tag
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.2
 */
class UI_HTML_Tree_LayerMenu
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$rootId			ID Tree Root
	 *	@param		string		$rootLabel		Label of Tree Root
	 *	@return		void
	 */
	public function __construct( $rootId, $rootLabel )
	{
		$this->rootId		= $rootId;
		$this->rootLabel	= $rootLabel;
	}

	/**
	 *	Builds Layer Menu from OPML File.
	 *	@access		public
	 *	@param		string		$fileName		URL of OPML File
	 *	@return
	 */
	public function buildMenuFromOpmlFile( $fileName )
	{
		$list	= Alg_Tree_Menu_Converter::convertFromOpmlFile( $fileName, $this->rootLabel );
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
		$root	= array(
			array(
				'id' 	=> $this->rootId,
				'level' => 0,
				'label' => $this->rootLabel
			)
		);
		return self::buildLayersRecursive( $list, $this->rootId, $root );
	}

	/**
	 *	Builds Layer Menu from Tree Menu Structure.
	 *	@access		protected
	 *	@static
	 *	@param		ADT_Tree_Menu_List	$list	Tree Menu Structure
	 *	@param		string				$parent	ID of parent Node
	 *	@param		array				$steps	List of Steps in Tree
	 *	@param		int					$level	Depth Level of Tree
	 *	@return		string
	 */
	protected static function buildLayersRecursive( ADT_Tree_Menu_List $tree, $parent, $steps = array(), $level = 0 )
	{
		$backlinks	= "";
		if( count( $steps ) > 1 )
		{
			$backlinks	= array();
			for( $i=1; $i<count( $steps ); $i++ )
			{
				$step			= $steps[$i-1];
				$label			= UI_HTML_Tag::create( "span", $step['label'] );
				$attributes		= array(
					'class'		=> "level".$step['level'],
					'onclick'	=> "stepOutTo('".$step['id']."');"
				);
				$backlinks[]	= UI_HTML_Elements::ListItem( $label, $level, $attributes );
			}
			$backlinks	= implode( "\n", $backlinks );
			$backlinks	= UI_HTML_Tag::create( "ol", $backlinks, array( 'class' => "back" ) );
		}

		$list		= array();
		foreach( $tree->getChildren() as $id => $item )
		{
			if( $item->hasChildren() )
			{
				$label	= UI_HTML_Tag::create( "span", $item->label );
				$attributes		= array(
					'class'		=> "parent",
					'onclick'	=> "stepInTo('".$parent."_".$id."');"
				);
				$list[]	= UI_HTML_Elements::ListItem( $label, $level, $attributes );
			}
			else
			{
				$link	= UI_HTML_Elements::Link( $item->url, $item->label );
				$list[]	= UI_HTML_Elements::ListItem( $link, $level );
			}
		}
		$list	= UI_HTML_Elements::unorderedList( $list, $level );
		$nested		= count( $steps ) > 1 ? " nested" : "";
		$attributes	= array(
			"id"	=> "layer_".$parent,
			"class"	=> "stepLayer".$nested
		);

		$heading	= '<div class="heading">'.$tree->label.'</div>';

		$list	= UI_HTML_Tag::create( "div", $backlinks.$heading.$list, $attributes );

		foreach( $tree->getChildren() as $id => $item )
		{
			if( $item->hasChildren() )
			{
				$newSteps	= $steps;
				$newSteps[]	= array(
					'id'	=> $parent."_".$id,
					'level'	=> $level+1,
					'label'	=> $item->label,
				);
				$list	.= self::buildLayersRecursive( $item, $parent."_".$id, $newSteps );
			}
		}
		return $list;
	}
}
?>