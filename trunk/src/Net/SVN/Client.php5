<?php
class Net_SVN_Client{

	protected $user;
	protected $group;
	protected $mode;
	
	public function __construct( $path ){
		if( !file_exists( $path ) )
			throw new Exception_IO( 'Invalid path', 0, $path );
		$this->path		= realpath( $path ).'/';
		$this->pathExp	= '^('.str_replace( '/', '\/', $this->path ).')';
	}

	public function add( $path ){
		$url	= $this->path.$path;
		if( !file_exists( $url ) )
			throw new Exception_IO( 'Invalid path', 0, $path );
		$status	= @svn_add( $url );
		if( !$status )
			svn_revert( $url );
		return $status;
	}
	
	public function authenticate( $username, $password ){
		svn_auth_set_parameter( SVN_AUTH_PARAM_NON_INTERACTIVE, true); 
		svn_auth_set_parameter( SVN_AUTH_PARAM_DEFAULT_USERNAME, $username );
		svn_auth_set_parameter( SVN_AUTH_PARAM_DEFAULT_PASSWORD, $password );
		svn_auth_set_parameter( PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true); // <--- Important for certificate issues! 
		svn_auth_set_parameter( SVN_AUTH_PARAM_NO_AUTH_CACHE, true); 
	}

	public function commit( $msg, $list ){
		foreach( $list as $nr => $item )
			$list[$nr]	= realpath( $item );
		return svn_commit( $msg, $list );
	}

	public function getRelativePath( $path ){
		$path	= str_replace( '\\', '/', $path );													//  Windows fix
		if( preg_match( '/'.$this->pathExp.'/U', $path ) )
			$path	= substr( $path, strlen( $this->path ) );
		return $path;
	}
	
	public function info( $path = '' ){
		$path	= $this->path.$path;
		return new XML_Element( `svn info $path --xml 2>&1` );
	}

	public function ls( $path, $revision = SVN_REVISION_HEAD, $recurse = FALSE ){
		$url	= $this->path.$path;
		if( !file_exists( $url ) )
			throw new Exception_IO( 'Invalid path', 0, $path );
		return svn_ls( $url, $revision, $recurse );
	}

	public function revert( $path ){
		$url	= $this->path.$path;
		if( !file_exists( $url ) )
			throw new Exception_IO( 'Invalid path', 0, $path );
		return @svn_revert( $url );
	}

	public function status( $path = '.', $flags = 0 ){
		$url		= $this->path.$path;
		if( !file_exists( $url ) )
			throw new Exception_IO( 'Invalid path', 0, $path );
		return svn_status( $url,  $flags );
	}
}
?>
