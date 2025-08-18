<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SessionInterface;
use App\Http\ServerRequestInterface;

class RequestService
{

    public function __construct(private readonly SessionInterface $session) {}

    public function getReferer(ServerRequestInterface $request): string
    {

        // return $request->getServerParams()['HTTP_REFERER'];
        $referer = $request->getHeader('referer')[0] ?? '';

        if (!$referer) {
            return $this->session->get('previousUrl');
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);

        $uriHost = parse_url($request->getUri(), PHP_URL_HOST);


        if ($refererHost !== $uriHost) {
            $referer = $this->session->get('previousUrl');
        }

        return $referer;
    }
}
