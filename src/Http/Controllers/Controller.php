<?php

namespace Saccharine\Operations\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller for the Operations package's HTTP endpoints.
 *
 * The public intake controller extends this so route actions resolve without
 * requiring the host application to provide a controller base class.
 */
class Controller extends BaseController
{
    //
}
