<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/team')]
class TeamController extends AbstractController
{
    #[Route('/{id}/drivers', name: 'update_team_drivers', methods: ['PATCH'])]
    public function updateDrivers(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $team = $em->getRepository(Team::class)->find($id);
        if (!$team) {
            return new JsonResponse(['error' => 'Écurie introuvable'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Corps JSON invalide'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (isset($data['addDriverId'])) {
            $driver = $em->getRepository(Driver::class)->find($data['addDriverId']);
            if (!$driver) return new JsonResponse(['error' => 'Pilote introuvable'], JsonResponse::HTTP_NOT_FOUND);
            $driver->setTeam($team);
        }

        if (isset($data['removeDriverId'])) {
            $driver = $em->getRepository(Driver::class)->find($data['removeDriverId']);
            if (!$driver) return new JsonResponse(['error' => 'Pilote introuvable'], JsonResponse::HTTP_NOT_FOUND);
            if ($driver->getTeam() && $driver->getTeam()->getId() === $team->getId()) {
                $driver->setTeam(null);
            }
        }

        if (isset($data['driverId']) && isset($data['status'])) {
            $driver = $em->getRepository(Driver::class)->find($data['driverId']);
            if (!$driver) return new JsonResponse(['error' => 'Pilote introuvable'], JsonResponse::HTTP_NOT_FOUND);
            $driver->setStatus($data['status']);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Pilotes mis à jour avec succès'], JsonResponse::HTTP_OK);
    }
}
