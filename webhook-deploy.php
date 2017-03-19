<?php

const LOCAL_ROOT         = "/var/www";
const LOCAL_REPO_NAME    = "public_html";
const REMOTE_REPO        = "git@github.com:fnev-eu/fnev.eu.git";
const DESIRED_BRANCH     = "master";

echo shell_exec("cd " . LOCAL_ROOT . "/" . LOCAL_REPO_NAME . " && git pull origin " . DESIRED_BRANCH);

die("Deploy done " . mktime());
