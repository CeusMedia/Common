<?php
import( 'de.ceus-media.adt.stack.Queue' );
import( 'de.ceus-media.file.File' );
/**
 *	Queue with File.
 *	@package		adt
 *	@subpackage		stack
 *	@extends		Queue
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.03.2006
 *	@version		0.1
 */
/**
 *	Queue with File.
 *	@package		adt
 *	@subpackage		stack
 *	@extends		Queue
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.03.2006
 *	@version		0.1
 */
class FileQueue extends Queue
{
	/**	@var	File		File Handler */
	var $_file;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		Name of Queue File
	 *	@return		void
	 */
	public function __construct( $filename )
	{
		$this->_file	= new File( $filename, 0755 );
		$this->_load();
		parent::__construct();
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Loads Queue from File.
	 *	@access		private
	 *	@return		void
	 */
	function _load()
	{
		$data	= $this->_file->readString();
		if( $data )
		{
			$data = unserialize( $data );
			if( is_array( $data ) )
				$this->_queue	= $data;
		}
	}
	
	/**
	 *	Saves Queue to File.
	 *	@access		private
	 *	@return		void
	 */
	function save()
	{
		$this->_file->writeString( serialize( $this->_queue ) );	
	}
}
?>