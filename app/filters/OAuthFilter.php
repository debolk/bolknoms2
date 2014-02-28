<?php

/**
 * Filter that forces the user to authenticate with De Bolk OAuth2 endpoint
 */
class OAuthFilter
{
    public function filter()
    {
        return 'access denied!';
    }
}
