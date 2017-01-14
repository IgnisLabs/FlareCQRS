<?php

namespace IgnisLabs\Qry;

// Synchronous promise
class ResultPromise {

    private $successCallback;

    private $errorCallback;

    private $success;

    private $error;

    private $done = false;

    public function __construct(Handler $handler, $query) {
        try {
            $result = $handler->handle($query);
            $this->resolve($result);
        } catch (\Exception $ex) {
            $this->reject($ex);
        }
    }

    public function resolve($result) {
        if ($this->successCallback) {
            $this->success($result);
            $this->done = true;
            return;
        }
        $this->success = $result;
    }

    public function resolve(\Exception $ex) {
        if ($this->errorCallback) {
            $this->error($ex);
            $this->done = true;
            return;
        }
        $this->error = $ex;
    }

    public function success(closure $closure) {
        $this->successCallback = $closure;

        if (!$this->done && !$this->successCallback && $this->success) {
            $closure($this->success);
        }
    }

    public function error(closure $closure) {
        $this->errorCallback = $closure;
        if (!$this->done && !$this->errorCallback && $this->error) {
            $closure($this->error);
        }
    }

    public function then(closure $success = null, closure $error = null) {
        $this->success($success);
        $this->error($error);
    }
}