<?php

namespace App\Controller;

use App\Entity\Infraction;
use App\Entity\Driver;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/infractions')]
class InfractionController extends AbstractController
{
    #[Route('', name: 'create_infraction', methods: ['POST'])]
    public function createInfraction(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Corps JSON invalide'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $infraction = new Infraction();
        $infraction->setType($data['type'] ?? '');
        $infraction->setRaceName($data['raceName'] ?? '');
        $infraction->setDescription($data['description'] ?? null);
        $infraction->setDate(new \DateTime());

        if ($infraction->getType() === Infraction::TYPE_POINTS) {
            $infraction->setPoints($data['points'] ?? 0);
            $driver = $em->getRepository(Driver::class)->find($data['driverId'] ?? 0);
            if (!$driver) return new JsonResponse(['error' => 'Pilote introuvable'], JsonResponse::HTTP_NOT_FOUND);
            $infraction->setDriver($driver);
        } elseif ($infraction->getType() === Infraction::TYPE_FINE) {
            $infraction->setAmount($data['amount'] ?? '0.00');
            $team = $em->getRepository(Team::class)->find($data['teamId'] ?? 0);
            if (!$team) return new JsonResponse(['error' => 'Écurie introuvable'], JsonResponse::HTTP_NOT_FOUND);
            $infraction->setTeam($team);
        } else {
            return new JsonResponse(['error' => 'Type d’infraction invalide'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($infraction);
        $em->flush();

        return new JsonResponse(['message' => 'Infraction créée avec succès'], JsonResponse::HTTP_CREATED);
    }

    #[Route('', name: 'list_infractions', methods: ['GET'])]
    public function listInfractions(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $driverId = $request->query->get('driver');
        $teamId = $request->query->get('team');
        $dateFrom = $request->query->get('dateFrom');
        $dateTo = $request->query->get('dateTo');

        $qb = $em->getRepository(Infraction::class)->createQueryBuilder('i')
            ->leftJoin('i.driver', 'd')->addSelect('d')
            ->leftJoin('i.team', 't')->addSelect('t');

        if ($driverId) $qb->andWhere('d.id = :driverId')->setParameter('driverId', $driverId);
        if ($teamId)   $qb->andWhere('t.id = :teamId')->setParameter('teamId', $teamId);
        if ($dateFrom) $qb->andWhere('i.date >= :dateFrom')->setParameter('dateFrom', new \DateTime($dateFrom));
        if ($dateTo)   $qb->andWhere('i.date <= :dateTo')->setParameter('dateTo', new \DateTime($dateTo));

        $infractions = $qb->orderBy('i.date', 'DESC')->getQuery()->getArrayResult();

        return new JsonResponse($infractions);
    }
}
