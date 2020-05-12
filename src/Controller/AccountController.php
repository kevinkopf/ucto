<?php

namespace App\Controller;

use App\Form;
use App\Handler;
use App\Repository\AccountRepository;
use App\Requisition;
use App\Service;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    ): Response
    {
        $accounts = $accountRepository->findAll();

        $formRequisition = new Requisition\Account\Analytical();
        $form = $this->createForm(
            Form\Account\AnalyticalType::class,
            $formRequisition
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $analyticalAccountHandler->handle($formRequisition);

            $this->redirectToRoute('accounts');
        }

        return $this->render('page.accounts.html.twig',[
            'form' => $form->createView(),
            'accounts' => $serializer->serialize($accounts, 'json', ['groups' => 'accounts']),
            'id' => $vueUtils->encodeProps($form->get('id')),
            'name' => $vueUtils->encodeProps($form->get('name')),
            'numeral' => $vueUtils->encodeProps($form->get('numeral')),
            'account' => $vueUtils->encodeProps($form->get('account')),
        ]);
    }

    /**
     * @Route("/api/accounts/search", name="api_accounts_search")
     * @param Request $request
     * @param AccountRepository $accountRepository
     * @param Service\Serializer $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function apiSearch(
        Request $request,
        AccountRepository $accountRepository,
        Service\Serializer $serializer
    ) {
        $nameOrNumeral = (string) $request->query->get('query');
        $accounts = $accountRepository->findBySimilarByNameOrNumeral($nameOrNumeral);

        return $this->json($serializer->normalize($accounts, 'json', ['groups' => 'accounts']));
    }
}
