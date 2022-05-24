<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
namespace CeusMedia\Common\CLI\Fork\Server;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
class Dynamic extends Abstraction
{
	protected $scriptFile;

	protected function handleRequest( $request )
	{
		if( !$this->scriptFile )
			return "No Script for Dynamic Server set.";
		if( !file_exists( $this->scriptFile ) )
			return "Script for Dynamic Server is not existing.";

		return require_once( $this->scriptFile );
	}

	public function setScriptFile( $scriptFile )
	{
		$this->scriptFile	= $scriptFile;
	}
}
