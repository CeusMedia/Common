<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Net.Service.Response
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		Net.Service.Response
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@deprecated		moved to cmModules::ENS
 *	@todo			to be removed in 0.7.3
 */
class Net_Service_Response_TodoLister extends Net_Service_Response
{
	public function getTodoFiles( $format, $paths, $refresh = FALSE, $withLines = FALSE, $additionalPatterns = array() )
	{
		$files	= array();
		foreach( $paths as $path )
		{
			$lister	= $this->getLister( $paths, $refresh, $additionalPatterns );
			$files	+= $lister->getList( $withLines );
		}
		return $this->getPhp( $files );
	}
	
	public function getTodoStats( $format, $paths, $refresh = FALSE, $additionalPatterns = array() )
	{
		$count	= 0;
		$found	= 0;
		$todos	= 0;
		$lines	= 0;
		foreach( $paths as $path )
		{
			$lister	= $this->getLister( $path, $refresh, $additionalPatterns );
			$count	+= $lister->getNumberScanned();
			$found	+= $lister->getNumberFound();
			$todos	+= $lister->getNumberTodos();
			$lines	+= $lister->getNumberLines();
		}
		$data	= array(
			'count'	=> $count,
			'found'	=> $found,
			'todos'	=> $todos,
			'lines'	=> $lines,
		);
		return $this->getPhp( $data );
	}
	
	public function getTodoIndicator( $format, $paths, $refresh = FALSE, $additionalPatterns = array() )
	{
		$lister	= $this->getLister( $path, $refresh, $additionalPatterns );
		$count		= $lister->getNumberScanned();
		$found		= $lister->getNumberFound();
		$indicator	= new UI_HTML_Indicator();
		$indicator	= $indicator->build( $count - $found, $count, 200 );
		return $indicator;
	}
	
	public function reflectParameters( $format )
	{
		$request	= new Net_HTTP_Request_Receiver();
		return $this->getPhp( $request->getAll() );
	}
	
	private function getLister( $path, $refresh = FALSE, $additionalPatterns = array() )
	{
		$hash		= md5( $path );
		$fileName	= $hash.".cache";
		if( !$refresh && file_exists( $fileName ) )
			return unserialize( file_get_contents( $fileName ) );
		$lister	= new File_RecursiveTodoLister( array(), $additionalPatterns );
		$lister->scan( $path );
		file_put_contents( $fileName, serialize( $lister ) );
		return $lister;
	}	
}
?>