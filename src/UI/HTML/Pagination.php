<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Pagination System for limited Tables and Lists.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

use CeusMedia\Common\ADT\OptionObject as OptionObject;
use InvalidArgumentException;

/**
 *	Pagination System for limited Tables and Lists.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Pagination extends OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( array $options = [] )
	{
		parent::__construct();
		if( !is_array( $options ) )
			throw new InvalidArgumentException( 'Option map is not an array' );
		$defaultOptions	= [
			'uri'			=> "./",
			'param'			=> [],
			'coverage'		=> 10,
			'showMore'		=> TRUE,
			'showPrevNext'	=> TRUE,
			'showFirstLast'	=> TRUE,
			'keyRequest'	=> "?",
			'keyParam'		=> "&",
			'keyAssign'		=> "=",
			'keyOffset'		=> "offset",
			'classList'		=> "pagination buttons",
			'classItem'		=> "",
			'classExtreme'	=> "extreme",
			'classSkip'		=> "skip",
			'classPage'		=> "",
			'classCurrent'	=> "current",
			'classMore'		=> "more",
			'classDisabled'	=> "disabled",
			'textFirst'		=> "&laquo;",
			'textPrevious'	=> "&lsaquo;",
			'textNext'		=> "&rsaquo;",
			'textLast' 		=> "&raquo;",
			'textMore'		=> "&minus;"
		];

//  --  LEFT JOIN  --  //
#
#		foreach( $defaultOptions as $defaultKey => $defaultValue )
#			if( array_key_exists( $defaultKey, $options ) )
#				$this->setOption( $option[$defaultKey] : $defaultValue );

		$options	= array_merge( $defaultOptions, $options );
		foreach( $options as $key => $value )
			$this->setOption( $key, $value );
	}

	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int			$amount			Total amount of entries
	 *	@param		int			$limit			Maximal amount of displayed entries
	 *	@param		int			$offset			Currently offset entries
	 *	@return		string
	 */
	public function build( int $amount, int $limit, int $offset = 0 ): string
	{
		//  reset invalid negative values
		$amount	= max( 0, $amount );
		$limit	= max( 0, $limit );
		$offset	= max( 0, $offset );

		if( $limit === 0 || $limit > $amount )
			return '';

		//  synchronise invalid offsets
		if( 0 !== $offset % $limit )
			$offset		= (int) ceil( $offset / $limit ) * $limit;

		//  current page
		$here		= (int) ceil( $offset / $limit );
		//  pages before
		$before		= $offset / $limit;

		$pages	= [];
		$cover			= $this->getOption( 'coverage' );
		$showMore		= $this->getOption( 'showMore' );
		$showFirstLast	= $this->getOption( 'showFirstLast' );
		$showPrevNext	= $this->getOption( 'showFirstLast' );

		//  --  FIRST PAGE --  //
		//  show first link
		if( $showFirstLast ){
			//  first link if not at first page
			if( $before )
				$pages[]	= $this->buildButton( 'textFirst', 'classExtreme', 0 );
			else
				//  first link disabled if at first page
				$pages[]	= $this->buildButton( 'textFirst', 'classExtreme classDisabled' );
		}

			//  --  PREVIOUS PAGE --  //
		if( $showPrevNext ){
			$previous	= ( $here - 1 ) * $limit;
			if( $before )
				//  previous page
				$pages[]	= $this->buildButton( 'textPrevious', 'classSkip', $previous );
			else
				//  previous page
				$pages[]	= $this->buildButton( 'textPrevious', 'classSkip classDisabled' );
		}

		if( $before ){
			//  --  MORE PAGES  --  //
			//  more previous pages
			if( $showMore && $before > $cover )
				$pages[]	= $this->buildButton( 'textMore', 'classMore' );

			//  --  PREVIOUS PAGES --  //
			//  previous pages
			for( $i=max( 0, $before - $cover ); $i<$here; $i++ )
				$pages[]	= $this->buildButton( (string) ( $i + 1 ), 'classPage', $i * $limit );
/*				if( $this->getOption( 'keyPrevious' ) ){
				$latest	= count( $pages ) - 1;
				$button	= $this->buildButton( $i, 'classLink', ($i-1) * $limit, 'previous' );
				$pages[$latest]	= $button;
			}*/
		}

		//  page here
		$pages[]	= $this->buildButton( (string) ( $here + 1 ), 'classCurrent' );
		//  pages after
		$after	= (int) ceil( ( ( $amount - $limit ) / $limit ) - $here );
		if( $after ){
			//  --  NEXT PAGES --  //
			//  after pages
			for( $i=0; $i<min( $cover, $after ); $i++ ){
				$offset		= ( $here + $i + 1 ) * $limit;
				$pages[]	= $this->buildButton( (string) ( $here + $i + 2 ), 'classPage', $offset );
			}

			//  --  MORE PAGES --  //
			//  more after pages
			if( $showMore && $after > $cover )
				$pages[]	= $this->buildButton( 'textMore', 'classMore' );
		}

		//  --  NEXT PAGE --  //
		if( $showPrevNext ){
			$offset		= ( $here + 1 ) * $limit;
			if( $after )
				//  next link if not at last page
				$pages[]	= $this->buildButton( 'textNext', 'classSkip', $offset );
			else
				//  next link disabled it at last page
				$pages[]	= $this->buildButton( 'textNext', 'classSkip disabled' );
		}

		//  --  LAST PAGE --  //
		if( $showFirstLast ){
			$offset		= ( $here + $after ) * $limit;
			//  last page
			if( $after )
				$pages[]	= $this->buildButton( 'textLast', 'classExtreme', $offset );
			else
				//  last link disabled if at last page
				$pages[]	= $this->buildButton( 'textLast', 'classExtreme disabled' );
		}
		return Elements::unorderedList( $pages, 0, ['class' => $this->getOption( 'classList' )] );
	}

	/**
	 *	Builds Paging Button.
	 *	@access		protected
	 *	@param		string			$text			Text or HTML of Paging Button Span
	 *	@param		string|NULL		$class			Additive Style Class of Paging Button Span
	 *	@param		int|NULL		$offset			Currently offset entries
	 *	@return		string
	 */
	protected function buildButton( string $text, ?string $class, ?int $offset = NULL ): string
	{
		$label	= $this->hasOption( $text ) ? $this->getOption( $text ) : $text;
		if( empty( $label ) )
			throw new InvalidArgumentException( 'Button Label cannot be empty' );
		$classes	= [];
		foreach( explode( " ", $class ) as $class )
			$classes[]	= ( $class && $this->hasOption( $class ) ) ? $this->getOption( $class ) : $class;
		$class	= implode( " ", $classes );
		if( $offset !== NULL ){
			$url		= $this->buildLinkUrl( $offset );
#			if( $label == $text )
#				$linkClass	.= " page";
			$label		= Elements::Link( $url, $label, $class );
		}
		else
			$label	= Tag::create( "span", $label, ['class' => $class] );
#		if( $label == $text )
#			$spanClass	.= " page";
		return $this->buildItem( $label);
	}

	/**
	 *	Builds List Item of Pagination Link.
	 *	@access		protected
	 *	@param		string			$text			Text or HTML of Paging Button Span
	 *	@param		string|NULL		$class			Additive Style Class of Paging Button Span
	 *	@return		string
	 */
	protected function buildItem( string $text, ?string $class = NULL ): string
	{
		$list	= [];
		if( $class )
			$list[]	= $class;
		return Elements::ListItem( $text, 0, $list );
	}

	/**
	 *	Builds Link URL of Paging Button.
	 *	@access		protected
	 *	@param		int			$offset			Currently offset entries
	 *	@return		string
	 */
	protected function buildLinkUrl( int $offset ): string
	{
		$param	= $this->getOption( 'param' );
		$param[$this->getOption( 'keyOffset' )] = $offset;
		$list	= [];
		foreach( $param as $key => $value )
			$list[]	= $key.$this->getOption( 'keyAssign' ).$value;
		$param	= implode( $this->getOption( 'keyParam' ), $list );
		return  $this->getOption( 'uri' ).$this->getOption( 'keyRequest' ).$param;
	}
}
