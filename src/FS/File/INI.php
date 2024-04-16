<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use CeusMedia\Common\ADT\Collection\Dictionary;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class INI
{
	protected string $fileName;
	protected int $mode;

	/**	@var	Dictionary|NULL			$sections		... */
	protected ?Dictionary $sections		= NULL;

	/**	@var	Dictionary|NULL			$pairs			... */
	protected ?Dictionary $pairs		= NULL;

	public int $indentTabs				= 8;
	public int $lengthTab				= 4;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name
	 *	@param		boolean		$useSections	Flag: use Sections
	 *	@param		integer		$mode			UNIX rights for chmod() as octal integer (starting with 0), default: 0640
	 *	@return		void
	 */
	public function __construct( string $fileName, bool $useSections = FALSE, int $mode = 0640 )
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
	 *	@param		string|NULL	$section		...
	 *	@return		string|NULL	Value if set, NULL otherwise
	 */
	public function get( string $key, ?string $section = NULL ): ?string
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
	 *	@param		string|NULL	$section		...
	 *	@return		boolean
	 */
	public function has( string $key, ?string $section = NULL ): bool
	{
		if( !is_null( $this->sections ) && $this->sections->has( $section ) )
			return $this->sections->get( $section )->has( $key );
		if( !is_null( $this->pairs ) )
			return $this->pairs->has( $key );
		return FALSE;
	}

	protected function read( bool $useSections = FALSE ): void
	{
		if( $useSections ){
			$this->sections	= new Dictionary();
			foreach( parse_ini_file( $this->fileName, TRUE ) as $section => $pairs ){
				$data	= $this->sections->get( $section );
				if( is_null( $data ) )
					$data	= new Dictionary();
				foreach( $pairs as $key => $value )
					$data->set( $key, $value );
				$this->sections->set( $section, $data );
			}
		}
		else{
			$data			= parse_ini_file( $this->fileName, FALSE );
			$this->pairs	= new Dictionary( $data );
		}
	}

	public function remove( string $key, ?string $section = NULL ): bool
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

	public function set( string $key, string|int|float $value, ?string $section = NULL ): bool
	{
		if( !is_null( $this->sections ) && $this->sections->has( $section ) )
			$result	= $this->sections->get( $section )->set( $key, $value );
		else{
			if( is_null( $this->pairs ) )
				$this->pairs	= new Dictionary();
			$result	= $this->pairs->set( $key, $value );
		}
		if( $result )
			$this->write();
		return $result;
	}

	protected function write(): int
	{
		$list	= [];
		if( !is_null( $this->sections ) ){
			foreach( $this->sections as $section => $items ){
				$list[]	= '['.$section.']';
				foreach( $items as $key => $value )
					$this->parseLine( $list, $key, $value);
				$list[]	= '';
			}
		}
		else if( !is_null( $this->pairs ) )
			foreach( $this->pairs as $key => $value )
				$this->parseLine( $list, $key, $value );
		return Writer::save( $this->fileName, join( "\n", $list ), $this->mode );
	}

	/**
	 * @param		array				$list
	 * @param		string|int|float	$key
	 * @param		mixed				$value
	 * @return		void
	 */
	protected function parseLine( array & $list, string|int|float $key, mixed $value ): void
	{
		$indent	= max( $this->indentTabs - ceil( ( strlen( $key ) + 1 ) / $this->lengthTab ), 1 );
		if( is_bool( $value ) )
			$value	= $value ? "yes" : "no";
		else if( !is_int( $value ) )
			$value	= '"'.$value.'"';
		$list[]	= $key.str_repeat( "\t", $indent ).'= '.$value;
	}
}
