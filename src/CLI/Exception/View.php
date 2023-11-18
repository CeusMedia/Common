<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Exception;

use Exception;
use InvalidArgumentException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class View
{
	protected ?Exception $exception		= NULL;

	public function __construct( ?Exception $exception = NULL )
	{
		if( !is_null( $exception ) )
			$this->setException( $exception );
	}

	public function __toString(): string
	{
		return $this->render();
	}

	public function render(): string
	{
		if( !$this->exception instanceof Exception )
			throw new InvalidArgumentException( 'No exception set' );
		$e	= $this->exception;
		$lines	= [
			'Exception caught:',
			'- Message:     '.$e->getMessage(),
			'- File:Lines:  '.$e->getFile().':'.$e->getLine(),
			'- Exception:   '.get_class( $e ),
		];
		return join( PHP_EOL, $lines ).PHP_EOL;
	}

	public function setException( Exception $exception ): self
	{
		$this->exception	= $exception;
		return $this;
	}

	public static function getInstance( ?Exception $exception = NULL ): self
	{
		return new self( $exception );
	}
}
