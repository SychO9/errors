<?php
/**
 * @package axy\errors
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

declare(strict_types=1);

namespace axy\errors\helpers;

use Exception;

/**
 * Helper which sets a trace for an exception instance
 *
 * Since PHP 7 this feature is not supported
 */
class SetterTrace
{
    /**
     * Sets a trace for an exception
     *
     * @param Exception $e
     * @param array $trace
     * @return bool
     */
    public static function setTrace(Exception $e, array $trace): bool
    {
        try {
            $setter = new self($e);
            $setter($trace);
            return true;
        } catch (\Exception $e) {
            // PHP 7
            return false;
        }
    }

    /**
     * The constructor
     *
     * @param Exception $e
     */
    public function __construct(Exception $e)
    {
        $this->set = function ($trace) {
            /** @noinspection PhpUndefinedFieldInspection */
            $this->trace = $trace;
        };
        $this->set = $this->set->bindTo($e, 'Exception');
    }

    /**
     * Invoke: set a trace for the exception
     *
     * @param array $trace
     */
    public function __invoke(array $trace)
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->set->__invoke($trace);
    }

    /**
     * @var \Closure
     */
    private $set;
}
