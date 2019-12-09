<?php

class Route
{
    private $target;
    private $args = [];

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param callable $target
     * @param array $args
     * @return Route
     */
    public function setTarget($target, $args)
    {
        $this->target = $target;
        $this->args   = $args;

        return $this;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

}