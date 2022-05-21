<?php
/**
 *	Progress bar for console output.
 *
 *	Copyright (c) 2019-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2019-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
namespace CeusMedia\Common\CLI\Output;

use CeusMedia\Common\CLI\Dimensions;
use CeusMedia\Common\CLI\Output;

/**
 *	Console Output.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2019-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CLI_Output_Progress
{
	const STATUS_NONE		= 0;
	const STATUS_READY		= 1;
	const STATUS_STARTED	= 2;
	const STATUS_FINISHED	= 3;

	const STATUSES			= array(
		self::STATUS_NONE,
		self::STATUS_READY,
		self::STATUS_STARTED,
		self::STATUS_FINISHED,
	);

	protected $status		= 0;
	protected $startTime	= 0;
	protected $total		= 0;
	protected $barBlocks	= array( '_', '░', '▓', '█' );
	protected $barTemplate	= '%1$s%2$s%3$s%4$s';
	protected $width		= 80;
	protected $output;

	/**
	 *	Constructor.
	 *	@access		public
	 */
	public function __construct()
	{
		if( !CLI::checkIsHeadless( FALSE ) )
			$this->width	= Dimensions::getWidth();
		$this->width	= $this->width - 3;
		$this->output	= new Output();
		$this->output->setMaxLineLength( $this->width );
	}

	/**
	 *	Draw completed progress bar.
	 *	@access		public
	 *	@return		self
	 */
	public function finish(): self
	{
		if( $this->status === static::STATUS_STARTED ){
			$this->status	= static::STATUS_FINISHED;
			$this->output->newLine();
		}
		return $this;
	}

	/**
	 *	Set characters of bar blocks.
	 *	Given array must hold 4 states: 0%, 33%, 66%, 100%
	 *	@access		public
	 *	@param		array		$barBlocks		List of block characters
	 *	@return		self
	 */
	public function setBarBlocks( array $barBlocks ): self
	{
		if( count( $barBlocks ) !== 4 )
			throw new InvalidArgumentException( 'Bar blocks list must contain 4 items' );
		$this->barBlocks	= array_values( $barBlocks );
		return $this;
	}

	/**
	 *	Set bar template of placeholders.
	 *	Placeholders:
	 *	- 1: bar
	 *	- 2: numbers
	 *	- 3: ratio
	 *	- 4: timeLeft
	 *	@access		public
	 *	@param		string		$barTemplate	Bar template of placeholders
	 *	@return		self
	 */
	public function setBarTemplate( $barTemplate ): self
	{
		$this->barTemplate	= $barTemplate;
		return $this;
	}

	/**
	 *	Set total number of steps.
	 *	@access		public
	 *	@param		integer		$total			Total number of steps
	 *	@return		self
	 *	@throws		RangeException				if total number is less than 1
	 */
	public function setTotal( int $total ): self
	{
		if( $total < 1 )
			throw new RangeException( 'Total number cannot be less than 1' );
		$this->total	= $total;
		$this->status	= static::STATUS_READY;
		return $this;
	}

	/**
	 *	Draw empty progress bar.
	 *	@access		public
	 *	@return		self
	 */
	public function start(): self
	{
		if( $this->status < static::STATUS_READY )
			throw new RuntimeException( 'No total set' );
		$this->startTime	= microtime( TRUE );
		$this->status		= static::STATUS_STARTED;
		$this->output->newLine( $this->renderLine( 0 ) );
		return $this;
	}

	/**
	 *	Set current number of steps and draw updated progress bar.
	 *	@access		public
	 *	@param		integer		$count		Current number of steps
	 *	@return		self
	 */
	public function update( int $count ): self
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

	/**
	 *	Render complete line of progress bar.
	 *	@access		protected
	 *	@param		integer		$count		Current number of steps
	 *	@return		string
	 */
	protected function renderLine( int $count ): string
	{
		if( CLI::checkIsHeadless( FALSE ) )
			return '';
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
