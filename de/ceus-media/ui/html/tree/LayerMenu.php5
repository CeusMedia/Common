<?php
import( 'de.ceus-media.alg.tree.menu.Converter' );
import( 'de.ceus-media.ui.html.Tag' );
import( 'de.ceus-media.ui.html.Elements' );
class UI_HTML_Tree_LayerMenu
{
	public function __construct( $rootId, $rootLabel )
	{
		$this->rootId		= $rootId;
		$this->rootLabel	= $rootLabel;
	}
	public function buildMenuFromOpmlFile( $fileName )
	{
		$list	= Alg_Tree_Menu_Converter::convertFromOpmlFile( $fileName, $this->rootLabel );;
		return $this->buildMenuFromMenuList( $list );
	}
	
	public function buildMenuFromOpml( $opml, $labelRoot )
	{
		$list		= Alg_Tree_Menu_Converter::convertFromOpml( $opml, $this->rootLabel );
		return $this->buildMenuFromMenuList( $list );
	}

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

	protected static function buildLayersRecursive( $tree, $parent, $steps = array(), $level = 0 )
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
		$list	= UI_HTML_Tag::create( "div", $backlinks.$list, $attributes );

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