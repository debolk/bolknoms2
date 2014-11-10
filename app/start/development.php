<?php

/*
|--------------------------------------------------------------------------
| Load the environment variables
|--------------------------------------------------------------------------
|
| We use phpdotenv (https://github.com/vlucas/phpdotenv) to load essential
| environment variables from the .env file in the root folder, when it 
| exists, i.e. only on development machines.
|
*/
Dotenv::load(base_path());
