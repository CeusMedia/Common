<?php
/**
 *	Definition of Input Field within Channels, Screens and Forms.
 *	@package		framework.krypton.core
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.05.2006
 *	@version		0.6
 */
/**
 *	Definition of Input Field within Channels, Screens and Forms.
 *	@package		framework.krypton.core
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.05.2006
 *	@version		0.6
 */
class Framework_Krypton_Core_FormDefinitionReader
{
	/**	@var	string		$channel		Output Channel */
	protected $channel;
	/**	@var	string		$screen			Channel Screen*/
	protected $screen;
	/**	@var	string		$form			Screen Form */
	protected $form;
	/**	@var	string		$cachePath		Path to Cache Files */
	protected $cachePath;
	/**	@var	string		$path			Path to Definition Files */
	protected $path;
	/**	@var	string		$prefix			Prefix of Definition Files */
	protected $prefix;
	/**	@var	bool		$useCache		Flag: cache Definitions in Cache Folder */
	protected $useCache;
	/**	@var	array		$definitions	Parsed Definitions */
	protected $definitions	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to XML Definition File
	 *	@param		bool		$useCache		Flag: cache XML Files party in Cache Folder
	 *	@param		string		$cachePath		Path to Cache Folder
	 *	@param		string		$prefix			Prefix of XML Definition Files
	 *	@return		void
	 */
	public function __construct( $path = "", $useCache = false, $cachePath = "cache/", $prefix = "" )
	{
		$this->path		= $path;
		if( $useCache )
		{
			$this->useCache		= $useCache;
			$this->cachePath	= $cachePath;
			$this->prefix		= $prefix;
		}
	}
	
	/**
	 *	Creates nested Folder recursive.
	 *	@access		protected
	 *	@param		string		$path		Folder to create
	 *	@return		void
	 */
	protected function createFolder( $path )
	{
		if( !file_exists( $path ) )
		{
			$parts	= explode( "/", $path );
			$folder	= array_pop( $parts );
			$path	= implode( "/", $parts );
			$this->createFolder( $path );
			mkDir( $path."/".$folder );
		}
	}

	/**
	 *	Returns full File Name of Cache File.
	 *	@access		protected
	 *	@param		string		$fileName			File Name of XML Definition File
	 *	@return		string
	 */
	protected function getCacheFilename( $fileName )
	{
		$file	= $this->cachePath.$fileName."_".$this->channel."_".$this->form.".cache";
		return $file;
	}
	
	/**
	 *	Returns complete Definition of a Field.
	 *	@access		public
	 *	@param		string		$name			Name of Field
	 *	@return		array
	 */
	public function getField( $name )
	{
		if( isset( $this->definitions[$name] ) )
			return $this->definitions[$name];
		return array();
	}

	/**
	 *	Returns semantic Definition of a Field.
	 *	@access		public
	 *	@param		string		$name			Name of Field
	 *	@return		array
	 */
	public function getFieldSemantics( $name )
	{
		if( isset( $this->definitions[$name]['semantic'] ) )
			return $this->definitions[$name]['semantic'];
		return array();
	}

	/**
	 *	Returns syntactic Definition of a Field.
	 *	@access		public
	 *	@param		string		$name			Name of Field
	 *	@return		array
	 */
	public function getFieldSyntax( $name )
	{
		return $this->definitions[$name]['syntax'];
	}

	/**
	 *	Returns Input Type of a Field.
	 *	@access		public
	 *	@param		string		$name			Name of Field
	 *	@return		array
	 */
	public function getFieldInput( $name )
	{
		return (array)$this->definitions[$name]['input'];
	}

	/**
	 *	Returns an Array of all Field Names in Definition.
	 *	@access		public
	 *	@return		array
	 */
	public function getFields()
	{
		return array_keys( $this->definitions );	
	}

	/**
	 *	Loads Definition from XML Definition File or Cache.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML Definition File
	 *	@param		bool		$force			Flag: force Loading of XML Defintion
	 *	@return		void
	 */
	public function loadDefinition( $fileName, $force = false )
	{
		$prefix	= $this->prefix;
		$path	= $this->path;
		$xmlFile	= $path.$prefix.$fileName.".xml";
		if( !$force && $this->useCache )
		{
			$cacheFile	= $this->getCacheFilename( $fileName );
			if( file_exists( $cacheFile ) && filemtime( $xmlFile ) <= filemtime( $cacheFile ) )
			{
				import( 'de.ceus-media.file.Reader' );
				$file	= new File_Reader( $cacheFile );
				$this->definitions	= unserialize( $file->readString() );
				return true;
			}
		}
		if(  file_exists( $xmlFile ) )
		{
			$this->loadDefinitionXML( $xmlFile );
			if( $this->useCache )
				$this->writeCacheFile( $fileName );
		}
		else
			trigger_error( "Definition File '".$xmlFile."' is not existing", E_USER_ERROR );
	}
	
