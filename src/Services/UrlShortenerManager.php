<?php

namespace App\Services;

use App\Entity\ShortenUrl;
use App\Repository\ShortenUrlRepository;

class UrlShortenerManager
{
    private $shortenUrlRepository;

    public function __construct(ShortenUrlRepository $shortenUrlRepository)
    {
        $this->shortenUrlRepository = $shortenUrlRepository;
    }

    public function generateShortenUrl(ShortenUrl $shortenUrl)
    {
        $slug = $this->generateSlug();

        $alreadyExistsShortenUrl = $this->shortenUrlRepository->findOneBy([
            'shortenUrl' => $slug,
        ]);

        if ($alreadyExistsShortenUrl) {
            $this->generateShortenUrl($shortenUrl);
        }

        $shortenUrl->setShortenUrl($slug);
    }

    private function generateSlug($length = 7)
    {
        return substr(str_shuffle(str_repeat(
            $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            ceil($length / strlen($x)
            ))), 1, $length);
    }
}
