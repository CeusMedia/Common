<?php
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.file.arc.GzipFile' );
import( 'de.ceus-media.file.arc.BzipFile' );
import( 'de.ceus-media.net.http.PartitionSession' );
/**
 *	Session storage in File with compression support.
 *	@package		net
 *	@subpackage		http
 *	@extends		Net_HTTP_PartitionSession
 *	@uses			File
 *	@uses			GzipFile
 *	@uses			BzipFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.1
 */
/**
 *	Session storage in File with compression support.
 *	@package		net
 *	@subpackage		http
 *	@extends		Net_HTTP_PartitionSession
 *	@uses			File
 *	@uses			GzipFile
 *	@uses			BzipFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.1
 */
class Net_HTTP_FileSession extends Net_HTTP_PartitionSession
{
	/**	@var	string		$_session_path		Path to store File Session in */
	var $_session_path;
	/**	@var	string		$_session_prefix		Prefix of Session File */
	var $_session_prefix;	
	/**	@var	bool			$_compression		Turns compression on and off */
	var $_compression = false;
	/**	@var	array		_compression_types	Array of supported compression types */
	var $_compression_types = array( "gzip", "bzip" );

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to store File Session in
	 *	@param		string		$prefix			Prefix of Session File
	 *	@param		string		$partition			Partition of Session Data
	 *	@param		string		$session_name	Name of Session ID
	 *	@return		void
	 */
	public function __construct( $path, $prefix = '', $partition = 'default', $session_name = 'sid' )
	{
		$this->_session_path	= $path;
		$this->_session_prefix	= $prefix;
		parent::__construct();
	}
	
	/**
	 *	Returns current compression type.
	 *	@access		public
	 *	@return		string
	 */
	function getCompression()
	{
		return $this->_compression;	
	}

	/**
	 *	Opens Session.
	 *	@access		public
	 *	@param		string		$partition			Partition of Session Data
	 *	@param		string		$session_name	Name of Session ID
	 *	@return		void
	 */
	function openSession( $partition = 'default', $session_name = 'sid' )
	{
		$this->_setPartition( $partition );
		$this->_setSessionName( $session_name );
		session_set_save_handler(
			array( &$this, "open"),
			array( &$this, "close"),
			array( &$this, "read"),
			array( &$this, "write"),
			array( &$this, "destroy"),
			array( &$this, "gc")
		);
		$ip = getEnv( 'REMOTE_ADDR' );
		session_start();
		$this->_session_data =& $_SESSION;
		if( !isset( $this->_session_data['ip'] ) )
			$this->_session_data['ip'] = $ip;
		else if( $this->_session_data['ip'] != $ip )								//  HiJack Attempt
		{
			session_regenerate_id();
			$this->_session_data =& $_SESSION;
			foreach( $this->_session_data as $key => $value )
				unset( $this->_session_data[$key] );
			$this->_session_data['ip'] = $ip;
		}
		$this->_partition_data =& $this->_session_data['partitions'][$this->getPartition()];
	}

	/**
	 *	Sets compression type.
	 *	@access		public
	 *	@param		string		$compression		Compression type
	 *	@return		void
	 */
	function setCompression( $compression )
	{
		if( !$this->_open )
			if( in_array( $compression, $this->_compression_types ) )
				$this->_compression = $compression;
		else
			trigger_error( "Cannot set compression to '".$compression."'. Session is already open.", E_USER_WARNING );
	}

	/**
	 *	Handler function to open Session.
	 *	@access		public
	 *	@return		bool
	 */
	function open( $session_name )
	{
		return true;
	}

	/**
	 *	Handler function to close Session.
	 *	@access		public
	 *	@return		bool
	 */
	function close()
	{
		return true;
	}

	/**
	 *	Handler function to read from Session.
	 *	@access		public
	 *	@param		string		$id			Session ID
	 *	@return		string
	 */
	function read( $id )
	{
		$uri	= $this->_getURI( $id );
		$file	= $this->_getFileHandler( $uri );
		if( $file->exists() && $content = $file->readString() )
			return $content;
		return "";
	}

	/**
	 *	Handler function to write to Session.
	 *	@access		public
	 *	@param		string		$id			Session ID
	 *	@return		bool
	 */
	function write( $id, $sess_data )
	{
		$uri	= $this->_getURI( $id );
		$file	= $this->_getFileHandler( $uri );
		print_m( $file );
		if( $file->writeString( $sess_data ) )
			return true;
		return false;
	}

	/**
	 *	Destruction of Session by deletion of Session File.
	 *	@access		public
	 *	@param		string		$id			Session ID
	 *	@return		bool
	 */
	function destroy( $id )
	{
		$uri	= $this->_getURI( $id );
		return( @unlink( $uri ) );
	}

	/**
	 *	No yet implemented.
	 *	@access		public
	 *	@return		bool
	 */
	function gc( $maxlifetime )
	{
		return true;
	}
	
	/**
	 *	Returns URI of Session.
	 *	@access		public
	 *	@param		string		$id			Session ID
	 *	@return		string
	 */
	function _getURI( $id )
	{
		$uri	= $this->_session_path."/".$this->_session_prefix.$id;
		return $uri;
	}
	
	/**
	 *	Returns File for Session handling.
	 *	@access		public
	 *	@return		File
	 */
	function _getFileHandler( $filename )
	{
		if( $this->_compression  == 'gzip')
			$file = new GzipFile( $filename, 0777 );
		else if( $this->_compression  == 'bzip')
			$file = new BzipFile( $filename, 0777 );
		else
			$file = new File( $filename, 0777 );
		return $file;
	}
}
?>