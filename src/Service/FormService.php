<?php

declare(strict_types=1);

namespace App\Service;

use HTMLPurifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormService
{
    private HTMLPurifier $htmlPurifier;
    private CsrfTokenManagerInterface $tokenManager;
    private TranslatorInterface $translator;

    public function __construct(
        HTMLPurifier $htmlPurifier,
        CsrfTokenManagerInterface $tokenManager,
        TranslatorInterface $translator
    ) {
        $this->htmlPurifier = $htmlPurifier;
        $this->tokenManager = $tokenManager;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param string $tokenName
     * @return array
     * @throws \JsonException
     * @throws InvalidCsrfTokenException
     */
    public function decodeAndSanitizePayload(Request $request, string $tokenName): array
    {
        $this->validateToken($request, $tokenName);

        return $this->sanitizePayload(
            $this->decodePayload($request)
        );
    }

    /**
     * @param Request $request
     * @param string $tokenName
     * @throws InvalidCsrfTokenException
     */
    public function validateToken(Request $request, string $tokenName){
        if (!$this->tokenManager->isTokenValid(
            new CsrfToken($tokenName, $request->request->get('token'))
        )) {
            throw new InvalidCsrfTokenException();
        }
    }

    /**
     * @return JsonResponse
     */
    public function getTokenErrorJsonResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'messages' => [
                    [
                        'type' => 'error',
                        'body' => $this->translator->trans('error', [], 'validators'),
                    ],
                ],
            ],

            Response::HTTP_BAD_REQUEST,
        );
    }

    /**
     * @param Request $request
     * @return array
     * @throws \JsonException
     */
    private function decodePayload(Request $request): array
    {
        return json_decode($request->get('payload'), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $payload
     * @return array
     */
    private function sanitizePayload(array $payload): array
    {
        array_walk_recursive(
            $payload,

            function (&$leaf) {
                if (!is_string($leaf)) {
                    return; // is not XSS-threat
                }

                $leaf = $this->htmlPurifier->purify(trim($leaf));
            },
        );

        return $payload;
    }
}
