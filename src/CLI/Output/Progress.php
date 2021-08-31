<?php
namespace CeusMedia\Common\CLI\Output;

use CeusMedia\Common\CLI\Dimensions;
use CeusMedia\Common\CLI\Output;

class Progress
{
	const STATUS_NONE		= 0;
	const STATUS_READY		= 1;
	const STATUS_STARTED	= 2;
	const STATUS_FINISHED	= 3;

	protected $status		= 0;
	protected $startTime	= 0;
	protected $total		= 0;
	protected $barBlocks	= array( '_', '░', '▓', '█' );
	protected $barTemplate	= '%1$s%2$s%3$s%4$s';

	public function __construct()
	{
		$this->width	= Dimensions::getWidth() - 3;
		$this->output	= new Output();
		$this->output->setMaxLineLength( $this->width );
	}

	public function setTotal( $total ): self{
		$this->total		= $total;
		$this->status		= static::STATUS_READY;
		return $this;
	}

	public function setBarTemplate( $barTemplate ): self
	{
		$this->barTemplate	= $barTemplate;
		return $this;
	}

	public function setBarBlocks( $barBlocks ): self
	{
		if( count( $barBlocks ) !== 4 )
			throw new \InvalidArgumentException( 'Bar blocks list must contain 4 items' );
		$this->barBlocks	= array_values( $barBlocks );
		return $this;
	}

	public function start(): self
	{
		if( $this->status < static::STATUS_READY )
			throw new \RuntimeException( 'No total set' );
		$this->startTime	= microtime( TRUE );
		$this->status		= static::STATUS_STARTED;
		$this->output->newLine( $this->renderLine( 0 ) );
		return $this;
	}

	public function update( $count ): self
	{
		if( $this->status != static::STATUS_STARTED )
			$this->start();
		$this->output->sameLine( $this->renderLine( $count ) );
		if( $count === $this->total ){
			$this->status	= static::STATUS_FINISHED;
			$this->output->newLine();
		}
		return $this;
	}

	public function finish(): self
	{
		if( $this->status == static::STATUS_STARTED ){
			$this->status	= static::STATUS_FINISHED;
			$this->output->newLine();
		}
		return $this;
	}

	/*  --  PROTECTED  --  */

	protected function estimateTimeLeft( int $count ): string
	{
		if( $count === 0 )
			return 0;
		if( $count === $this->total )
			return 0;

		$timeDiff	= microtime( TRUE ) - $this->startTime;
		if( ( $count / $this->total * 100 ) < 1 || $timeDiff < 1 )
			return 0;

		$ratio		= $this->total / $count;
		$timeLeft	= ceil( $timeDiff * $ratio - $timeDiff );
		return $timeLeft;
	}

	protected function formatTime( int $seconds, int $nrParts = 2 ): string
	{
		if( $seconds < 1 )
			return '';
		$days		= 0;
		$hours		= 0;
		$minutes	= 0;
		$parts		= array();
		if( $seconds > 86400 ){
			$days		= floor( $seconds / 86400 );
			$seconds	-= $days * 86400;
			$parts[]	= $days.'d';
		}
		if( $seconds > 3600 ){
			$hours		= floor( $seconds / 3600 );
			$seconds	-= $hours * 3600;
			if( $days )
				$parts[]	= str_pad( $hours, 2, 0, STR_PAD_LEFT ).'h';
			else
				$parts[]	= $hours.'h';
		}
		if( $seconds > 60 ){
			$minutes	= floor( $seconds / 60 );
			$seconds	-= $minutes * 60;
			if( $days || $hours )
				$parts[]	= str_pad( $minutes, 2, 0, STR_PAD_LEFT ).'m';
			else
				$parts[]	= $minutes.'m';
		}
		if( $days || $hours || $minutes )
			$parts[]	= str_pad( $seconds, 2, 0, STR_PAD_LEFT ).'s';
		else
			$parts[]	= $seconds.'s';

		if( $nrParts )
			$parts		= array_slice( $parts, 0, $nrParts );
		return implode( ' ', $parts );
	}

	protected function renderLine( int $count ): string
	{
		$count		= min( $count, $this->total );
		$timeLeft	= ceil( microtime( TRUE ) - $this->startTime );
		if( $count !== $this->total )
			$timeLeft	= $this->estimateTimeLeft( $count );
		$timeLeft	= str_pad( $this->formatTime( $timeLeft ), 8, ' ', STR_PAD_LEFT );
		$ratio		= str_pad( floor( $count / $this->total * 100 ), 4, ' ', STR_PAD_LEFT ).'%';
		$numbers	= str_pad( $count.'/'.$this->total, strlen( $this->total ) * 2 + 2, ' ', STR_PAD_LEFT );
		$barWidth	= $this->width - strlen( $timeLeft ) - strlen( $ratio ) - strlen( $numbers );
		$length1	= floor( $count / $this->total * $barWidth );
		$barPart1	= str_repeat( $this->barBlocks[3], $length1 );
		$bar		= $barPart1;
		if( $length1 < $barWidth ){
			$next		= floor( ( ( $count / $this->total * $barWidth ) - $length1 ) * 100 );
			$block		= $this->barBlocks[0];
			if( $next >= 66 )
				$block	= $this->barBlocks[2];
			else if( $next >= 33 )
				$block	= $this->barBlocks[1];
			$barPart2	= str_repeat( $this->barBlocks[0], $barWidth - $length1 - 1 );
			$bar		= $barPart1.$block.$barPart2;
		}
		$line	= vsprintf( $this->barTemplate, array(
			$bar,
			$numbers,
			$ratio,
			$timeLeft
		) );
		return $line;
	}
}
