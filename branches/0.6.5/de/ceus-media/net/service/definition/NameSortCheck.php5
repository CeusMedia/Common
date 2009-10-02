<?php
/**
 *	Checks order of Services in a Service Definition File (YAML and XML).
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		net.service.definition
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.09.2008
 *	@version		0.1
 */
/**
 *	Checks order of Services in a Service Definition File (YAML and XML).
 *	@package		net.service.definition
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.09.2008
 *	@version		0.1
 */
class Net_Service_Definition_NameSortCheck
{
	private $fileName		= "";
	private $originalList	= array();
	private $sortedList		= array();
	private $compared		= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URL of Service Definition File
	 *	@return		void
	 */
 	public function __construct( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "File '".$fileName."' is not existing." );
		$this->fileName	= $fileName;
	}

	/**
	 *	Indicates whether all services are in correct order.
	 *	@access		public
	 *	@return		bool
	 */
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
	
	/**
	 *	Returns List of methods in original order.
	 *	@access		public
	 *	@return		array
	 */
	public function getOriginalList()
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->originalList;
	}

	/**
	 *	Returns List of methods in correct order.
	 *	@access		public
	 *	@return		array
	 */
	public function getSortedList()
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->sortedList;
	}
}
?>