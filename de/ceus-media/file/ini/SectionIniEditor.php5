<?php
import( 'de.ceus-media.file.ini.SectionIniReader' );
/**
 *	Editor for sectioned Ini Files using parse_ini_file.
 *	@package		file
 *	@subpackage		ini
 *	@extends		SectionIniReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.1
 */
/*
 *	Editor for sectioned Ini Files using parse_ini_file.
 *	@package		file
 *	@subpackage		ini
 *	@extends		SectionIniReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.1
 */
class SectionIniEditor extends SectionIniReader
{
	/**	@var		bool		$_auto_write	Flag: write automaticly after every modification */
	var $_auto_write;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri			URI of File to edit
	 *	@param		bool		$auto_write		Flag: write automatically after every Modification
	 *	@param		bool		$auto_read		Flag: read automatically after Construction
	 *	@return		void
	 */
	public function __construct( $uri, $auto_write = true, $auto_read = true )
	{
		parent::__construct( $uri, $auto_read );
		$this->_auto_write	= $auto_write;
	}

	/**
	 *	Adds a Section.
	 *	@access		public
	 *	@param		string		$section		Section to add
	 *	@return		void
	 */
	function addSection( $section )
	{
		if( !$this->hasSection( $section ) )
		{
			$this->_data[$section] = array();
			if( $this->_auto_write )
				$this->write();
		}
		else
			trigger_error( "Section '".$section."' is already existing." );
	}
	
	/**
	 *	Sets a Property.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@param		string		$value			Value of Property
	 *	@return		void
	 */
	function setProperty( $section, $key, $value )
	{
		if( !$this->hasSection( $section ) )
			$this->addSection( $section );
		$this->_data[$section][$key]	= $value;
		if( $this->_auto_write )
			$this->write();
	}
	
	/**
	 *	Removes a Property.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@return		void
	 */
	function removeProperty( $section, $key )
	{
		if( $this->hasProperty( $section, $key ) )
		{
			unset( $this->_data[$section][$key] );
			if( $this->_auto_write )
				$this->write();
		}
	}
	
	/**
	 *	Removes a Section.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@return		void
	 */
	function removeSection( $section )
	{
		if( $this->hasSection( $section ) )
		{
			unset( $this->_data[$section] );
			if( $this->_auto_write )
				$this->write();
			return true;
		}
		return false;
	}

	/**
	 *	Writes sectioned Ini File.
	 *	@access		public
	 *	@return		void
	 */
	function write()
	{
		$lines	= array();
		$sections	= $this->getSections();
		foreach( $sections as $section )
		{
			$lines[]	= "[".$section."]";
			foreach( $this->_data[$section] as $key => $value )
				$lines[]	= $this->_fillUp( $key )."=".$value;
		}
		$lines	= implode( "\n", $lines );
		$fp		= fopen( $this->_uri, "w" );
		fputs( $fp, $lines );
		fclose( $fp );
	}
	
	/**
	 *	Builds uniformed indent between Keys and Values.
	 *	@access		private
	 *	@param		string		$key			Key of Property
	 *	@param		int			$tabs			Amount to Tabs to indent
	 *	@return		string
	 */
	function _fillUp( $key, $tabs = 5 )
	{
		$key_breaks	= $tabs - floor( strlen( $key ) / 8 );
		if( $key_breaks < 1 )
			$key_breaks = 1;
		$key	= $key.str_repeat( "\t", $key_breaks );
		return $key;
	}
}
?>