<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 */

namespace CeusMedia\Common\UI\HTML;

use InvalidArgumentException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class Table
{
	protected array $bodyRows	= [];

	protected array $footRows	= [];

	protected array $headRows	= [];

	protected ?string $summary	= NULL;

	protected array $columns	= [];

	protected ?string $caption	= NULL;

	protected ?string $class	= NULL;

	protected ?string $id		= NULL;

	public function __construct( array $attributes = [] )
	{
		foreach( $attributes as $key => $value ){
			switch( $key ){
				case 'caption':
					$this->setCaption( $value );
					break;
				case 'class':
					$this->setClass( $value );
					break;
				case 'columns':
					$this->addColumns( $value );
					break;
				case 'id':
					$this->setId( $value );
					break;
				case 'summary':
					$this->setSummary( $value );
					break;
				case 'heads':
					$this->addHeads( $value );
					break;
				case 'foots':
					$this->addFoots( $value );
					break;
				case 'rows':
					foreach( $value as $row )
					$this->addRow( $row );
					break;
			}
		}
	}

	public function addCell( string $label, array $attributes = [] ): self
	{
		if( !$this->bodyRows )
			$this->bodyRows[]	= [];
		$current	= count( $this->bodyRows ) - 1;
		if( empty( $label ) )
			$label	= "&#160;";
		$this->bodyRows[$current][]	= Tag::create( "td", $label, $attributes );
		return $this;
	}

	public function addColumn( string $column ): self
	{
		if( !$this->columns )
			$this->columns[]	= [];
		$current	= count( $this->columns ) - 1;
		$this->columns[$current][]	= $column;
		return $this;
	}

	public function addColumns( array $columns = [] ): self
	{
		$this->columns[]	= [];
		foreach( $columns as $value )
			$this->addColumn( $value );
		return $this;
	}

	public function addFoot( string $label, array $attributes = [] ): self
	{
		if( !$this->footRows )
			$this->footRows[]	= [];
		$current	= count( $this->footRows ) - 1;
		$this->footRows[$current][]	= Tag::create( "td", $label, $attributes );
		return $this;
	}

	public function addFoots( array $foots ): self
	{
		$this->footRows[]	= [];
		foreach( $foots as $key => $value ){
			if( is_int( $key ) && is_string( $value ) )
				$this->addFoot( $value );
			else if( is_string( $key ) && is_array( $value ) )
				$this->addFoot( $key, $value );
			else
				throw new InvalidArgumentException( 'Unknown format: '.gettype( $key ).' => '.gettype( $value ) );
		}
		return $this;
	}

	public function addHead( string $label, array $attributes = [] ): self
	{
		if( !$this->headRows )
			$this->headRows[]	= [];
		$current				= count( $this->headRows ) - 1;
		$attributes['scope']	= $attributes['scope'] ?? 'col';
		$tag					= Tag::create( "th", $label, $attributes );
		$this->headRows[$current][]	= $tag;
		return $this;
	}

	public function addHeads( array $heads ): self
	{
		$this->headRows[]	= [];
		foreach( $heads as $key => $value ){
			if( is_int( $key ) && is_array( $value ) )
				$this->addHeads( $value );
			else if( is_int( $key ) && is_string( $value ) )
				$this->addHead( $value );
			else if( is_string( $key ) && is_array( $value ) )
				$this->addHead( $key, $value );
			else
				throw new InvalidArgumentException( 'Unknown format: '.gettype( $key ).' => '.gettype( $value ) );
		}
		return $this;
	}

	/**
	 *	@param		array		$cells
	 *	@return		void
	 */
	public function addRow( array $cells = [] ): void
	{
		$this->bodyRows[]	= [];
		foreach( $cells as $key => $value ){
			if( is_int( $key ) && is_string( $value ) )
				$this->addCell( $value );
			else if( is_string( $key ) && is_array( $value ) )
				$this->addCell( $key, $value );
			else
				throw new InvalidArgumentException( 'Unknown format: '.gettype( $key ).' => '.gettype( $value ) );
		}
	}

	public function render( ?string $comment = "TEST" ): string
	{
		$start	= $comment ? "\n<!--  TABLE: ".$comment." >>  -->\n" : "";
		$end	= $comment ? "\n<!--  << TABLE: ".$comment."  -->\n" : "";

		//  --  TABLE HEAD  --  //
		$list	= [];
		foreach( $this->headRows as $headCells )
			$list[]	= Tag::create( "tr", "\n      ".implode( "\n      ", $headCells )."\n    " );
		$tableHead		= "\n  ".Tag::create( "thead", "\n    ".implode( "\n    ", $list )."\n  " );

		//  --  TABLE FOOT  --  //
		$list	= [];
		foreach( $this->footRows as $footCells )
			$list[]	= Tag::create( "tr", "\n      ".implode( "\n      ", $footCells )."\n    " );
		$tableFoot		= "\n  ".Tag::create( "tfoot", "\n    ".implode( "\n    ", $list )."\n  " );

		//  --  TABLE BODY  --  //
		$list	= [];
		foreach( $this->bodyRows as $bodyCells )
			$list[]	= Tag::create( "tr", "\n      ".implode( "\n      ", $bodyCells )."\n    " );
		$tableBody		= "\n  ".Tag::create( "tbody", "\n    ".implode( "\n    ", $list )."\n  " )."\n";

		//  --  COLUMN GROUP  --  //
		$list	= [];
		foreach( $this->columns as $columns )
		{
			foreach( $columns as $nr => $width )
				$columns[$nr]	= Tag::create( "col", NULL, ['width' => $width] );
			$list[]	= Tag::create( "colgroup", "\n      ".implode( "\n      ", $columns )."\n  " );
		}
		$colgroups		= "\n  ".implode( "\n  ", $list );

		$caption		= $this->caption ? "\n  ".Tag::create( 'caption', $this->caption ) : "";
		$content		= $caption.$colgroups.$tableHead.$tableFoot.$tableBody;
		$attributes		= [
			'id'		=> $this->id,
			'class'		=> $this->class,
			'summary'	=> $this->summary
		];
		$table			= Tag::create( "table", $content, $attributes );
		return $start.$table.$end;
	}

	public function setCaption( ?string $label ): self
	{
		$this->caption	= $label;
		return $this;
	}

	public function setClass( ?string $class ): self
	{
		$this->class	= $class;
		return $this;
	}

	public function setId( ?string $id ): self
	{
		$this->id	= $id;
		return $this;
	}

	public function setSummary( ?string $label ): self
	{
		$this->summary	= $label;
		return $this;
	}
}
