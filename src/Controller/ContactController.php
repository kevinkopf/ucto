<?php

namespace App\Controller;

use App\Form;
use App\Handler;
use App\Repository\ContactRepository;
use App\Requisition;
use App\Service;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/kontakty", name="contacts")
     * @param Request $request
     * @param Service\VueUtils $vueUtils
     * @param Handler\Contact $contactHandler
     * @param ContactRepository $contactRepository
     * @return Response
     */
    public function contacts(
        Request $request,
        Service\VueUtils $vueUtils,
        Handler\Contact $contactHandler,
        ContactRepository $contactRepository
    ): Response
    {
        $formRequisition = new Requisition\Contact();
        $form = $this->createForm(Form\ContactType::class, $formRequisition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $contactHandler->handle($formRequisition);

            return $this->redirectToRoute('contacts');
        }

        return $this->render('page.contacts.html.twig', [
            'form' => $form->createView(),
            'id' => $vueUtils->encodeProps($form->get('id')),
            'name' => $vueUtils->encodeProps($form->get('name')),
            'address' => $vueUtils->encodeProps($form->get('address')),
            'phone' => $vueUtils->encodeProps($form->get('phone')),
            'email' => $vueUtils->encodeProps($form->get('email')),
            'registrationNumber' => $vueUtils->encodeProps($form->get('registrationNumber')),
            'isVatPayer' => $vueUtils->encodeProps($form->get('isVatPayer')),
            'vatNumberPrefix' => $vueUtils->encodeProps($form->get('vatNumberPrefix')),
            'vatNumber' => $vueUtils->encodeProps($form->get('vatNumber')),
        ]);
    }

    public function apiSearch(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse {
        $name = (string) $request->request->get('name');
        $contacts = $contactRepository->findBySimilarByName($name);

        return $this->json($serializer->normalize($contacts, 'json', ['groups' => 'contacts']));
    }

    /**
     * @Route("/api/contacts/list", name="api_contacts_list")
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param Service\Serializer $serializer
     * @return JsonResponse
     */
    public function list(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse
    {
        $page = (int) $request->query->get('page');
        $page = $page < 1 ? 1 : $page;
        $limit = (int) $request->query->get('limit');
        $limit = $limit < 1 ? 50 : $limit;

        $contacts = [
            "data" => $contactRepository->findBy([], ["name" => "ASC"], $limit, ($page - 1) * $limit),
            "pages" => [
                "current" => $page,
                "total" => ceil($contactRepository->count([]) / $limit),
            ],
        ];

        return $this->json(
            $serializer->normalize(
                $contacts,
                'json',
                ['groups' => 'contacts']
            )
        );
    }

    /**
     * @Route("/api/contacts/detail", name="api_contact_detail")
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param Service\Serializer $serializer
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function detail(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse
    {
        $id = (int) $request->query->get('id');
        $contact = $contactRepository->find($id);

        return $this->json(
            $serializer->normalize(
                $contact,
                'json',
                ['groups' => 'contacts']
            )
        );
    }

    /**
     * @Route("/api/contacts/remove", name="api_contact_remove")
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(
        Request $request,
        ContactRepository $contactRepository,
        EntityManagerInterface $em
    ): RedirectResponse
    {
        $id = (int) $request->query->get('id');
        $contact = $contactRepository->find($id);

        if(count($contact->getTransactions()) === 0)
        {
            $em->remove($contact);
            $em->flush();
        }

        return $this->redirectToRoute('contacts');
    }
}
