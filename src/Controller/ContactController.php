<?php

namespace App\Controller;

use App\Form;
use App\Handler;
use App\Repository\ContactRepository;
use App\Requisition;
use App\Service;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $contacts = $contactRepository->findAll();

        return $this->render('page.contacts.html.twig', [
            'contacts' => $contacts,
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

    /**
     * @Route("/api/contacts/search", name="api_contacts_search")
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param Service\Serializer $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function apiContactsSearch(
        Request $request,
        ContactRepository $contactRepository,
        Service\Serializer $serializer
    ): JsonResponse
    {
        $name = (string) $request->query->get('name');
        $contacts = $contactRepository->findBySimilarByName($name);

        return $this->json($serializer->normalize($contacts, 'json', ['groups' => 'contacts']));
    }

    /**
     * @Route("/api/contact/detail", name="api_contact_detail")
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
}
