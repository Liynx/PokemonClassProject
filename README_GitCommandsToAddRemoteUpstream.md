// ITAS 255 Fall 2019
// croftd TODO: steps from Keith and Liam to include
// a remote to the shared class repository

// ITAS 255 git repo setup for class repo to share updates with student repositories

// STEP 1 - easiest to do this on the gitlab.itas.ca website
// create a new empty repository with no readme

// STEP 2 - clone to your local machine
clone your to your local repo, which adds it as origin ("You have cloned empty repo")

// STEP 3 - add the remote to the main shared repo
git remote add upstream https://gitlab.itas.ca/ITAS255_F19_code

// STEP 4 - pull the code from the remote
git pull upstream master

// STEP 5
// had to ignore .idea folder from PHPStorm with a .gitignore file


// STEP 6 - create a new branch
git branch workstream

// do some work

git add, commit etc..

// go back to master branch
git checkout master

git merge workstream

// someone adds new files to ITAS255_F19_code repo
// to get these changes

git pull upstream master

// will automatically merge window save with :wq assuming vi is default editor

