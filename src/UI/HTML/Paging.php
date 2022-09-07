<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Paging System for Lists.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 */

namespace CeusMedia\Common\UI\HTML;

use CeusMedia\Common\ADT\OptionObject as OptionObject;
use InvalidArgumentException;

/**
 *	Paging System for Lists.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Paging extends OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setOption( 'uri',			"./" );
		$this->setOption( 'param',			array() );
		$this->setOption( 'coverage',		"2" );
		$this->setOption( 'extreme',		"1" );
		$this->setOption( 'more',			"1" );
		$this->setOption( 'linebreak',		"\n" );
		$this->setOption( 'key_request',	"?" );
		$this->setOption( 'key_param',		"&" );
		$this->setOption( 'key_assign',		"=" );
		$this->setOption( 'key_offset',		"offset" );
		$this->setOption( 'class_span',		"pagingSpan" );
		$this->setOption( 'class_link',		"pagingLink" );
		$this->setOption( 'class_text',		"pagingText" );

		$this->setOption( 'text_first',		"<<" );
		$this->setOption( 'text_previous',	"<" );
		$this->setOption( 'text_next',		">" );
		$this->setOption( 'text_last', 		">>" );
		$this->setOption( 'text_more',		".." );

		$this->setOption( 'key_first', 		'' );
		$this->setOption( 'key_previous',	'' );
		$this->setOption( 'key_next',		'' );
		$this->setOption( 'key_last',		'' );
	}

	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int			$amount			Total amount of entries
	 *	@param		int			$limit			Maximal amount of displayed entries
	 *	@param		int			$offset			Currently offset entries
	 *	@return		string
	 */
	public function build( int $amount, int $limit, int $offset ): string
	{
		$pages	= [];
		if( $limit && $amount > $limit ){
			$cover		= $this->getOption( 'coverage' );
			$extreme	= $this->getOption( 'extreme' );
			$more		= $this->getOption( 'more' );
			//  reset invalid negative offsets
			$offset		= ( $offset >= 0 ) ? $offset : 0;
			//  synchronise invalid offsets
			$offset		= ( 0 !== $offset % $limit ) ? ceil( $offset / $limit ) * $limit : $offset;
			//  current page
			$here		= ceil( $offset / $limit );
			//  pages before
			$before		= $offset / $limit;
			if( $before ){
				//  --  FIRST PAGE --  //
				//  first page
				if( $extreme && $before > $extreme )
					$pages[]	= $this->buildButton( 'text_first', 'class_link', 'class_link', 0 );

				//  --  PREVIOUS PAGE --  //
				$previous	= ( $here - 1 ) * $limit;
				//  previous page
				$pages[]	= $this->buildButton( 'text_previous', 'class_link', 'class_link', $previous );

				//  --  MORE PAGES  --  //
				//  more previous pages
				if( $more && $before > $cover )
					$pages[]	= $this->buildButton( 'text_more', 'class_text' );

				//  --  PREVIOUS PAGES --  //
				//  previous pages
				for( $i=max( 0, $before - $cover ); $i<$here; $i++ )
					$pages[]	= $this->buildButton( $i + 1, 'class_link', 'class_link', $i * $limit );
/*				if( $this->getOption( 'key_previous' ) ){
					$latest	= count( $pages ) - 1;
					$button	= $this->buildButton( $i, 'class_link', 'class_link', ($i-1) * $limit, 'previous' );
					$pages[$latest]	= $button;
				}*/
			}

			//  page here
			$pages[]	= $this->buildButton( $here + 1, 'class_text' );
			//  pages after
			$after	= ceil( ( ( $amount - $limit ) / $limit ) - $here );
			if( $after ){
				//  --  NEXT PAGES --  //
				//  after pages
				for( $i=0; $i<min( $cover, $after ); $i++ ){
					$offset		= ( $here + $i + 1 ) * $limit;
					$pages[]	= $this->buildButton( $here + $i + 2, 'class_link', 'class_link', $offset );
				}

				//  --  MORE PAGES --  //
				//  more after pages
				if( $more && $after > $cover )
					$pages[]	= $this->buildButton( 'text_more', 'class_text' );

				//  --  NEXT PAGE --  //
				$offset		= ( $here + 1 ) * $limit;
				//  next page
				$pages[]	= $this->buildButton( 'text_next', 'class_link', 'class_link', $offset );

				//  --  LAST PAGE --  //
				//  last page
				if( $extreme && $after > $extreme ){
					$offset		= ( $here + $after ) * $limit;
					$pages[]	= $this->buildButton( 'text_last', 'class_link', 'class_link', $offset );
				}
			}
		}
		return implode( $this->getOption( "linebreak" ), $pages );
	}

	/**
	 *	Builds Paging Button.
	 *	@access		protected
	 *	@param		string			$text			Text or HTML of Paging Button Span
	 *	@param		string			$spanClass		Additive Style Class of Paging Button Span
	 *	@param		int|NULL		$offset			Currently offset entries
	 *	@param		string|NULL		$linkClass		Style Class of Paging Button Link
	 *	@param		string|NULL		$key			Access Key
	 *	@return		string
	 */
	protected function buildButton( string $text, string $spanClass, ?string $linkClass = NULL, ?int $offset = NULL, ?string $key = NULL ): string
	{
		$label	= $this->hasOption( $text ) ? $this->getOption( $text ) : $text;
		if( empty( $label ) )
			throw new InvalidArgumentException( 'Button Label cannot be empty.' );
		$spanClass	= $this->getOption( $spanClass ) ? $this->getOption( $spanClass ) : "";
		if( $offset !== NULL ){
			$linkClass	= (string) $this->getOption( $linkClass );
			$url		= $this->buildLinkUrl( $offset );
			$key		= $key ? $this->getOption( 'key_'.$key ) : "";
#			if( $label == $text )
#				$linkClass	.= " page";
			$label		= Elements::Link( $url, $label, $linkClass, NULL, NULL, NULL, $key );
		}
#		if( $label == $text )
#			$spanClass	.= " page";
		return $this->buildSpan( $label, $spanClass );
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
		$param[$this->getOption( 'key_offset' )] = $offset;
		$list	= [];
		foreach( $param as $key => $value )
			$list[]	= $key.$this->getOption( 'key_assign' ).$value;
		$param	= implode( $this->getOption( 'key_param' ), $list );
		return $this->getOption( 'uri' ).$this->getOption( 'key_request' ).$param;
	}

	/**
	 *	Builds Span Link of Paging Button.
	 *	@access		protected
	 *	@param		string			$text			Text or HTML of Paging Button Span
	 *	@param		string|NULL		$class			Additive Style Class of Paging Button Span
	 *	@return		string
	 */
	protected function buildSpan( string $text, ?string $class = NULL ): string
	{
		$class 	= $class ? $this->getOption( 'class_span' )." ".$class : $this->getOption( 'class_span' );
		return Tag::create( "span", $text, ['class' => $class] );
	}
}
