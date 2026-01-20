<?php

namespace App\Helpers;

enum HttpRequestOperation: string
{
    const POST = 'post';

    const GET = 'get';

    const PUT = 'put';

    const PATCH = 'patch';

    const DELETE = 'delete';
}
