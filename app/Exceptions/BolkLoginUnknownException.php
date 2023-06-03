<?php

namespace App\Exceptions;

use Exception;

/**
 * For access to Bolknoms, the user needs atleast the "bekend" (known)
 * authorization level in Bolklogin, as we need that level of access
 * to read the basic details of the user, i.e. username, name, email
 * and optional photo.
 *
 * This exception represents the user *not* having that level of access.
 */
class BolkLoginUnknownException extends Exception
{
    //
}
