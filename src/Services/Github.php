<?php

namespace App\Services;

use Github\Client;

class Github
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->authenticate(
            getenv('GITHUB_SECRET'),
            null,
            Client::AUTH_URL_TOKEN
        );
    }

    public function getAllCommits($repository, $user = null, $sha = 'master')
    {
        if (!$user) {
            $user = getenv('GITHUB_USERNAME');
        }

        return $this->fetchCommits($repository, $user, $sha);
    }

    private function fetchCommits($repository, $user, $sha)
    {
        $commits = $this->client->api('repo')->commits()->setPerPage(100)->all($user, $repository, array('sha' => $sha));

        if (array_key_exists(99, $commits)) {
            $lastCommit = $commits[99];
            unset($commits[99]);
            $commits = array_merge($commits, $this->fetchCommits($repository, $user, $lastCommit['sha']));
        }

        return $commits;
    }
}