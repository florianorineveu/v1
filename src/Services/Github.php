<?php

namespace App\Services;

use App\Entity\GithubActivity;
use App\Entity\Project;
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
            Client::AUTH_HTTP_PASSWORD
        );
    }

    public function getAllCommits($user, $repository, $sha = 'primary')
    {
        return $this->fetchCommits($user, $repository, $sha);
    }

    private function fetchCommits($user, $repository, $sha)
    {
        $commits = $this->client->api('repo')->commits()->setPerPage(100)->all($user, $repository, [
            'sha' => $sha,
        ]);

        if (array_key_exists(99, $commits)) {
            $lastCommit = $commits[99];
            unset($commits[99]);
            $commits = array_merge($commits, $this->fetchCommits($user, $repository, $lastCommit['sha']));
        }

        return $commits;
    }

    public function updateActivity(Project $project)
    {
        if (!$project->getGithubActivity()) {
            $githubActivity = new GithubActivity();
            $project->setGithubActivity($githubActivity);
        }

        try {
            $commits = $this->getAllCommits(
                $project->getGithubOwner(),
                $project->getGithubRepository(),
                $project->getGithubBranch()
            );
        } catch (\Exception $exception) {
            return false;
        }

        $githubActivity
            ->setTotalCommits(count($commits))
            ->setLastCommit($commits[0]['commit'])
        ;

        return $githubActivity;
    }
}
