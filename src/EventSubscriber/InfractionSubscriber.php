<?php

namespace App\EventSubscriber;

use App\Entity\Infraction;
use App\Entity\Driver;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

class InfractionSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::postPersist];
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Infraction) {
            return;
        }

        if ($entity->getType() !== Infraction::TYPE_POINTS || !$entity->getDriver()) {
            return;
        }

        $em = $args->getObjectManager();

        $driver = $entity->getDriver();

        $pointsToRemove = (int) $entity->getPoints();
        $current = (int) $driver->getLicensePoints();
        $new = max(0, $current - $pointsToRemove);
        $driver->setLicensePoints($new);

        if ($new < 12) {
            $driver->setStatus(Driver::STATUS_SUSPENDED);
        }

        $em->flush();
    }
}
