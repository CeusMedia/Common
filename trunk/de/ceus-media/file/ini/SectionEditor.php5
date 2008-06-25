<?php
import( 'de.ceus-media.file.ini.SectionReader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Editor for sectioned Ini Files using parse_ini_file.
 *	@package		file.ini
 *	@extends		File_INI_SectionReader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.6
 */
/**
 *	Editor for sectioned Ini Files using parse_ini_file.
 *	@package		file.ini
 *	@extends		File_INI_SectionReader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.6
 */
class File_INI_SectionEditor extends File_INI_SectionReader
{
	/**
	 *	Adds a Section.
	 *	@access		public
	 *	@param		string		$section		Section to add
	 *	@return		bool
	 */
	public function addSection( $section )
	{
		if( $this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is already existing.' );
		$this->data[$section] = array();
		return is_int( $this->write() );
	}
	
	/**
	 *	Sets a Property.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@param		string		$value			Value of Property
	 *	@return		bool
	 */
	public function setProperty( $section, $key, $value )
	{
		if( !$this->hasSection( $section ) )
			$this->addSection( $section );
		$this->data[$section][$key]	= $value;
		return is_int( $this->write() );
	}
	
	/**
	 *	Removes a Property.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@return		bool
	 */
	public function removeProperty( $section, $key )
	{
		if( !$this->hasProperty( $section, $key ) )
			throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in Section "'.$section.'".' );
		unset( $this->data[$section][$key] );
		return is_int( $this->write() );
	}
	
	/**
	 *	Removes a Section.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@return		bool
	 */
	public function removeSection( $section )
	{
		if( !$this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing.' ); 
		unset( $this->data[$section] );
		return is_int( $this->write() );
	}

	/**
	 *	Writes sectioned Property File and returns Number of written Bytes.
	 *	@access		public
	 *	@return		int
	 */
	public function write()
	{
		$lines		= array();
		$sections	= $this->getSections();
		foreach( $sections as $section )
		{
			$lines[]	= "[".$section."]";
			foreach( $this->data[$section] as $key => $value )
				$lines[]	= $this->fillUp( $key )."=".$value;
		}
		return File_Writer::saveArray( $this->fileName, $lines );
		$this->read();
	}
	
	/**
	 *	Builds uniformed indent between Keys and Values.
	 *	@access		protected
	 *	@param		string		$key			Key of Property
	 *	@param		int			$tabs			Amount to Tabs to indent
	 *	@return		string
	 */
	protected function fillUp( $key, $tabs = 5 )
	{
		$key_breaks	= $tabs - floor( strlen( $key ) / 8 );
		if( $key_breaks < 1 )
			$key_breaks = 1;
		$key	= $key.str_repeat( "\t", $key_breaks );
		return $key;
	}
}
?>