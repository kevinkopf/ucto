<?php

namespace App\Controller;

use App\Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    public function contacts(
        Form\ContactForm $contactForm
    ): Response
    {
        return $this->render('page.contacts.html.twig', [
            'forms' => [
                'contact' => $contactForm->stage(),
            ],
        ]);
    }
}
