pastecode
=========

A code pasting/sharing utility

## Installation ##
1. Pull this repo recursively `git clone --recursive https://github.com/breaker1/pastecode.git` into whatever directory you like.
2. Use composer to get all of the dependancies downloaded and setup.
3. Hit the setup.php script and fill in the fields accordingly.
    1. Be sure to enter everything correctly, the script does not do input validation currently.
    2. The database must be present already with permissions for the given user, it also must NOT contain tables named users nor pastes.
4. On the file system run `vendor/bin/doctrine orm:generate:proxies`
5. Set up the cron job to run once a minute as the web user.