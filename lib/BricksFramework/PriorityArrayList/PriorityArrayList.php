<?php

/** @copyright Sven Ullmann <kontakt@sumedia-webdesign.de> **/

declare(strict_types=1);

namespace BricksFramework\PriorityArrayList;

class PriorityArrayList implements PriorityArrayListInterface
{
    protected $data = [];

    protected $priority = [];

    protected $needSort = false;

    public function push($value, int $priority = 0) : void
    {
        $this->data[] = $value;
        $this->priority[] = $priority;
        $this->needSort = true;
    }

    /**
     * @throws Exception\KeyExistsException
     */
    public function add(string $key, $value, int $priority = 0) : void
    {
        if ($this->has($key)) {
            throw new Exception\KeyExistsException('key "' . $key . '" has already be added, please use ::set()');
        }
        $this->set($key, $value, $priority);
    }

    public function set(string $key, $value, int $priority = 0) : void
    {
        $this->data[$key] = $value;
        $this->priority[$key] = $priority;
        $this->needSort = true;
    }

    public function remove(string $key) : void
    {
        if ($this->has($key)) {
            unset($this->data[$key], $this->priority[$key]);
        }
    }

    public function has(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
    }

    public function count() : int
    {
        return count($this->data);
    }

    public function setPriority(string $key, int $priority) : void
    {
        if ($this->has($key)) {
            $this->priority[$key] = $priority;
            $this->needSort = true;
        }
    }

    protected function sort() : void
    {
        $newData = [];
        $newPriority = [];

        $combined = [];
        foreach ($this->data as $key => $value) {
            $priority = $this->priority[$key];
            $combined[] = [
                'key' => $key,
                'value' => $value,
                'priority' => $priority
            ];
        }
        usort(
            $combined, function ($a, $b) {
                return $a['priority'] > $b['priority'] ? 1 : -1;
            }
        );

        foreach ($combined as $data) {
            $newData[$data['key']] = $data['value'];
            $newPriority[$data['key']] = $data['priority'];
        }

        $this->data = $newData;
        $this->priority = $newPriority;
    }

    protected function sortIfNeeded() : void
    {
        if ($this->needSort) {
            $this->sort();
            $this->needSort = false;
        }
    }

    public function getIterator() : \ArrayIterator
    {
        $this->sortIfNeeded();
        return new \ArrayIterator($this->data);
    }
}