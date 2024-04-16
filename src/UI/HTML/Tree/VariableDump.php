<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\UI\HTML\Tree;

use CeusMedia\Common\UI\HTML\Elements as HtmlElements;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use RuntimeException;

/**
 *	Output Methods for Development.
 *
 *	Copyright (c) 2009-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Output Methods for Development.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class VariableDump
{
	/**	@var	string		$noteOpen		Sign for opening Notes */
	public static string $noteOpen			= '<em>';

	/**	@var	string		$noteClose		Sign for closing Notes */
	public static string $noteClose			= '</em>';

	public static int $count				= 0;

	/**
	 *	Builds and returns a Tree Display of a Variable, recursively.
	 *	@access		public
	 *	@static
	 *	@param		mixed		    $mixed		Variable of every Type to build Tree for
	 *	@param		string|NULL		$key		Variable Name
	 *	@param		bool		    $closed		Flag: start with closed Nodes
	 *	@param		int		    	$level		Depth Level
	 *	@return		string
	 */
	public static function buildTree( mixed $mixed, ?string $key = NULL, bool $closed = FALSE, int $level = 0 ): string
	{
		if( $level === 0 )
			self::$count	= 0;
		$type		= gettype( $mixed );
		$children	= [];
		$keyLabel	= ( $key !== NULL ) ? htmlentities( $key, ENT_QUOTES, 'UTF-8' ).' -> ' : '';
		$event		= NULL;
		self::$count++;
		switch( $type ){
			case 'array':
				self::$count--;
				foreach( $mixed as $childKey => $childValue )
					$children[]	= self::buildTree( $childValue, $childKey, $closed, $level + 1 );
				if( $key === NULL )
					$keyLabel	= self::$noteOpen.'Array'.self::$noteClose;
				$mixed		= '';
				$event		= '$(this).parent().toggleClass(\'closed\'); return false;';
				break;
			case 'object':
				self::$count--;
				$vars		= get_object_vars( $mixed );
				foreach( $vars as $childKey => $childValue )
					$children[]	= self::buildTree( $childValue, $childKey, $closed, $level + 1 );
				$keyLabel	= self::$noteOpen.get_class( $mixed ).self::$noteClose;
				$mixed		= '';
				$event		= '$(this).parent().toggleClass(\'closed\'); return false;';
				break;
			case 'boolean':
				$mixed	= self::$noteOpen.( $mixed ? 'TRUE' : 'FALSE' ).self::$noteClose;
				break;
			case 'NULL':
				if( $mixed === NULL )
					$mixed	= self::$noteOpen.'NULL'.self::$noteClose;
				break;
			case 'unknown type':
				throw new RuntimeException( 'Unknown type' );
			default:
				if( preg_match( "/pass(w(or)?d)?/", $key ) )
					$mixed	= str_repeat( '*', 8 );
				break;
		}
		$children	= $children ? "\n".HtmlElements::unorderedList( $children, $level + 2 ) : '';
		$pair		= $keyLabel.htmlentities( $mixed, ENT_QUOTES, 'UTF-8' );
		$label		= HtmlTag::create( 'span', $pair, array( 'onclick' => $event ) );
		$classes	= [$type];
		if( $closed )
			$classes[]	= 'closed';
		return HtmlElements::ListItem( $label.$children, $level + 1, ['class' => implode( ' ', $classes )] );
	}

	/**
	 *	Global Call Method for UI_HTML_VarTree::buildTree.
	 *	@access		public
	 *	@param		mixed		$mixed		Variable to build Tree for
	 *	@param		bool		$print		Flag: print directly to screen or return
	 *	@param		bool		$closed		Flag: start with closed Nodes
	 *	@return		string|NULL				String if print is disabled, else void
	 */
	public static function dumpVar( mixed $mixed, bool $print = TRUE, bool $closed = FALSE ): ?string
	{
		$tree	= self::buildTree( $mixed, NULL, $closed );
		$list	= HtmlElements::unorderedList( array( $tree ), 1 );
		$code	= '<div class="varTree">'."\n".$list.'</div>';
		if( !$print )
			return $code;
		print $code;
		return NULL;
	}
}

/**
 *	Global Call Method for VariableDump::buildTree.
 *	@access		public
 *	@param		mixed		$mixed		Variable to build Tree for
 *	@param		bool		$print		Flag: print directly to screen or return
 *	@param		bool		$closed		Flag: start with closed Nodes
 *	@return		string|NULL				String if print is disabled, else void
 */
function treeVar( mixed $mixed, bool $print = TRUE, bool $closed = FALSE ): ?string
{
	return VariableDump::dumpVar( $mixed, $print, $closed );
}