<?php
/**
 *	Reader for sectioned Ini Files using parse_ini_file.
 *	@package		file
 *	@subpackage		ini
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.1
 */
/*
 *	Reader for sectioned Ini Files using parse_ini_file.
 *	@package		file
 *	@subpackage		ini
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.1
 */
class SectionIniReader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri			URI of File to edit
	 *	@param		bool		$auto_read		Flag: read automatically after Construction
	 *	@return		void
	 */
	public function __construct( $uri, $auto_read = true )
	{
		$this->_uri		= $uri;
		if( $auto_read )
			$this->read();
	}

	/**
	 *	Reads sectioned Ini File.
	 *	@access		public
	 *	@return		void
	 */
	function read()
	{
		if( file_exists( $this->_uri ) )
			$this->_data		= parse_ini_file( $this->_uri, true );
		else
			trigger_error( "File '".$this->_uri."' is not existing.", E_USER_WARNING );
	}
	
	/**
	 *	Returns a Property by its Key.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@return		string
	 */
	function getProperty( $section, $key )
	{
		if( $this->hasProperty( $section, $key ) )
			return $this->_data[$section][$key];
		return NULL;
	}
	
	/**
	 *	Indicated whether a Keys is set.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@return		bool
	 */
	function hasProperty( $section, $key )
	{
		return isset( $this->_data[$section][$key] );
	}
	
	/**
	 *	Indicated whether a Section is set.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@return		bool
	 */
	function hasSection( $section )
	{
		return in_array( $section, $this->getSections() );
	}
	
	/**
	 *	Returns all Sections as Array.
	 *	@access		public
	 *	@return		array
	 */
	function getSections()
	{
		return array_keys( $this->_data );
	}
	
	/**
	 *	Returns all Properties as Array.
	 *	@access		public
	 *	@param		bool		$section		Flag: use Sections
	 *	@return		array
	 */
	function toArray( $section = false )
	{
		if( $section && in_array( $section, $this->getSections() ) )
			return $this->_data[$section];
		return $this->_data;
	}
}
?>