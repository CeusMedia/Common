<?php
/**
 *	Parser for RSS 2.0 Feeds usind SimpleXML.
 *	@package		xml.rss
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Parser for RSS 2.0 Feeds usind SimpleXML.
 *	@package		xml.rss
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
class XML_RSS_SimpleParser
{
	/**
	 *	Reads RSS from XML statically and returns Array containing Channel Data and Items.
	 *	@access		public
	 *	@param		string		$xml		XML String to read
	 *	@return		array
	 */
	public static function parse( $xml )
	{
		$channelData	= array();
		$itemList		= array();
		$xml	= new SimpleXMLElement( $xml );
		foreach( $xml->channel->children() as $nodeName => $nodeValue )
		{
			if( $nodeName == "image" && $nodeValue->children() )
			{
				$channelData[$nodeName]	= self::readSubSet( $nodeValue );
				continue;
			}
			if( $nodeName == "textInput" && $nodeValue->children() )
			{
				$channelData[$nodeName]	= self::readSubSet( $nodeValue );
				continue;
			}
			if( $nodeName != "item" )
			{
				$channelData[$nodeName]	= (string) $nodeValue;
				continue;
			}
			$item		= array();
			$itemNode	= $nodeValue;
			foreach( $itemNode->children() as $nodeName => $nodeValue )
				$item[$nodeName]	= (string) $nodeValue;
			$itemList[]	= $item;
		}
		$attributes	= $xml->attributes();
		$data	= array(
			'encoding'		=> $attributes['encoding'],
			'channelData'	=> $channelData,
			'itemList'		=> $itemList,
		);
		return $data;
	}

	/**
	 *	Reads Subset of Node.
	 *	@access		protected
	 *	@param		SimpleXMLElement	$node		Subset Node
	 *	@return		array
	 */
	protected static function readSubSet( $node )
	{
		$item	= array();
		foreach( $node->children() as $nodeName => $nodeValue )
			$item[$nodeName]	= (string) $nodeValue;
		return $item;
	}
}
?>