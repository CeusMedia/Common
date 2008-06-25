<?php
import( 'de.ceus-media.xml.atom.Parser' );
class XML_Atom_Reader
{
	protected $parser;
	
	public function __construct()
	{
		$this->parser	= new XML_Atom_Parser();
	}

	public function readXml( $xml )
	{
		$this->parser->parse( $xml );
	}

	public function readUrl( $url )
	{
		import( 'de.ceus-media.net.Reader' );
		$xml	= Net_Reader::readUrl( $url );
		$this->parser->parse( $xml );
	}

	public function readFile( $fileName )
	{
		import( 'de.ceus-media.file.Reader' );
		$xml	= File_Reader::load( $fileName );
		$this->parser->parse( $xml );
	}

	protected function checkEntryIndex( $index )
	{
		if( !isset( $this->parser->entries[$index] ) )
			throw new InvalidArgumentException( 'Entry with Index "'.$index.'" is not existing.' );
	}

	public function getChannelAuthors()
	{
		return $this->parser->channelData['author'];
	}
		
	public function getChannelCategories()
	{
		return $this->parser->channelData['category'];
	}

	public function getChannelContributors()
	{
		return $this->parser->channelData['contributor'];
	}
		
	protected function getChannelElementAndAttribute( $element, $attribute = NULL )
	{
		if( !$attribute )
			return $this->parser->channelData[$element];
		if( !array_key_exists( $attribute, $this->parser->channelData[$element] ) )
			throw new Exception( 'Attribute "'.$attribute.'" is not set in Channel Element "'.$element.'".' );
		return $this->parser->channelData[$element][$attribute];
	}
	
	public function getChannelGenerator()
	{
		return $this->parser->channelData['generator'];
	}
		
	public function getChannelIcon()
	{
		return $this->parser->channelData['icon'];
	}

	public function getChannelId()
	{
		return $this->parser->channelData['id'];
	}
		
	public function getChannelLinks()
	{
		return $this->parser->channelData['link'];
	}
	
	public function getChannelLogo()
	{
		return $this->parser->channelData['logo'];
	}
	
	public function getChannelRights()
	{
		return $this->parser->channelData['rights'];
	}

	public function getChannelSubtitle( $attribute = 'content' )
	{
		return $this->getChannelElementAndAttribute( 'subtitle', $attribute );
	}
		
	public function getChannelTitle( $attribute = 'content' )
	{
		return $this->getChannelElementAndAttribute( 'title', $attribute );
	}
	
	public function getChannelUpdated()
	{
		return $this->parser->channelData['updated'];
	}
	
	public function getChannelData()
	{
		return $this->parser->channelData;
	}
	
	public function getEntries( $language = NULL )
	{
	}	
	

	public function getEntry( $index )
	{
		$this->checkEntryIndex( $index );
		return $this->parser->entries[$index];
	}

	public function getEntryAuthors( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'author' );
	}
	
	public function getEntryCategories( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'category' );
	}
		
	public function getEntryContent( $index, $attribute = 'content' )
	{
		return $this->getEntryElementAndAttribute( $index, 'content', $attribute );
	}
		
	public function getEntryContributors( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'contributor' );
	}
		
	protected function getEntryElementAndAttribute( $index, $element, $attribute = NULL )
	{
		$this->checkEntryIndex( $index );
		if( !$attribute )
			return $this->parser->entries[$index][$element];
		if( !array_key_exists( $attribute, $this->parser->entries[$index][$element] ) )
		{
			print_m( $this->parser->entries[$index][$element] );
			throw new Exception( 'Attribute "'.$attribute.'" is not set in Entry Element "'.$element.'".' );
		}
		return $this->parser->entries[$index][$element][$attribute];
	}
	
	public function getEntryId( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'id' );
	}
		
	public function getEntryLinks( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'link' );
	}
		
	public function getEntryPublished( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'published' );
	}
		
	public function getEntryRights( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'rights' );
	}
		
	public function getEntrySource( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'source' );
	}
		
	public function getEntrySummary( $index, $attribute = 'content' )
	{
		return $this->getEntryElementAndAttribute( $index, 'summary', $attribute );
	}
		
	public function getEntryTitle( $index, $attribute = 'content' )
	{
		return $this->getEntryElementAndAttribute( $index, 'title', $attribute );
	}
		
	public function getEntryUpdated( $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'updated' );
	}
		
	public function getLanguage()
	{
		return $this->parser->language;
	}
}
?>