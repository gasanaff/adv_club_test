<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TravelCostController extends AbstractController
{
    #[Route('api/travel_cost', name: 'api_travel_cost', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $baseCost = $data['base_cost'] ?? null;
        $birthDate = $data['birth_date'] ?? null;
        $travelDate = $data['travel_date'] ?? null;

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate)) {
            return new JsonResponse([
                'error' => 'Invalid birth date'
            ], 400);
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $travelDate)) {
            return new JsonResponse([
                'error' => 'Invalid travel date'
            ], 400);
        }

        if ($birthDate > $travelDate) {
            return new JsonResponse([
                'error' => 'Invalid birth date'
            ], 400);
        }

        if (!is_numeric($baseCost)) {
            return new JsonResponse([
                'error' => 'Invalid base cost'
            ], 400);
        }

        $cost = $baseCost;

        if ($birthDate > date('Y-m-d', strtotime('-3 years', strtotime($travelDate)))) {
            $cost = $baseCost * 0.2;
        }

        if ($birthDate < date('Y-m-d', strtotime('-6 years', strtotime($travelDate))) && $birthDate > date('Y-m-d', strtotime('-12 years', strtotime($travelDate)))) {
            $cost = min($baseCost * 0.7, 4500);
        } elseif ($birthDate < date('Y-m-d', strtotime('-12 years', strtotime($travelDate))) &&  $birthDate >= date('Y-m-d', strtotime('-18 years', strtotime($travelDate)))) {
            $cost = $baseCost * 0.9;
        }

        return new JsonResponse([
            'cost' => $cost
        ]);
    }
}
