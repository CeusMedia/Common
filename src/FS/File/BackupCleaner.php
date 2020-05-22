<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
class FS_File_BackupCleaner{

	protected $path;
	protected $prefix;
	protected $ext;
	protected $vault;

	public function __construct( $path, $prefix, $ext ){
		$this->path			= $path;
		$this->prefix		= $prefix;
		$this->ext			= preg_replace( "/^\.+/", "", $ext );
	}

	/**
	 *	@todo		kriss: 3rd parameter $predicateClass = "Alg_Validation_Predicates"
	 */
	public function filterDateTree( $dates, $filters ){
		if( !is_array( $dates ) )
			throw new InvalidArgumentException( "Dates must be an array" );
		if( !is_array( $filters ) )
			throw new InvalidArgumentException( "Filters must be an array" );
		if( !count( $filters ) )
			return $dates;
		$validator	= new Alg_Validation_PredicateValidator();
		foreach( $dates as $year => $months ){
			foreach( $months as $month => $days ){
				foreach( $days as $day => $date ){
					$time	= strtotime( $date );
					foreach( $filters as $predicate => $argument ){
						if( !$validator->validate( $date, $predicate, $argument ) ){
							unset( $dates[$year][$month][$day] );
							if( !count( $dates[$year][$month] ) ){
								unset( $dates[$year][$month] );
								if( !count( $dates[$year] ) )
									unset( $dates[$year] );
							}
						}
					}
				}
			}
		}
		return $dates;
	}

	public function getDateTree(){
		$dates	= array();
		foreach( $this->index() as $date ){
			$time	= strtotime( $date );
			$year	= (int) date( "Y", $time );
			$month	= (int)	date( "m", $time );
			$day	= (int) date( "d", $time );
			if( !isset( $dates[$year] ) )
				$dates[$year]	= array();
			if( !isset( $dates[$year][$month] ) )
				$dates[$year][$month]	= array();
			$dates[$year][$month][$day]	= $date;
			ksort( $dates[$year][$month] );
			ksort( $dates[$year] );
			ksort( $dates );
		}
		return $dates;
	}

	public function index(){
		$dates	= array();
		$regExp	= "/^".$this->prefix.".+\.".$this->ext."$/";
		$index	= new FS_File_RegexFilter( $this->path, $regExp );
		foreach( $index as $entry ){
			$regExp		= "/^".$this->prefix."([0-9-]+)\.".$this->ext."$/";
			$dates[]	= preg_replace( $regExp, "\\1", $entry->getFilename() );
		}
		return $dates;
	}

	/**
	 *	Removes all files except the last of each month.
	 *	@access		public
	 *	@param		array		$filters	List of filters to apply on dates before
	 *	@param		boolean		$verbose	Flag: show whats happening, helpful for test mode, default: FALSE
	 *	@param		boolean		$testOnly	Flag: no real actions will take place, default: FALSE
	 *	@return		void
	 */
	public function keepLastOfMonth( $filters = array(), $verbose = FALSE, $testOnly = FALSE ){
		$dates	= $this->filterDateTree( $this->getDateTree(), $filters );
		foreach( $dates as $year => $months ){
			if( $verbose )
				remark( "..Year: ".$year );
			foreach( $months as $month => $days ){
				if( $verbose )
					remark( "....Month: ".$month );
				$keep	= array_pop( $days );
				if( $verbose )
					remark( "......Keep: ".$this->path.$this->prefix.$keep.".".$this->ext );
				foreach( $days as $day => $date ){
					if( $this->vault ){
						$fileSource	= $this->path.$this->prefix.$date.".".$this->ext;
						$fileTarget	= $this->vault.$this->prefix.$date.".".$this->ext;
						if( $verbose )
							remark( "......Move: ".$fileSource." to  ".$fileTarget );
						if( !$testOnly )
							rename( $fileSource, $fileTarget );
					}
					else{
						$fileName	= $this->path.$this->prefix.$date.".".$this->ext;
						if( $verbose )
							remark( "......Delete: ".$fileName );
						if( !$testOnly )
							unlink( $fileName );
					}
				}
			}
		}
	}

	public function keepOnlyLastMonths( $months ){
		$dates	= $this->filterDateTree( $this->getDateTree(), array() );
		print_m( $dates );
		die;
	}

	public function setVault( $path ){
		$this->vault	= $path;
	}
}
?>
