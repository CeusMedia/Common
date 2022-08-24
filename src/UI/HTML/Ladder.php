<?php
/**
 *	Builds HTML and JavaScript code for UI Component 'Ladder'.
 *
 *	Copyright (c) 2009-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://ceusmedia.de/demos/cmClasses/UI_HTML_Ladder
 *	@since			0.6.8
 */

namespace CeusMedia\Common\UI\HTML;

/**
 *	Builds HTML and JavaScript code for UI Component 'Ladder'.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://ceusmedia.de/demos/cmClasses/UI_HTML_Ladder
 *	@since			0.6.8
 */
class Ladder
{
	protected $steps	= [];
	protected $id		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id			ID of Ladder HTML Container
	 *	@return		void
	 */
	public function __construct( $id )
	{
		$this->id	= $id;
	}

	/**
	 *	Adds a Step on the Ladder.
	 *	@access		public
	 *	@param		string		$label		Label of Step
	 *	@param		string		$content	Content of Step
	 *	@return		void
	 */
	public function addStep( $label, $content )
	{
		$this->steps[]	= array(
			'label'		=> $label,
			'content'	=> $content,
		);
	}

	/**
	 *	Builds and returns HTML Code of Ladder.
	 *	@access		public
	 *	@return		string
	 */
	public function buildHtml()
	{
		$list	= [];
		$divs	= [];
		foreach( $this->steps as $nr => $step )
		{
			$id		= $this->id."_link".$nr;
			$list[]	= Elements::ListItem( $step['label'], 0, array( 'id' => $id ) );
			$id		= $this->id."_".$nr;
			$divs[] = Tag::create( 'div', $step['content'], array( 'id' => $id ) );
		}
		$list	= Elements::unorderedList( $list );
		$divs	= implode( "\n", $divs );
		$div	= Tag::create( 'div', "\n".$list.$divs."\n", array( 'id' => $this->id ) );
		return $div;
	}

	/**
	 *	Builds and returns JavaScript Code of Ladder.
	 *	@access		public
	 *	@return		string
	 */
	public function buildScript()
	{
		return JQuery::buildPluginCall( 'cmLadder', '#'.$this->id );
	}
}
