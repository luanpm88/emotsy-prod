#/bin/bash
git checkout develop && git fetch heroku && git stash && git rebase heroku/develop && git stash pop
