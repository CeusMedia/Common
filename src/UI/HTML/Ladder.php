<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builds HTML and JavaScript code for UI Component 'Ladder'.
 *
 *	Copyright (c) 2009-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://ceusmedia.de/demos/cmClasses/UI_HTML_Ladder
 */

namespace CeusMedia\Common\UI\HTML;

/**
 *	Builds HTML and JavaScript code for UI Component 'Ladder'.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://ceusmedia.de/demos/cmClasses/UI_HTML_Ladder
 */
class Ladder
{
	protected array $steps	= [];
	protected ?string $id		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id			ID of Ladder HTML Container
	 *	@return		void
	 */
	public function __construct( string $id )
	{
		$this->id	= $id;
	}

	/**
	 *	Adds a Step on the Ladder.
	 *	@access		public
	 *	@param		string		$label		Label of Step
	 *	@param		string		$content	Content of Step
	 *	@return		self
	 */
	public function addStep( string $label, string $content ): self
	{
		$this->steps[]	= array(
			'label'		=> $label,
			'content'	=> $content,
		);
		return $this;
	}

	/**
	 *	Builds and returns HTML Code of Ladder.
	 *	@access		public
	 *	@return		string
	 */
	public function buildHtml(): string
	{
		$list	= [];
		$divs	= [];
		foreach( $this->steps as $nr => $step ){
			$id		= $this->id."_link".$nr;
			$list[]	= Elements::ListItem( $step['label'], 0, ['id' => $id] );
			$id		= $this->id."_".$nr;
			$divs[] = Tag::create( 'div', $step['content'], ['id' => $id] );
		}
		$list	= Elements::unorderedList( $list );
		$divs	= implode( "\n", $divs );
		return Tag::create( 'div', "\n".$list.$divs."\n", ['id' => $this->id] );
	}

	/**
	 *	Builds and returns JavaScript Code of Ladder.
	 *	@access		public
	 *	@return		string
	 */
	public function buildScript(): string
	{
		return JQuery::buildPluginCall( 'cmLadder', '#'.$this->id );
	}
}
