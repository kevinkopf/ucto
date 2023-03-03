<?php

namespace App\Contacts\Controller;

use App\Contacts\Actor\ContactDetailActor;
use App\Contacts\Actor\ContactsListActor;
use App\Contacts\Exception\ContactNotFoundException;
use App\Contacts\Handler\ContactUpdateHandler;
use App\Contacts\Repository\ContactRepository;
use App\Handler\ContactCreationAlterationHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contacts', name: 'api_contacts_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'listing', methods: ["POST"])]
    public function listing(
        Request           $request,
        ContactsListActor $actor
    ): JsonResponse
    {
        return $this->json($actor->prepare($request));
    }

    #[Route('/detail', name: 'detail', methods: ["POST"])]
    public function detail(
        Request            $request,
        ContactDetailActor $actor
    ): JsonResponse
    {
        try {
            return $this->json($actor->prepare($request));
        } catch (BadRequestHttpException $e) {
            return $this->json([], 400);
        } catch (ContactNotFoundException $e) {
            return $this->json([], 404);
        }
    }

    #[Route('/create', name: 'create', methods: ["POST"])]
    public function create(
        Request                          $request,
        ContactCreationAlterationHandler $handler
    ): JsonResponse
    {
        try {
            $handler->handle($request);
        } catch (\InvalidArgumentException $e) {
            $this->json([], 400);
        }

        return $this->json([], 204);
    }

    #[Route('/update', name: 'update', methods: ["POST"])]
    public function update(
        Request              $request,
        ContactUpdateHandler $handler
    ): JsonResponse
    {
        try {
            $handler->handle($request);
        } catch (BadRequestHttpException $e) {
            $this->json([], 400);
        } catch (ContactNotFoundException $e) {
            $this->json([], 404);
        }

        return $this->json([], 204);
    }

    #[Route('/remove', name: 'remove', methods: ["POST"])]
    public function remove(
        Request                $request,
        ContactRepository      $contactRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $id = (int)$request->query->get('id');

        if (!$id) {
            return $this->json([], 400);
        }

        $contact = $contactRepository->find($id);

        if (!$contact) {
            return $this->json([], 404);
        }

        if (count($contact->getTransactions()) > 0) {
            return $this->json([], 405);
        }

        $em->remove($contact);
        $em->flush();

        return $this->json([], 204);
    }
}
