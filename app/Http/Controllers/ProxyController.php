<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\HeaderBag;
use Illuminate\Support\Str;

class ProxyController extends Controller
{

    public function index(Request $request): Response
    {
        $responseData = $this->getCsrfTokenAndCookiesData($request);

        $response = response()->view('proxy.rent_house.index', [
            'csrfToken' => $responseData['csrfToken'] ?? '',
        ]);

        return $response;
    }

    public function getCsrfTokenAndCookies(Request $request): JsonResponse
    {
        $csrfTokenAndCookiesData = $this->getCsrfTokenAndCookiesData($request);


        $csrfTokenAndCookiesData['csrf_token'] = $csrfTokenAndCookiesData['csrfToken'];
        unset($csrfTokenAndCookiesData['csrfToken']);

        return response()->json($csrfTokenAndCookiesData);
    }

    public function getCsrfTokenAndCookiesData(Request $request): array
    {
        $response = $this->proxy($request);

        if (!$response->successful()) {
            return [];
        }
        $csrfToken = $this->getCsrfToken($response->body());
        $sourceCookies = $response->cookies()->toArray();
        $cookies = [];
        foreach ($sourceCookies as $sourceCookieKey => $sourceCookie) {
            foreach ($sourceCookie as $key => $value) {
                $cookies[$sourceCookieKey][Str::snake($key)] = $value;
            }
        }

        return [
            'csrfToken' => $csrfToken,
            'cookies' => $cookies
        ];
    }

    private function getCsrfToken(string $htmlContent): string
    {
        $csrfToken = '';
        $matches = [];

        if (preg_match('/<meta name="csrf-token" content="([^"]+)">/', $htmlContent, $matches)) {
            $csrfToken = $matches[1];
        }

        return $csrfToken;
    }

    public function proxy(Request $request): ClientResponse
    {
        $headers = $this->cleanHeaders($request->headers);
        return Http::withHeaders($headers->all())->withCookies($request->cookies->all(), $this->getDomainByUrl(config('proxy.proxy.rent_house.index')))->get($request->url);
    }

    public function list(Request $request): JsonResponse
    {
        $json = $this->proxy($request)->object();
        return response()->json($json);
    }

    private function cleanHeaders(HeaderBag $headers): HeaderBag
    {
        $headers->remove('host');
        return $headers;
    }

    private function getDomainByUrl(string $url): string
    {
        $parsedUrl = parse_url($url);

        $domain = '';
        if (isset($parsedUrl['host'])) {
            $domain = $parsedUrl['host'];
        }

        return $domain;
    }
}
