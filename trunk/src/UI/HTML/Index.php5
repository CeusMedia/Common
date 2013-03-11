<?php
/**
 *	Generates HTML of an index structure, parsed headings within HTML or given by OPML (or [several] tree structures and objects).
 *
 *	Copyright (c) 2013 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Generates HTML of an index structure, parsed headings within HTML or given by OPML (or [several] tree structures and objects).
 *
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 *	@todo		implement import of trees and perhaps normal setters
 *	@todo		code doc
 */
class UI_HTML_Index{

	public $headings	= array();
	public $tree		= array();

	/**
	 *	Parses HTML for headings.
	 *	@access		public
	 *	@param		string		$html		Reference to HTML to inspect for headings
	 *	@return		void
	 */
	public function importFromHtml( &$content, $level = 1 ){
		$this->headings	= array();																	//  
		$this->tree		= $this->importFromHtmlRecursive( $content, $level );						//  
		$this->setHeadingIds( $content, $level );													//  
	}

	protected function importFromHtmlRecursive( $content, $level ){
		if( !preg_match( "/<h".$level.">/", $content ) )											//  no heading of this level found
			return array();																			//  return empty list
		$tree		= array();																		//  prepare empty tree
		$headings	= array();																		//  prepare empty heading list
		preg_match_all( "/<h".$level.">(.+)<\/h".$level.">/U", $content, $headings );				//  collect headings of this level
		$parts	= preg_split( "/<h".$level.">.+<\/h".$level.">/U", $content );						//  split HTML into blocks
		array_shift( $parts );																		//  ignore block above first heading
		foreach( $parts as $nr => $part ){															//  iterate blocks
			$heading	= preg_replace( "/<.+>/", "", $headings[1][$nr] );							//  
			$id			= strtolower( trim( $heading ) );											//  
			$id			= str_replace( array( " ", "_" ), "-", $id );								//  
			$id			= preg_replace( "/[^a-z0-9-]/", "", $id );									//  
			$this->headings[]	= (object) array(													//  
				'label'		=> $heading,															//  
				'level'		=> $level,																//  
				'id'		=> $id,																	//  
			);
			$tree[]	= (object) array(																//  
				'label'		=> $heading,															//  
				'level'		=> $level,																//  
				'id'		=> $id,																	//  
				'children'	=> $this->importFromHtmlRecursive( $part, $level + 1 ),					//  
			);
		}
		return $tree;																				//  
	}

	/**
	 *	Generates plain list of parsed heading structure.
	 *	@access		public
	 *	@return		string		HTML of list containing heading structure.
	 */
	public function renderList(){
		$list	= array();																			//  
		foreach( $this->headings as $item ){														//  
			$link	= UI_HTML_Tag::create( 'a', $item->label, array( 'href' => "#".$item->id ) );	//  
			$attributes		= array( 'class' => 'level-'.$item->level );							//  
			$list[]	= UI_HTML_Elements::ListItem( $link, 0, $attributes );							//  
		}
		return UI_HTML_Tag::create( 'ul', $list, array( 'class' => 'index-list' ) );				//  
	}

	/**
	 *	Generates nested lists of parsed heading structure.
	 *	@access		public
	 *	@param		array		$tree		Tree of heading structure, default: parsed tree
	 *	@return		string		HTML of nested lists containing heading structure.
	 */
	public function renderTree( $tree = NULL ){														//  
		$list	= array();																			//  
		if( is_null( $tree ) )																		//  
			$tree	= $this->tree;																	//  
		foreach( $tree as $item ){
			$link	= UI_HTML_Tag::create( 'a', $item->label, array( 'href' => "#".$item->id ) );	//  
			$attributes		= array( 'class' => 'level-'.$item->level );							//  
			$subtree		= $this->renderTree( $item->children );									//  
			$list[]	= UI_HTML_Elements::ListItem( $link.$subtree, 0, $attributes );					//  
		}
		return UI_HTML_Tag::create( 'ul', $list, array( 'class' => 'index-tree' ) );				//  
	}

	/**
	 *	Tries to apply an ID to collect headings on HTML.
	 *	@access		protected
	 *	@param		string		$content		Reference to HTML to extend by heading IDs
	 *	@param		integer		$level			Heading level to start at, default: 1
	 *	@return		string		Resulting HTML
	 */
	protected function setHeadingIds( &$content, $level = 1 ){
		foreach( $this->headings as $heading ){														//  
			$find		= "/<h".$heading->level."(.*)>".$heading->label."/";						//  
			$replace	= '<h'.$heading->level.'\\1 id="'.$heading->id.'">'.$heading->label;		//  
			$content	= preg_replace( $find, $replace, $content );								//  
		}
		return $content;
	}
}
?>
