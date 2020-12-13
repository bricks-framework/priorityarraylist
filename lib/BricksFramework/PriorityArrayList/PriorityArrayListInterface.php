<?php

/** @copyright Sven Ullmann <kontakt@sumedia-webdesign.de> **/

namespace BricksFramework\PriorityArrayList;

interface PriorityArrayListInterface extends \IteratorAggregate, \Countable
{
    public function push($value, int $priority = 0) : void;

    public function add(string $key, $value, int $priority = 0) : void;

    public function set(string $key, $value, int $priority = 0) : void;

    public function remove(string $key) : void;

    public function has(string $key) : bool;

    public function get(string $key);

    public function count() : int;

    public function setPriority(string $key, int $priority) : void;
}
