<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.File' );
/**
 *	Definition of Input Field within Channels, Screens and Forms.
 *	@package		adt
 *	@extends		ADT_OptionObject
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.05.2006
 *	@version		0.1
 */
/**
 *	Definition of Input Field within Channels, Screens and Forms.
 *	@package		adt
 *	@extends		ADT_OptionObject
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.05.2006
 *	@version		0.1
 */
class FieldDefinition extends ADT_OptionObject
{
#	/**	@var		string		_channel		Output Channel */
#	var $_channel;
#	/**	@var		string		_screen		Channel Screen*/
#	var $_screen;
#	/**	@var		string		_form		Screen Form */
#	var $_form;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		path			Path to XML Definition File
	 *	@param		bool			use_cache	Flag: cache XML Files party in Cache Folder
	 *	@param		string		cache_path	Path to Cache Folder
	 *	@param		string		prefix		Prefix of XML Definition Files
	 *	@return		void
	 */
	public function __construct( $path = "", $use_cache = false, $cache_path = "cache/", $prefix = "" )
	{
		$this->setOption( 'path', $path );
		if( $use_cache )
		{
			$this->setOption( 'use_cache', $use_cache );
			$this->setOption( 'cache_path', $cache_path );
			$this->setOption( 'prefix', $prefix );
		}
	}
	
	/**
	 *	Returns complete Definition of a Field.
	 *	@access		public
	 *	@param		string		name		Name of Field
	 *	@return		array
	 */
	function getField( $name )
	{
		return $this->_definition[$name];	
	}

	/**
	 *	Returns syntactic Definition of a Field.
	 *	@access		public
	 *	@param		string		name		Name of Field
	 *	@return		array
	 */
	function getFieldSyntax( $name )
	{
		return $this->_definition[$name]['syntax'];
	}

	/**
	 *	Returns semantic Definition of a Field.
	 *	@access		public
	 *	@param		string		name		Name of Field
	 *	@return		array
	 */
	function getFieldSemantics( $name )
	{
		if( isset( $this->_definition[$name]['semantic'] ) )
			return $this->_definition[$name]['semantic'];
		return array();
	}

	/**
	 *	Returns Input Type of a Field.
	 *	@access		public
	 *	@param		string		name		Name of Field
	 *	@return		array
	 */
	function getFieldInput( $name )
	{
		return (array)$this->_definition[$name]['input'];
	}

	/**
	 *	Returns an Array of all Field Names in Definition.
	 *	@access		public
	 *	@return		array
	 */
	function getFields()
	{
		return array_keys( $this->_definition );	
	}

	/**
	 *	Loads Definition from XML Definition File or Cache.
	 *	@access		public
	 *	@param		string		filename		File Name of XML Definition File
	 *	@param		bool			force		Flag: force Loading of XML Defintion
	 *	@return		void
	 */
	function loadDefinition( $filename, $force = false )
	{
		$prefix	= $this->getOption( 'prefix' );
		$path	= $this->getOption( 'path' );
		$xml_file	= $path.$prefix.$filename.".xml";
		if( !$force && $this->getOption( 'use_cache' ) )
		{
			$cache_file	= $this->_getCacheFilename( $filename );
			if( file_exists( $cache_file ) && filemtime( $xml_file ) <= filemtime( $cache_file ) )
			{
				$file	= new File( $cache_file );
				$this->_definition	= unserialize( $file->readString() );
				return true;
			}
		}
		if(  file_exists( $xml_file ) )
		{
			$this->_loadDefinitionXML( $xml_file );
			if( $this->getOption( 'use_cache' ) )
				$this->_writeCacheFile( $filename );
		}
		else
			trigger_error( "Definition File '".$xml_file."' is not existing", E_USER_ERROR );
	}

	/**
	 *	Sets Output Channel.
	 *	@access		public
	 *	@param		string		channel		Output Channel
	 *	@return		void
	 */
	function setChannel( $channel )
	{
		$this->setOption( 'channel', $channel );
	}

	/**
	 *	Sets Channel Screen.
	 *	@access		public
	 *	@param		string		screen		Channel Screen
	 *	@return		void
	 */
	function setScreen( $screen )
	{
		$this->setOption( 'screen', $screen );
	}

	/**
	 *	Sets Screen Form
	 *	@access		public
	 *	@param		string		form			Screen Form
	 *	@return		void
	 */
	function setForm( $form )
	{
		$this->setOption( 'form', $form );
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Loads Definition from XML Definition File.
	 *	@access		public
	 *	@param		string		filename		File Name of XML Definition File
	 *	@return		void
	 */
	function _loadDefinitionXML( $filename )
	{
		$this->_definition	= array();

		$doc	= new DOMDocument();
		$doc->preserveWhiteSpace	= false;
		$doc->load( $filename );
		$channels = $doc->firstChild->childNodes;
		foreach( $channels as $channel )
		{
			if( $channel->getAttribute( "type" ) == $this->getOption( 'channel' ) )
			{
				$screens	= $channel->childNodes;
				foreach( $screens as $screen )
				{
					if( $screen->getAttribute( "id" ) == $this->getOption( 'screen' ) )
					{
						$forms	= $screen->childNodes;
						foreach( $forms as $form )
						{
							if( $form->nodeType == XML_ELEMENT_NODE )
							{
								if( $form->getAttribute( "name" ) == $this->getOption( 'form' ) )
								{
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
													$keys	= array( "name", "type", "style", "validator", "source", "submit", "disabled", "hidden", "tabindex", "colspan" );
													foreach( $keys as $key )
														$_field[$name][$key]	= $node->getAttribute( $key );
													$_field[$name]['default']		= $node->textContent;
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
											$this->_definition[$name] = $_field;
										}
									}
									break;
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 *	Writes Cache File.
	 *	@access		private
	 *	@param		string		filename		File Name of XML Definition File
	 *	@return		void
	 */
	function _writeCacheFile( $filename )
	{
		$cache_file	= $this->_getCacheFilename( $filename );
		$file	= new File( $cache_file, 0755 );
		$file->writeString( serialize( $this->_definition ) );
	}

	/**
	 *	Returns full File Name of Cache File.
	 *	@access		pricate
	 *	@param		string		filename		File Name of XML Definition File
	 *	@return		string
	 */
	function _getCacheFilename( $filename )
	{
		$file	= $this->getOption( 'cache_path' ).$filename."_".$this->getOption( 'channel' )."_".$this->getOption( 'screen' )."_".$this->getOption( 'form' ).".cache";
		return $file;
	}
}
?>