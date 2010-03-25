<?php
/**
 *	Output Methods for Developement.
 *
 *	Copyright (c) 2009-2010 Christian Würker (ceus-media.de)
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
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$id$
 */
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Output Methods for Developement.
 *	@category		cmClasses
 *	@package		ui.html
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$id$
 */
class UI_HTML_VarTree
{
	/**	@var		string		$noteOpen		Sign for opening Notes */
	public static $noteOpen		= "<em>";
	/**	@var		string		$noteClose		Sign for closing Notes */
	public static $noteClose	= "</em>";

	/**
	 *	Builds and returns a Tree Display of a Variable, recursively.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$mixed		Variable of every Type to build Tree for
	 *	@param		string		$key		Variable Name
	 *	@param		string		$closed		Flag: start with closed Nodes
	 *	@param		int			$level		Depth Level
	 *	@return		void
	 */
	public static function buildTree( $mixed, $key = NULL, $closed = FALSE, $level = 0 )
	{
		$type		= gettype( $mixed );
		$children	= array();
		switch( $type )
		{
			case 'array':
				foreach( $mixed as $childKey => $childValue )
					$children[]	= self::buildTree( $childValue, $childKey, $closed, $level + 1 );
				if( $key === NULL )
					$key	= self::$noteOpen."Array".self::$noteClose;
				$mixed		= "";
				break;
			case 'object':
				$vars		= get_object_vars( $mixed );
				foreach( $vars as $childKey => $childValue )
					$children[]	= self::buildTree( $childValue, $childKey, $closed, $level + 1 ); 
				$key		= self::$noteOpen.get_class( $mixed ).self::$noteClose;
				$mixed		= "";
				break;
			case 'bool':
				$key	= ( $key !== NULL ) ? $key." -> " : "";
				$mixed	= self::$noteOpen.( $mixed ? "TRUE" : "FALSE" ).self::$noteClose;
				break;
			case 'NULL':
				$key	= ( $key !== NULL ) ? $key." -> " : "";
				if( $mixed === NULL )
					$mixed	= self::$noteOpen."NULL".self::$noteClose;
				break;
			case 'unknown type':
				throw new RuntimeException( 'Unknown type' );
			default:
				$key	= ( $key !== NULL ) ? $key." -> " : "";
				break;
		}
		$children	= $children ? "\n".UI_HTML_Elements::unorderedList( $children, $level + 1 ) : "";
		$event		= '$(this).parent().toggleClass(\'closed\'); return false;';
		$label		= UI_HTML_Tag::create( 'span', $key.$mixed, array( 'onclick' => $event ) );
		$classes	= array( $type );
		if( $closed )
			$classes[]	= "closed";
		return UI_HTML_Elements::ListItem( $label.$children, $level, array( 'class' => implode( " ", $classes ) ) );
	}
}

/**
 *	Global Call Method for UI_HTML_VarTree::buildTree.
 *	@access		public
 *	@param		mixed		$mixed		Variable to build Tree for
 *	@param		string		$print		Flag: print directly to screen or return
 *	@param		int			$closed		Flag: start with closed Nodes
 *	@return		void|string				String if print is disabled, else void
 */
function treeVar( $mixed, $print = TRUE, $closed = FALSE )
{
	$tree	= UI_HTML_VarTree::buildTree( $mixed, NULL, $closed, 1 );
	$list	= UI_HTML_Elements::unorderedList( array( $tree ), 1 );
	$code	= '<div class="varTree">'."\n".$list.'</div>';
	if( !$print )
		return $code;
	print $code;
}
?>