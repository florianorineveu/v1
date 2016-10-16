<?php

const LOCAL_ROOT         = "/var/www";
const LOCAL_REPO_NAME    = "public_html";
const LOCAL_REPO         = "{$LOCAL_ROOT}/{$LOCAL_REPO_NAME}";
const REMOTE_REPO        = "git@github.com:fnev-eu/fnev.eu.git";
const DESIRED_BRANCH     = "master";

echo shell_exec("cd {LOCAL_REPO} && git push origin {DESIRED_BRANCH}");

die("done " . mktime());
