<?php
/**
 *	Base Folder implementation.
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version	0.4
 */
/**
 *	Base Folder implementation.
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version	0.4
 */
class Folder
{
	/**	@var		string		$path			URI of the Directory */
	protected $path;
	/**	@var		dir			$dir			Instance of PHP Class Directory */
	protected $dir;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path		URI of directory
	 *	@param		string		$create		Flag: create directory if not existing
	 *	@return		void
	 */
	public function __construct( $path, $createMode = false )
	{
		$this->path = $path;
		if( !$this->exists() )
		{
			if( !$createMode )
				throw new Exception( "Directory '".$path."' not found" );
			return $this->create( $createMode );
		}
	}

	/**
	 *	Creates the directory.
	 *	@access		public
	 *	@param		int			$mode		access in linux rights, eg. 0755
	 *	@return		bool
	 */
	public function create( $mode = 0750 )
	{
		return mkdir( $this->path, $mode );
	}

	/**
	 *	Returns the directory name.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 *	Proving existence of the directory.
	 *	@access		public
	 *	@return		bool
	 */
	public function exists()
	{
		return is_dir( $this->path );
	}

	/**
	 *	Removing the directory.
	 *	@access		public
	 *	@return		bool
	 */
	public function remove()
	{
		return rmdir( $this->path );
	}
	
	/**
	 *	Changes current working Directory.
	 *	@access		public
	 *	@param		string		$path			URI of Directory
	 *	@return		bool
	 */
	public function chDir( $path = false )
	{
		if ($path)
		{
			$f = new Folder($path);
			return $f->chDir ();
		}
		else
			return chDir ($this->path);
	}
}
?>