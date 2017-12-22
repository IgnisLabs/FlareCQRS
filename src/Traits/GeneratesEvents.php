<?php

namespace IgnisLabs\FlareCQRS\Traits;

trait GeneratesEvents {

    private $recordedEvents = [];

    /**
     * Record that something has happened (event)
     * @param object $event
     */
    private function recordThat($event) {
        $this->recordedEvents[] = $event;
    }

    /**
     * Get all recorded events as an array
     * @return array
     */
    public function getRecordedEvents() : array {
        return $this->recordedEvents;
    }
}
