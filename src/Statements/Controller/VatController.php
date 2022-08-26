<?php

namespace App\Statements\Controller;

use App\Handler\Statement\VatInspectionalCompiler;
use App\Repository\Statement\Vat\InspectionalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VatController extends AbstractController
{
    private VatInspectionalCompiler $vatInspectionalCompiler;

    public function __construct(VatInspectionalCompiler $vatInspectionalCompiler)
    {
        $this->vatInspectionalCompiler = $vatInspectionalCompiler;
    }

    public function generateInspectionalStatement(?string $year, ?string $month): JsonResponse
    {
        $dt = (new \DateTime())->sub(new \DateInterval('P1M'));
        $this->vatInspectionalCompiler->compile(
            (int) ($year ?? $dt->format('Y')),
            (int) ($month ?? $dt->format('n'))
        );

        return $this->json([]);
    }

    public function displayInspectionalStatement(?int $id, InspectionalRepository $inspectionalRepository): Response
    {
        if ($id) {
            $inspectionalStatement = $inspectionalRepository->find($id);
        } else {
            $inspectionalStatement = $inspectionalRepository->findOneBy([], ['id' => 'DESC']);
        }

//        dd($inspectionalStatement);

        return $this->render('page.vatInspectionalStatement.html.twig', [
            'statement' => $inspectionalStatement,
        ]);
    }
}
