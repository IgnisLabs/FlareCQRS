<?php

namespace IgnisLabs\FlareCQRS\Message;

trait DataAccessorTrait {

    /**
     * Message data
     * @var array
     */
    private $data = [];

    /**
     * Set the message data
     * @param array $data
     */
    protected function setData(array $data) {
        $this->data = $data;
    }

    /**
     * Get message data item
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        return $this->data[$name] ?? null;
    }

    /**
     * Access data through __get
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}
