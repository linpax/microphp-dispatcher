<?php
/**
 * @link https://github.com/linpax/microphp-dispatcher
 * @copyright Copyright &copy; 2017 Linpax
 * @license https://github.com/linpax/microphp-dispatcher/blob/master/LICENSE
 */

namespace Micro\Dispatcher;


class Dispatcher
{
    /** @var array $listeners Listeners objects on events */
    protected $listeners = [];


    /**
     * Add listener on event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array|callable $event ['Object', 'method'] or callable
     * @param int|null $prior priority
     *
     * @return bool
     */
    public function addListener($listener, $event, $prior = null)
    {
        if (!is_callable($event)) {
            return false;
        }

        if (!array_key_exists($listener, $this->listeners)) {
            $this->listeners[$listener] = [];
        }

        if (!$prior) {
            $this->listeners[$listener][] = $event;
        } else {
            array_splice($this->listeners[$listener], $prior, 0, $event);
        }

        return true;
    }

    /**
     * Send signal to run event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array $params Signal parameters
     */
    public function signal($listener, array $params = [])
    {
        if (array_key_exists($listener, $this->listeners) && 0 !== count($this->listeners[$listener])) {
            /** @noinspection ForeachSourceInspection */
            foreach ($this->listeners[$listener] as $listen) {
                call_user_func_array($listen, $params);
            }
        }
    }
}