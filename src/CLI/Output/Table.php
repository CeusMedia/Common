<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Table for console output.
 *
 *	Copyright (c) 2020-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Output;

/**
 *	Progress bar for console output.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Table
{
	public const SIZE_MODE_MIN			= 0;
	public const SIZE_MODE_MAX			= 1;

	public const SIZE_MODES				= [
		self::SIZE_MODE_MIN,
		self::SIZE_MODE_MAX,
	];

	public const BORDER_STYLE_NONE		= 0;
	public const BORDER_STYLE_SINGLE	= 1;
	public const BORDER_STYLE_DOUBLE	= 2;
	public const BORDER_STYLE_MIXED		= 3;

	public const BORDER_STYLES			= [
		self::BORDER_STYLE_NONE,
		self::BORDER_STYLE_SINGLE,
		self::BORDER_STYLE_DOUBLE,
		self::BORDER_STYLE_MIXED,
	];

	protected int $sizeMax				= 76;

	protected int $sizeMin				= 5;

	protected array $columns			= [];

	protected array $data				= [];

	protected int $sizeMode				= 0;

	protected static array $borderStyles	= [
		self::BORDER_STYLE_NONE	=> [
			'otl'	=> '', 'ot'	=> '', 'otj'	=> '', 'otr'	=> '',
			'ol'	=> '', 'olj'	=> '', 'or'	=> '', 'orj'	=> '',
			'obl'	=> '', 'ob'	=> '', 'obj'	=> '', 'obr'	=> '',
			'ij'	=> '', 'ih'	=> '', 'iv'	=> ' ',
		],
		self::BORDER_STYLE_SINGLE	=> [
			'otl'	=> '┌', 'ot'	=> '─', 'otj'	=> '┬', 'otr'	=> '┐',
			'ol'	=> '│', 'olj'	=> '├', 'or'	=> '│', 'orj'	=> '┤',
			'obl'	=> '└', 'ob'	=> '─', 'obj'	=> '┴', 'obr'	=> '┘',
			'ij'	=> '┼', 'ih'	=> '─', 'iv'	=> '│',
		],
		self::BORDER_STYLE_DOUBLE	=> [
			'otl'	=> '╔', 'ot'	=> '═', 'otj'	=> '╦', 'otr'	=> '╗',
			'ol'	=> '║', 'olj'	=> '╠', 'or'	=> '║', 'orj'	=> '╣',
			'obl'	=> '╚', 'ob'	=> '═', 'obj'	=> '╩', 'obr'	=> '╝',
			'ij'	=> '╬', 'ih'	=> '═', 'iv'	=> '║',
		],
		self::BORDER_STYLE_MIXED	=> [
			'otl'	=> '╔', 'ot'	=> '═', 'otj'	=> '╤', 'otr'	=> '╗',
			'ol'	=> '║', 'olj'	=> '╟', 'or'	=> '║', 'orj'	=> '╢',
			'obl'	=> '╚', 'ob'	=> '═', 'obj'	=> '╧', 'obr'	=> '╝',
			'ij'	=> '┼', 'ih'	=> '─', 'iv'	=> '│',
		],
	];

	protected int $borderStyle;

	protected array $borders		= [];

	public function __construct()
	{
		$this->setBorderStyle( self::BORDER_STYLE_SINGLE );
	}

	public function render(): string
	{
		if( count( $this->data ) === 0 )
			return '';

		$this->collectColumns();
		return join( [
			$this->renderTopBorder(),
			$this->renderHeadline(),
			$this->renderRows(),
			$this->renderBottomBorder(),
		] );
	}

	protected function renderDataLine( array $data, string $padding ): string
	{
		$line	= [];
		$border	= $this->getBorderStyleObject();
		foreach( $this->columns as $columnKey => $column ){
			$value	= $data[$columnKey] ?? '';
			$dir = $column['type'] === 'string' ? STR_PAD_RIGHT : STR_PAD_LEFT;
			$line[]	= $padding.str_pad( $value, $column['size'], ' ', $dir ).$padding;
		}
		return $border->ol.join( $border->iv, $line ).$border->or.PHP_EOL;
	}

	public function setBorderStyle( int $borderStyle ): self
	{
		$this->borderStyle	= $borderStyle;
		return $this;
	}

	public function setData( array $data ): self
	{
		$this->data	= $data;
		return $this;
	}

	public function setSizeMode( int $mode ): self
	{
		$this->sizeMode	= $mode;
		return $this;
	}

	//  --  PROTECTED  --  //

	protected function calculatePadding(): string
	{
		$padding	= '';
		$size		= count( $this->columns ) + 1;
		foreach( $this->columns as $column )
			$size	+= $column['size'];

		if( $size < ( 75 - count( $this->columns ) ) )
			$padding	= ' ';
		return $padding;
	}

	protected function collectColumns(): void
	{
		$this->columns	= [];
		if( !count( $this->data ) )
			return;
		$first	= current( $this->data );
		foreach( $first as $key => $value ){
			$this->columns[$key]	= [
				'size'	=> 0,
				'type'	=> gettype( $value ),
				'label'	=> is_string( $key ) ? $key : NULL,
			];
		}
		foreach( $this->data as $row ){
			foreach( $row as $key => $value ){
				$this->columns[$key]['size']	= max( $this->columns[$key]['size'], strlen( $value ) );
			}
		}
		if( $this->sizeMode === self::SIZE_MODE_MAX && $this->sizeMax ){
			$spaceLeft	= $this->sizeMax - ( count( $this->columns ) * 3 ) - 1;
			$spaceUsed	= 0;
			foreach( $this->columns as $column )
				$spaceUsed	+= $column['size'];
			foreach( $this->columns as $columnKey => $column ){
				$ratio	= $this->columns[$columnKey]['size'] / $spaceUsed;
				$this->columns[$columnKey]['size']	= floor( $ratio * $spaceLeft );
			}
		}
	}

	protected function getBorderStyleObject(): object
	{
		return TableBorderTheme::createFromArray( self::$borderStyles[$this->borderStyle] );
	}

	protected function renderHeadline(): string
	{
		$padding	= ' ';
		$border		= $this->getBorderStyleObject();
		$line		= [];
		foreach( $this->columns as $columnKey => $column ){
			$dir = $column['type'] === 'string' ? STR_PAD_RIGHT : STR_PAD_LEFT;
			$line[]	= $padding.str_pad( $columnKey, $column['size'], ' ', $dir ).$padding;
		}
		return $border->ol.join( $border->iv, $line ).$border->or.PHP_EOL;
	}
	protected function renderRowSeparator(): string
	{
		$list	= [];
		$border	= $this->getBorderStyleObject();
		if( $border->olj.$border->ij.$border->orj === '' )
			return '';

		foreach( $this->columns as $column ){
			$list[]	= str_repeat( $border->ih, $column['size'] + 2 );
		}
		return join( [
				$border->olj,
				join( $border->ij, $list ),
				$border->orj,
			] ).PHP_EOL;
	}

	protected function renderBottomBorder(): string
	{
		$list	= [];
		$border	= $this->getBorderStyleObject();
		if( $border->obl.$border->obj.$border->obr === '' )
			return '';

		foreach( $this->columns as $column ){
			$list[]	= str_repeat( $border->ob, $column['size'] + 2 );
		}
		return join( [
				$border->obl,
				join( $border->obj, $list ),
				$border->obr,
			] ).PHP_EOL;
	}

	protected function renderRows(): string
	{
		$padding	= $this->calculatePadding();
		$rows		= [];
		foreach( $this->data as $row ){
			$rows[]	= $this->renderRowSeparator();
			$rows[]	= $this->renderDataLine( $row, $padding );
		}
		return join( $rows );
	}

	protected function renderTopBorder(): string
	{
		$list	= [];
		$border	= $this->getBorderStyleObject();
		if( $border->otl.$border->otj.$border->otr === '' )
			return '';

		foreach( $this->columns as $column ){
			$list[]	= str_repeat( $border->ot, $column['size'] + 2 );
		}
		return join( [
				$border->otl,
				join( $border->otj, $list ),
				$border->otr,
			] ).PHP_EOL;
	}
}