<?php
import( 'de.ceus-media.xml.dom.XML_Configuration' );
/**
 *	Editor for Configurations via XML.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XML_Configuration
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.09.2005
 *	@version		0.4
 */
/**
 *	Editor for Configurations via XML.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XML_Configuration
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.09.2005
 *	@version		0.4
 */
class XML_ConfigurationEditor extends XML_Configuration
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of configration File
	 *	@param		bool			useCache	Flag: use Caching
	 *	@return		void
	 */
	public function __construct( $filename, $useCache = false )
	{
		parent::__construct( $filename, $useCache );
	}

	/**
	 *	Copies a Section of a Configuration.
	 *	@access		public
	 *	@param		string		source		Source name of Section
	 *	@param		string		target		Target name of Section
	 *	@return		void
	 */
	function copySection( $source, $target )
	{
		$this->_config[$target] = $this->_config[$source];
	}

	/**
	 *	Renames a Section of a Configuration.
	 *	@access		public
	 *	@param		string		old			Old name of Section
	 *	@param		string		new			New name of Section
	 *	@return		void
	 */
	function renameSection( $old, $new )
	{
		$this->copySection( $old, $new );
		$this->removeSection( $old );
	}


	/**
	 *	Saves a Configuration.
	 *	@access		public
	 *	@param		string		filename		URI of configuration file
	 *	@return		void
	 */
	function removeSection( $section )
	{
		unset( $this->_config[$section] );
	}

	/**
	 *	Saves a Configuration.
	 *	@access		public
	 *	@param		string		encoding	 	Encoding Type
	 *	@return		void
	 */
	function save( $encoding = "utf-8" )
	{
		$tree	= new XML_DOM_Node( 'configuration' );
		foreach( $this->getConfigValues() as $section_name => $section_data )
		{
			$section	=& new XML_DOM_Node( 'section');
			$section->setAttribute( 'name', $section_name );
			foreach( $section_data as $key => $value )
			{
				$value_type	= gettype( $value );
				if( $value_type == "boolean")
				{
					$value	= $value ? "1" : "0";
					$value_type = "bool";
				}
				else if( $value_type == "integer" )
					$value_type = "int";
				else
					$value_type = "string";
				$node	=& new XML_DOM_Node( 'value', $value );
				$node->setAttribute( 'name', $key );
				$node->setAttribute( 'type',  $value_type);
				$section->addChild( $node );
			}
			$tree->addChild( $section );
		}
		$xw		= new XML_DOM_FileWriter( $this->getOption( 'pathConfig' ).$this->_filename );
		$xw->write( $tree, $encoding );
		if( $this->getOption( 'useCache' ) )
			$this->_writeCache();
	}
}
?>