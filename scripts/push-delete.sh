#!/usr/bin/env bash
set -e

REPO_DIR='c:/xampp1/htdocs/1-Pet'   # adjust if different; use forward slashes for Git Bash
REMOTE='origin'
BRANCH='main'                       # change to 'master' or your branch name if needed
COMMIT_MSG='Remove manage_vet_application.php'

cd "$REPO_DIR"

# show current status so you can confirm
git status --porcelain

# stage all changes (including deletions)
git add -A

# create commit (will fail if nothing to commit)
git commit -m "$COMMIT_MSG"

# push to remote branch
git push "$REMOTE" "$BRANCH"
