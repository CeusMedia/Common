<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.xml.opml.Parser' );
import( 'de.ceus-media.adt.tree.menu.Item' );
class Alg_Tree_Menu_Converter
{
	/**
	 *	Converts an OPML File to a Tree Menu List.
	 *	@access		public
	 *	@param		string		$fileName		File Name of OPML File
	 *	@param		string		$labelRoot		Label of Top Tree Menu List
	 *	@return		ADT_Tree_Menu_List
	 */
	public static function convertFromOpmlFile( $fileName, $labelRoot )
	{
		$opml		= File_Reader::load( $fileName );
		return self::convertFromOpml( $opml, $labelRoot );
	}
	
	/**
	 *	Converts an OPML String to a Tree Menu List.
	 *	@access		public
	 *	@param		string		$opml			OPML String
	 *	@param		string		$labelRoot		Label of Top Tree Menu List
	 *	@return		ADT_Tree_Menu_List
	 */
	public static function convertFromOpml( $opml, $labelRoot )
	{
		$parser		= new XML_OPML_Parser();
		$parser->parse( $opml );
		$lines		= $parser->getOutlines();
		$list		= new ADT_Tree_Menu_List( $labelRoot );

		self::buildMenuListFromOutlines( $lines, $list );
		return $list;
	}

	/**
	 *	Adds Tree Menu Items from OPML Outlines into a given Tree Menu List recursively.
	 *	@access		public
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