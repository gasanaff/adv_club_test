<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalcController extends AbstractController
{
    #[Route('api/calc', name: 'api_calc', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $operator = $data['operator'] ?? null;
        $operand1 = $data['operand1'] ?? null;
        $operand2 = $data['operand2'] ?? null;

        if (!is_numeric($operand1) || !is_numeric($operand2)) {
            return new JsonResponse([
                'error' => 'Invalid operands'
            ], 400);
        }

        //check operator
        if (!in_array($operator, ['+', '-', '*', '/'])) {
            return new JsonResponse([
                'error' => 'Invalid operator'
            ], 400);
        }

        if ($operator === '/' && $operand2 == 0) {
            return new JsonResponse([
                'error' => 'Division by zero'
            ], 400);
        }

        $result = null;

        switch ($operator) {
            case '+':
                $result = $operand1 + $operand2;
                break;
            case '-':
                $result = $operand1 - $operand2;
                break;
            case '*':
                $result = $operand1 * $operand2;
                break;
            case '/':
                $result = $operand1 / $operand2;
                break;
        }

        return new JsonResponse([
            'result' => $result
        ]);
    }
}
