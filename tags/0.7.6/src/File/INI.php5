<?php
/**
 *	...
 *	@category		cmClasses
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_INI {

	protected $sections		= NULL;
	protected $pairs		= NULL;
	public $indentTabs		= 8;
	public $lengthTab		= 4;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name
	 *	@param		boolean		$useSections	Flag: use Sections
	 *	@return		void
	 */
	public function __construct( $fileName, $useSections = FALSE, $mode = NULL )
	{
		$this->fileName	= $fileName;
		$this->mode		= $mode;
		if( file_exists( $fileName ) )
			$this->read( $useSections );
	}

	/**
	 *	Returns Value by its Key.
	 *	@access		public
	 *	@param		string		$key			Key
	 *	@param		boolean		$section		Flag: use Sections
	 *	@return		string|NULL	Value if set, NULL otherwise
	 */
	public function get( $key, $section = NULL )
	{
		if( !is_null( $this->sections ) && $this->sections->has( $section ) )
			return $this->sections->get( $section )->get( $key );
		if( !is_null( $this->pairs ) )
			return $this->pairs->get( $key );
		return NULL;
	}

	/**
	 *	Returns Value by its Key.
	 *	@access		public
	 *	@param		string		$key			Key
	 *	@param		boolean		$section		Flag: use Sections
	 *	@return		boolean
	 */
	public function has( $key, $section = NULL )
	{
		if( !is_null( $this->sections ) && $this->sections->has( $section ) )
			return $this->sections->get( $section )->has( $key );
		if( !is_null( $this->pairs ) )
			return $this->pairs->has( $key );
		return NULL;
	}

	protected function read( $useSections = FALSE )
	{
		if( $useSections )
		{
			$this->sections	= new ADT_List_Dictionary();
			foreach( parse_ini_file( $this->fileName, TRUE ) as $section => $pairs )
			{
				$data	= $this->sections->get( $section );
				if( is_null( $data ) )
					$data	= new ADT_List_Dictionary();
				foreach( $pairs as $key => $value )
					$data->set( $key, $value, TRUE );
				$this->sections->set( $section, $data, TRUE );
			}
		}
		else
		{
			$data			= parse_ini_file( $this->fileName, FALSE );
			$this->pairs	= new ADT_List_Dictionary( $data );
		}
	}

	public function remove( $key, $section = NULL )
	{
		$result	= NULL;
		if( !is_null( $this->sections ) && $this->sections->has( $section ) )
			$result	= $this->sections->get( $section )->remove( $key );
		else if( !is_null( $this->pairs ) )
			$result	= $this->pairs->remove( $key );
		if( $result )
			$this->write();
		return $result;
	}

	public function set( $key, $value, $section = NULL )
	{
		$result	= NULL;
		if( !is_null( $this->sections ) && $this->sections->has( $section ) )
			$result	= $this->sections->get( $section )->set( $key, $value );
		else
		{
			if( is_null( $this->pairs ) )
				$this->pairs	= new ADT_List_Dictionary();
			$result	= $this->pairs->set( $key, $value );
		}
		if( $result )
			$this->write();
		return $result;
	}

	protected function write()
	{
		$list	= array();
		if( !is_null( $this->sections ) )
		{
			foreach( $this->sections as $section => $items )
			{
				$list[]	= '['.$section.']';
				foreach( $items as $key => $value )
				{
					$indent	= max( $this->indentTabs - ceil( ( strlen( $key ) + 1 ) / $this->lengthTab ), 1 );
					if( is_bool( $value ) )
						$value	= $value ? "yes" : "no";
					else if( !is_int( $value ) )
						$value	= '"'.$value.'"';
					$list[]	= $key.str_repeat( "\t", $indent ).'= '.$value;
				}
				$list[]	= '';
			}
		}
		else if( !is_null( $this->pairs ) )
		{
			foreach( $this->pairs as $key => $value )
			{
				$indent	= max( $this->indentTabs - ceil( ( strlen( $key ) + 1 ) / $this->lengthTab ), 1 );
				if( is_bool( $value ) )
					$value	= $value ? "yes" : "no";
				else if( !is_int( $value ) )
					$value	= '"'.$value.'"';
				$list[]	= $key.str_repeat( "\t", $indent ).'= '.$value;
			}
		}
		return File_Writer::save( $this->fileName, join( "\n", $list ), $this->mode );
	}
}
?>
