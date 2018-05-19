<?php

namespace App;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class Repository extends EntityRepository
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    protected function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * @param EventDispatcher $dispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcher $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        return $this;
    }

    /**
     * @return EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}
