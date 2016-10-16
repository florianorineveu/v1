<?php

$LOCAL_ROOT         = "/var/www";
$LOCAL_REPO_NAME    = "public_html";
$LOCAL_REPO         = "{$LOCAL_ROOT}/{$LOCAL_REPO_NAME}";
$REMOTE_REPO        = "git@github.com:fnev-eu/fnev.eu.git";
$DESIRED_BRANCH     = "master";

if (file_exists($LOCAL_REPO)) {
    shell_exec("rm -rf {$LOCAL_REPO}");
}

echo shell_exec("cd {$LOCAL_ROOT} && git clone {$REMOTE_REPO} {$LOCAL_REPO_NAME} && cd {$LOCAL_REPO} && git checkout {$BRANCH}");

die("done " . mktime());
