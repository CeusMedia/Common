<?php
/**
 *	Buffer for HTML Elements.
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Buffer for HTML Elements.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Buffer extends UI_HTML_Abstract
{
	protected $list	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$content		HTML Elements to add to Buffer
	 *	@return		void
	 */
	public function __construct()
	{
		foreach( func_get_args() as $content )
			$this->list[]	= $content;
	}

	/**
	 *	Returns rendered HTML Elements in Buffer.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$buffer	= '';
		foreach( $this->list as $content )
			$buffer	.= $this->renderInner( $content );
		return $buffer;
	}
}
?>