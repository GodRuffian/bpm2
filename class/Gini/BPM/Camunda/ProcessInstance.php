<?php

namespace Gini\BPM\Camunda;

class ProcessInstance implements \Gini\BPM\Driver\ProcessInstance {

    private $camunda;
    private $id;
    private $data;

    public function __construct($camunda, $id, $data=null) {
        $this->camunda = $camunda;
        $this->id = $id;
        if ($data) {
            $this->data = (array) $data;
        }
    }

    private function _fetchInstance() {
        if (!$this->data) {
            $id = $this->id;
            try {
                $this->data = $this->camunda->get("history/process-instance/$id");
            } catch (\Gini\BPM\Exception $e) {
                $this->data = [];
            }
        }
    }

    public function exists() {
        $this->_fetchInstance();
        return isset($this->data['id']);
    }

    public function __get($name) {
        if ($name == 'id') {
            return $this->id;
        }

        $this->_fetchInstance();
        return $this->data[$name];
    }

    public function getData() {
        $this->_fetchInstance();
        return $this->data;
    }
}
