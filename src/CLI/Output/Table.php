<?php
namespace CeusMedia\Common\CLI\Output;

class Table
{
	const SIZE_MODE_MIN		= 0;
	const SIZE_MODE_MAX		= 1;

	const BORDER_STYLE_NONE		= 0;
	const BORDER_STYLE_SINGLE	= 1;
	const BORDER_STYLE_DOUBLE	= 2;
	const BORDER_STYLE_MIXED	= 3;

	protected $sizeMax	= 76;
	protected $sizeMin	= 5;

	protected $data		= array();

	protected $sizeMode	= 0;

	protected static $bordersDouble	= [
		'otl'	=> '╔',
		'ot'	=> '═',
		'otj'	=> '╦',
		'otr'	=> '╗',
		'ol'	=> '║',
		'olj'	=> '╠',
		'or'	=> '║',
		'orj'	=> '╣',
		'obl'	=> '╚',
		'ob'	=> '═',
		'obj'	=> '╩',
		'obr'	=> '╝',
		'ij'	=> '╬',
		'ih'	=> '═',
		'iv'	=> '║',
	];

	protected static $bordersMixed	= [
		'otl'	=> '╔',
		'ot'	=> '═',
		'otj'	=> '╤',
		'otr'	=> '╗',
		'ol'	=> '║',
		'olj'	=> '╟',
		'or'	=> '║',
		'orj'	=> '╢',
		'obl'	=> '╚',
		'ob'	=> '═',
		'obj'	=> '╧',
		'obr'	=> '╝',
		'ij'	=> '┼',
		'ih'	=> '─',
		'iv'	=> '│',
	];

	protected static $bordersSingle	= [
		'otl'	=> '┌',
		'ot'	=> '─',
		'otj'	=> '┬',
		'otr'	=> '┐',
		'ol'	=> '│',
		'olj'	=> '├',
		'or'	=> '│',
		'orj'	=> '┤',
		'obl'	=> '└',
		'ob'	=> '─',
		'obj'	=> '┴',
		'obr'	=> '┘',
		'ij'	=> '┼',
		'ih'	=> '─',
		'iv'	=> '│',
	];

	protected $borderStyle;

	public function __construct()
	{
		$this->setBorderStyle( self::BORDER_STYLE_SINGLE );
	}

	public function render()
	{
		$result		= '';
		if( count( $this->data ) ){
			$this->collectColumns();
			$headline	= $this->renderHeadline();

			$size		= count( $this->columns ) + 1;
			foreach( $this->columns as $column )
				$size	+= $column['size'];

			$padding	= '';
			if( $size < ( 75 - count( $this->columns ) ) ){
				$padding	= ' ';
				$size	+= count( $this->columns ) * 2;
			}

			$rows	= array();
			$columnKeys	= array_keys( $this->columns );
			$line		= array();
			foreach( $columnKeys as $columnKey ){
				$dir = $this->columns[$columnKey]['type'] === 'string' ? STR_PAD_RIGHT : STR_PAD_LEFT;
				$line[]	= $padding.str_pad( $columnKey, $this->columns[$columnKey]['size'], ' ', $dir ).$padding;
			}
			$rows[]	= $this->borders['ol'].join( $this->borders['iv'], $line ).$this->borders['or'].PHP_EOL;

			foreach( $this->data as $rowNr => $row ){
				$line	= array();
				foreach( $columnKeys as $columnKey ){
					$value	= isset( $row[$columnKey] ) ? $row[$columnKey] : '';
					$dir = $this->columns[$columnKey]['type'] === 'string' ? STR_PAD_RIGHT : STR_PAD_LEFT;
					$line[]	= $padding.str_pad( $value, $this->columns[$columnKey]['size'], ' ', $dir ).$padding;
				}
				$rows[]	= $this->renderRowSeparator();
				$rows[]	= $this->borders['ol'].join( $this->borders['iv'], $line ).$this->borders['or'].PHP_EOL;
			}
//			array_pop( $rows );
		}
		$result	= join( [
			$this->renderTopBorder(),
			join( $rows ),
			$this->renderBottomBorder(),
		] );
		return $result;
	}

	protected function renderRowSeparator(): string
	{
		$list	= array();
		// $this->borders['olj'].' ' );
		foreach( $this->columns as $columnKey => $column ){
			$list[]	= str_repeat( $this->borders['ih'], $column['size'] + 2 );
		}
		return join( array(
			$this->borders['olj'],
			join( $this->borders['ij'], $list ),
			$this->borders['orj'],
		) ).PHP_EOL;
	}

	protected function renderBottomBorder(): string
	{
		$list	= array();
		foreach( $this->columns as $columnKey => $column ){
			$list[]	= str_repeat( $this->borders['ob'], $column['size'] + 2 );
		}
		return join( array(
			$this->borders['obl'],
			join( $this->borders['obj'], $list ),
			$this->borders['obr'],
		) ).PHP_EOL;
	}

	protected function renderTopBorder(): string
	{
		$list	= array();
		foreach( $this->columns as $columnKey => $column ){
			$list[]	= str_repeat( $this->borders['ot'], $column['size'] + 2 );
		}
		return join( array(
			$this->borders['otl'],
			join( $this->borders['otj'], $list ),
			$this->borders['otr'],
		) ).PHP_EOL;
	}

	public function setBorderStyle( int $borderStyle ): self
	{
		$this->borderStyle	= $borderStyle;
		switch( $this->borderStyle ){
			case self::BORDER_STYLE_MIXED:
				$this->borders	= self::$bordersMixed;
				break;
			case self::BORDER_STYLE_DOUBLE:
				$this->borders	= self::$bordersDouble;
				break;
			case self::BORDER_STYLE_SINGLE:
			default:
				$this->borders	= self::$bordersSingle;
				break;
		}
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

	protected function collectColumns(): self
	{
		$this->columns	= array();
		if( !count( $this->data ) )
			return $this->columns;
		$first	= current( $this->data );
		foreach( $first as $key => $value ){
			$this->columns[$key]	= array(
				'size'	=> 0,
				'type'	=> gettype( $value ),
				'label'	=> is_string( $key ) ? $key : NULL,
			);
		}
		foreach( $this->data as $rowNr => $row ){
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
		return $this;
	}

	protected function renderHeadline(): string
	{
		$padding	= ' ';
		$rows		= array();
		$columnKeys	= array_keys( $this->columns );
		$line		= array();
		foreach( $this->columns as $columnKey => $column ){
			$dir = $this->columns[$columnKey]['type'] === 'string' ? STR_PAD_RIGHT : STR_PAD_LEFT;
			$line[]	= $padding.str_pad( $columnKey, $this->columns[$columnKey]['size'], ' ', $dir ).$padding;
		}
		return $this->borders['ol'].join( $this->borders['iv'], $line ).$this->borders['or'].PHP_EOL;
	}
}
