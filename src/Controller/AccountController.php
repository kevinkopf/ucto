<?php

namespace App\Controller;

use App\Form;
use App\Handler;
use App\Repository\Account\AnalyticalRepository;
use App\Repository\AccountRepository;
use App\Requisition;
use App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/ucty", name="accounts")
     * @param Request $request
     * @param AccountRepository $accountRepository
     * @param Service\Serializer $serializer
     * @param Service\VueUtils $vueUtils
     * @param Handler\Account\Analytical $analyticalAccountHandler
     * @return Response
     */
    public function accounts(
        Request $request,
        AccountRepository $accountRepository,
        Service\Serializer $serializer,
        Service\VueUtils $vueUtils,
        Handler\Account\Analytical $analyticalAccountHandler
    ): Response {
        $accounts = $accountRepository->findAll();

        $formRequisition = new Requisition\Account\Analytical();
        $form = $this->createForm(
            Form\Account\AnalyticalType::class,
            $formRequisition
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $analyticalAccountHandler->handle($formRequisition);

            $this->redirectToRoute('accounts');
        }

        return $this->render('page.accounts.html.twig', [
            'form' => $form->createView(),
            'accounts' => $serializer->serialize($accounts, 'json', ['groups' => 'accounts']),
            'id' => $vueUtils->encodeProps($form->get('id')),
            'name' => $vueUtils->encodeProps($form->get('name')),
            'numeral' => $vueUtils->encodeProps($form->get('numeral')),
            'account' => $vueUtils->encodeProps($form->get('account')),
        ]);
    }

    public function apiSearch(
        Request $request,
        AccountRepository $accountRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $nameOrNumeral = (string)$request->request->get('query');
        $accounts = $accountRepository->findBySimilarByNameOrNumeral($nameOrNumeral);

        return $this->json($serializer->normalize($accounts, 'json', ['groups' => 'accounts']));
    }

    /**
     * @Route("/api/accounts/analytical/remove", name="api_account_analytical_remove")
     * @param Request $request
     * @param AnalyticalRepository $analyticalRepository
     * @param EntityManagerInterface $em
     */
    public function apiRemove(
        Request $request,
        AnalyticalRepository $analyticalRepository,
        EntityManagerInterface $em
    ) {
        $id = (int)$request->query->get('id');
        $analyticalAccount = $analyticalRepository->find($id);

        $em->remove($analyticalAccount);
        $em->flush();

        return $this->redirectToRoute('accounts');
    }
}
