<?php
/**
 *	Converter between OPML and Tree Menu Structure.
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
 *	@package		Alg.Tree.Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.12.2008
 *	@version		$Id$
 */
/**
 *	Converter between OPML and Tree Menu Structure.
 *	@category		cmClasses
 *	@package		Alg.Tree.Menu
 *	@uses			File_Reader
 *	@uses			XML_OPML_Parser
 *	@uses			ADT_Tree_Menu_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.12.2008
 *	@version		$Id$
 */
class Alg_Tree_Menu_Converter
{
	/**
	 *	Converts an OPML File to a Tree Menu List.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of OPML File
	 *	@param		string		$labelRoot		Label of Top Tree Menu List
	 *	@param		string		$rootClass		CSS Class of root node
	 *	@return		ADT_Tree_Menu_List
	 */
	public static function convertFromOpmlFile( $fileName, $labelRoot, $rootClass = NULL )
	{
		$opml		= File_Reader::load( $fileName );
		return self::convertFromOpml( $opml, $labelRoot, $rootClass );
	}
	
	/**
	 *	Converts an OPML String to a Tree Menu List.
	 *	@access		public
	 *	@static
	 *	@param		string		$opml			OPML String
	 *	@param		string		$labelRoot		Label of Top Tree Menu List
	 *	@param		string		$rootClass		CSS Class of root node
	 *	@return		ADT_Tree_Menu_List
	 */
	public static function convertFromOpml( $opml, $labelRoot, $rootClass = NULL )
	{
		$parser		= new XML_OPML_Parser();
		$parser->parse( $opml );
		$lines		= $parser->getOutlines();
		$list		= new ADT_Tree_Menu_List( $labelRoot, array( 'class' => $rootClass ) );

		self::buildMenuListFromOutlines( $lines, $list );
		return $list;
	}

	/**
	 *	Adds Tree Menu Items from OPML Outlines into a given Tree Menu List recursively.
	 *	@access		public
	 *	@static
	 *	@param		array				$outlines		Outline Array from OPML Parser
	 *	@param		ADT_Tree_Menu_List	$container		Current working Menu Container, a Tree Menu List initially.
	 *	@return		void
	 */
	protected static function buildMenuListFromOutlines( $lines, &$container )
	{
		foreach( $lines as $line )
		{
			if( isset( $line['outlines'] ) && count( $line['outlines'] ) )
			{
				if( isset ( $line['url'] ) )
					$item	= new ADT_Tree_Menu_Item( $line['url'], $line['text'] );
				else
					$item	= new ADT_Tree_Menu_List( $line['text'] );
				self::buildMenuListFromOutlines( $line['outlines'], $item );
				$container->addChild( $item );
			}
			else
			{
				$item	= new ADT_Tree_Menu_Item( $line['url'], $line['text'] );
				$container->addChild( $item );
			}
		}
	}
}
?>