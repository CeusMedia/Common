<?php
import( 'de.ceus-media.net.service.definition.NameSortCheck' );
import( 'de.ceus-media.file.RecursiveRegexFilter' );
class Folder_ServiceNameSortCheck
{
	protected $count	= 0;
	protected $found	= 0;
	protected $files	= array();

	public function __construct( $path, $extensions = array( "xml", "yaml", "json" ) )
	{
		$this->path			= $path;
		$this->extensions	= $extensions;
	}

	public function checkOrder()
	{
		$this->count	= 0;
		$this->found	= 0;
		$this->files	= array();
		$extensions		= implode( "|", $this->extensions );
		$pattern		= "@\.(".$extensions.")$@";
		$filter			=  new File_RecursiveRegexFilter( $this->path, $pattern );
		foreach( $filter as $entry )
		{
			if( preg_match( "@^(_|\.)@", $entry->getFilename() ) )
				continue;
			$this->count++;
			$check	= new Net_Service_Definition_NameSortCheck( $entry->getPathname() );
			if( $check->compare() )
				continue;
			$this->found++;
			$list1	= $check->getOriginalList();
			$list2	= $check->getSortedList();
			do{
				$line1 = array_shift( $list1 );
				$line2 = array_shift( $list2 );
				if( $line1 != $line2 )
					break;
			}
			while( count( $list1 ) && count( $list2 ) );
			$fileName	= substr( $entry->getPathname(), strlen( $this->path ) + 1 );
			$this->files[$entry->getPathname()]	= array(
				'fileName'	=> $fileName,
				'pathName'	=> $entry->getPathname(),
				'original'	=> $line1,
				'sorted'	=> $line2,
			);
		}
		return !$this->found;
	}

	/**
	 *	Returns Number of scanned Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 *	Returns an Array of Files and found Order Deviations within.
	 *	@access		public
	 *	@return		array
	 */
	public function getDeviations()
	{
		return $this->files;
	}
		
	/**
	 *	Returns Number of found Files with Order Deviations.
	 *	@access		public
	 *	@return		int
	 */
	public function getFound()
	{
		return $this->found;
	}

	/**
	 *	Returns Percentage Value of Ratio between Number of found and scanned Files.
	 *	@access		public
	 *	@param		int			$accuracy		Number of Digits after Dot
	 *	@return		float
	 */
	public function getPercentage( $accuracy = 0 )
	{
		if( !$this->count )
			return 0;
		return round( $this->found / $this->count * 100, $accuracy );
	}
	
	/**
	 *	Returns Ratio between Number found and scanned Files.
	 *	@access		public
	 *	@return		float
	 */
	public function getRatio()
	{
		if( !$this->count )
			return 0;
		return $this->found / $this->count;
	}
}
?>