<?php
class Net_Service_Definition_NameSortCheck
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
		$this->originalList	= array();
		$this->compared		= TRUE;
		$content	= file_get_contents( $this->fileName );
		$info	= pathinfo( $this->fileName );
		switch( $info['extension'] )
		{
			case 'yaml':	$regEx	= "@^  ([a-z]+)[:]@i";
							break;
			case 'xml':		$regEx	= "@^\s*<service .*name=\"(\w+)\"@i";
							$content	= preg_replace( "@<!--.*-->@u", "", $content );
							break;
			default:		throw new Exception( 'Extension "'.$info['extension'].'" is not supported.' );
		}
	
	
		$lines		= explode( "\n", $content );
		foreach( $lines as $line )
		{
			$matches	= array();
			preg_match_all( $regEx, $line, $matches, PREG_SET_ORDER );
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