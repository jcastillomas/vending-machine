<?php

declare(strict_types=1);

namespace VM\Shared\UI\Response;

final class ForbiddenHttpResponse extends ApiHttpErrorResponse
{
    public function __construct()
    {
        parent::__construct(['Forbidden'], HttpResponseCode::HTTP_FORBIDDEN);
    }
}