	/**
	 *	Loads Definition from XML Definition File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of XML Definition File
	 *	@return		void
	 */
	protected function loadDefinitionXML( $fileName )
	{
		$this->definitions	= array();
		$doc	= new DOMDocument();
		$doc->preserveWhiteSpace	= false;
		$doc->load( $fileName );
		$channels = $doc->firstChild->childNodes;
		foreach( $channels as $channel )
		{
			if( $channel->getAttribute( "type" ) != $this->channel )
				continue;
			$forms	= $channel->childNodes;
			foreach( $forms as $form )
			{
				if( $form->nodeType != XML_ELEMENT_NODE )
					continue;
				if( $form->getAttribute( "name" ) != $this->form )
					continue;
				$fields	= $form->childNodes;
				foreach( $fields as $field )
				{
					if( $field->nodeType == XML_ELEMENT_NODE )
					{
						$_field	= array();
						$nodes	= $field->childNodes;
						foreach( $nodes as $node )
						{
							$name	= $node->nodeName;
							$_field[$name]	= array();
							if( $name	 == "syntax" )
							{
								$keys	= array( "class", "mandatory", "minlength", "maxlength" );
								foreach( $keys as $key )
									$_field[$name][$key] = $node->getAttribute( $key );
							}
							else if( $name	 == "semantic" )
							{
								$semantic	= array(
									'predicate'	=> $node->getAttribute( 'predicate' ),
									'edge'		=> $node->getAttribute( 'edge' ),
									);
								$_field[$name][] = $semantic;
							}
							if( $name	 == "input" )
							{
								$keys	= array( "name", "type", "style", "validator", "source", "options", "submit", "disabled", "hidden", "tabindex", "colspan", "label" );
								foreach( $keys as $key )
									$_field[$name][$key]	= $node->getAttribute( $key );
								$_field[$name]['default']	= $node->textContent;
							}
							else if( $name	 == "output" )
							{
								$keys	= array( "source", "type", "format", "structure", "style", "label", "hidden", "colspan" );
								foreach( $keys as $key )
									$_field[$name][$key]	= $node->getAttribute( $key );
								$_field[$name]['default']	= $node->textContent;
							}
							else if( $name	 == "calendar" )
							{
								$keys	= array( "component", "type", "range", "direction", "format", "language" );
								foreach( $keys as $key )
									$_field[$name][$key]	= $node->getAttribute( $key );
							}
							else if( $name	 == "help" )
							{
								$keys	= array( "type", "file" );
								foreach( $keys as $key )
									$_field[$name][$key]	= $node->getAttribute( $key );
							}
							else if( $name	 == "hidemode" )
							{
								$_field[$name]['hidemode'][]	= $node->getContent();
							}
							else if( $name	 == "disablemode" )
							{
								$_field[$name]['hidemode'][]	= $node->getContent();
							}
						}
						$name	= $field->getAttribute( "name" );
						$this->definitions[$name] = $_field;
					}
				}
				break;
			}
		}
	}

	/**
	 *	Sets Output Channel.
	 *	@access		public
	 *	@param		string		$channel		Output Channel
	 *	@return		void
	 */
	public function setChannel( $channel )
	{
		$this->channel	= $channel;
	}

	/**
	 *	Sets Channel Screen.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of XML Files
	 *	@return		void
	 */
	public function setPrefix( $prefix )
	{
		$this->prefix	= $prefix;
	}

	/**
	 *	Sets Screen Form
	 *	@access		public
	 *	@param		string		$form			Screen Form
	 *	@return		void
	 */
	public function setForm( $form )
	{
		$this->form	= $form;
	}

	/**
	 *	Writes Cache File.
	 *	@access		protected
	 *	@param		string		$fileName			File Name of XML Definition File
	 *	@return		void
	 */
	protected function writeCacheFile( $fileName )
	{
		import( 'de.ceus-media.file.Writer' );
		$cacheFile	= $this->getCacheFilename( $fileName );
		$this->createFolder( dirname( $cacheFile ) );
		$file	= new File_Writer( $cacheFile, 0755 );
		$file->writeString( serialize( $this->definitions ) );
	}
}
?>
