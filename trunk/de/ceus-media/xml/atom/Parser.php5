<?php
import( 'de.ceus-media.xml.Element' );
/**
 *	Parser for Atom Feeds.
 *	@package		xml.atom
 *	@uses			XML_Element
 *	@uses			XML_Atom_Validator
 *	@see			http://www.atomenabled.org/developers/syndication/atom-format-spec.php
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.05.2008
 *	@version		0.6
 */
/**
 *	Parser for Atom Feeds.
 *	@package		xml.atom
 *	@uses			XML_Element
 *	@uses			XML_Atom_Validator
 *	@see			http://www.atomenabled.org/developers/syndication/atom-format-spec.php
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.05.2008
 *	@version		0.6
 */
class XML_Atom_Parser
{
	/**	@var		array		$channelData		Array of collect Data about Atom Feed */
	public $channelData;
	/**	@var		array		$emptyChannelData	Template of empty Category Data Structure */
	protected $emptyCategory	= array(
		'label'		=> "",
		'scheme'	=> "",
		'term'		=> "",
	);
	/**	@var		array		$emptyChannelData	Template of empty Channel Data Structure */
	protected $emptyChannelData	= array(
		'author'		=> array(),
		'category'		=> array(),
		'contributor'	=> array(),
		'generator' 	=> array(),								//  will be set to emptyGenerator by Parser								
		'icon'			=> "",
		'id'			=> "",
		'link'			=> array(),
		'logo'			=> "",
		'rights'		=> "",
		'source'		=> "",
		'subtitle'		=> "",
		'title'			=> "",
		'updated'		=> "",
	);
	/**	@var		array		$emptyChannelData	Template of empty Entry Data Structure */
	protected $emptyEntry		= array(
		'author'		=> array(),
		'category'		=> array(),
		'content'		=> array(),								//  will be set to emptyText by Constructor
		'contributor'	=> array(),
		'id'			=> "",
		'link'			=> array(),
		'published'		=> "",
		'rights'		=> "",
		'source'		=> array(),								//  will be set to emptyText by Constructor
		'summary'		=> array(),								//  will be set to emptyText by Constructor
		'title'			=> array(),								//  will be set to emptyText by Constructor
		'updated'		=> "",
	);
	/**	@var		array		$emptyChannelData	Template of empty Generator Data Structure */
	protected $emptyGenerator	= array(
		'uri'		=> "",
		'version'	=> "",
		'name'		=> "",
	);
	/**	@var		array		$emptyChannelData	Template of empty Link Data Structure */
	protected $emptyLink	= array(
		'href'			=> "",
		'rel'			=> NULL,
		'type'			=> NULL,
		'hreflang'		=> NULL,
		'title'			=> NULL,
		'length'		=> NULL,
	);
	/**	@var		array		$emptyChannelData	Template of empty Person Data Structure */
	protected $emptyPerson	= array(
		'name'	=> "",
		'uri'	=> "",
		'email'	=> "",
	);
	/**	@var		array		$emptyChannelData	Template of empty Text Data Structure */
	protected $emptyText		= array(
		'base'		=> "",
		'content'	=> "",
		'lang'		=> "",
		'type'		=> "text",
	);
	/**	@var		array		$entries			Array of Entries in Atom Feed */
	public $entries;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->emptyEntry['content']	= $this->emptyText;
		$this->emptyEntry['summary']	= $this->emptyText;
		$this->emptyEntry['title']		= $this->emptyText;
		$source	= $this->emptyEntry;
		unset( $source['source'] );
		$this->emptyEntry['source']		= $source;
	}

	/**
	 *	Creates a Data Structure with Attributes with a Tempate for a Node.
	 *	@access		protected
	 *	@param		XML_Element	$node				Node to build Data Structure for
	 *	@param		array							Template Data Structure (emptyCategory|emptyChannelData|emptyEntry|emptyGenerator|emptyLink|emptyPerson|emptyText)
	 *	@return		array
	 */
	protected function createAttributeNode( $node, $template = array() )
	{
		$text	= $template;
		foreach( $node->getAttributes() as $key => $value )
			$text[$key]	= $value;
		$text['content']	= (string) $node;
		return $text;
	}
	
	/**
	 *	Parses XML String and stores Channel Data and Entries.
	 *	@access		public
	 *	@param		string		$xml				XML String to parse
	 *	@param		bool		$validateRules		Validate Atom Feed against Atom Rules.
	 *	@return		void
	 */
	public function parse( $xml, $validateRules = TRUE )
	{
		$this->language		= "en";
		$this->channelData	= $this->emptyChannelData;
		$this->entries		= array();
			
		$root		= new XML_Element( $xml );
		if( $validateRules )
		{
			import( 'de.ceus-media.xml.atom.Validator' );
			$validator	= new XML_Atom_Validator();
			if( !$validator->isValid( $root ) )	
				throw new Exception( $validator->getFirstError() );
		}

		$this->language		= $this->getNodeLanguage( $root );
		$this->channelData	= $this->parseNodes( $root, $this->emptyChannelData );
	}

	/**
	 *	Parses Nodes and returns Array Structure.
	 *	@access		protected
	 *	@param		XML_Element		$nodes			XML_Element containing Child Nodes to parse
	 *	@param		array			$template		Template of new Structure (emptyCategory|emptyChannelData|emptyEntry|emptyGenerator|emptyLink|emptyPerson|emptyText)
	 *	@return		array
	 */
	protected function parseNodes( $nodes, $template = array() )
	{
		$target	= $template;
		foreach( $nodes as $nodeName => $node )
		{
			$language	= $this->getNodeLanguage( $node );
			switch( $nodeName )
			{
				case 'author':
				case 'constributor':
					$target[$nodeName][]	= $this->parseNodes( $node, $this->emptyPerson );
					break;
				case 'entry':
					$this->entries[]		= $this->parseNodes( $node, $this->emptyEntry );
					break;
				case 'source':
					$target[$nodeName]		= $this->parseNodes( $node, $this->emptyChannelData );
					break;
				case 'category':
					$target[$nodeName][]	= $this->createAttributeNode( $node, $this->emptyCategory );
					break;
				case 'link':
					$target[$nodeName][]	= $this->createAttributeNode( $node, $this->emptyLink );
					break;
				case 'generator':
					$target[$nodeName]		= $this->createAttributeNode( $node, $this->emptyGenerator );
					break;
				case 'title':
				case 'subtitle':
				case 'summary':
					$target[$nodeName]		= $this->createAttributeNode( $node, $this->emptyText );
					break;
				case 'icon':
				case 'logo':
				default:
					$target[$nodeName]		= (string) $node;
			}
		}
		return $target;
	}
	
	/**
	 *	Returns Language Attributes and returns evaluate Language.
	 *	@access		protected
	 *	@param		XML_Element		$node			XML_Element
	 *	@param		string			$attributeName	Name of Language Attribute
	 *	@return		string
	 */
	protected function getNodeLanguage( $node, $attributeName = "xml:lang" )
	{
		if( strpos( $attributeName, ":" ) )
		{
			$parts	= explode( ":", $attributeName );
			if( $node->hasAttribute( $parts[1], $parts[0] ) )
				return $node->getAttribute( $parts[1], $parts[0] );
		}
		else if( $node->hasAttribute( $attributeName ) )
			return $node->getAttribute( $attributeName );
		return $this->language;
	}
}
?>