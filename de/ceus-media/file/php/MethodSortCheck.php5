<?php
class File_PHP_MethodSortCheck
{
	private $fileName		= "";
	private $originalList	= array();
	private $sortedList		= array();
	private $compared		= FALSE;

	public function __construct( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "File '".$fileName."' is not existing." );
		$this->fileName	= $fileName;
	}

	public function compare()
	{
		$this->compared	= TRUE;
		$content	= file_get_contents( $this->fileName );
		$content	= preg_replace( "@/\*.+\*/@sU", "", $content );
		$lines		= explode( "\n", $content );
		foreach( $lines as $line )
		{
			if( preg_match( "@^(#|//)@", trim( $line ) ) )
				continue;
			$matches	= array();
			preg_match_all( "@function\s*([a-z]\S+)\s*\(@i", $line, $matches, PREG_SET_ORDER );
			foreach( $matches as $match )
				$this->originalList[] = $match[1];
		}
		$this->sortedList	= $this->originalList;
		natCaseSort( $this->sortedList );
		return $this->sortedList === $this->originalList;
	}
	
	public function getOriginalList()
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->originalList;
	}

	public function getSortedList()
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->sortedList;
	}
}
?>