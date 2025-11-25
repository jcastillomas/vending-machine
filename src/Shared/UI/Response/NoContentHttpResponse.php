<?php

declare(strict_types=1);

namespace VM\Shared\UI\Response;

final class NoContentHttpResponse extends ApiHttpResponse
{
    public function __construct()
    {
        parent::__construct([], HttpResponseCode::HTTP_NO_CONTENT);
    }
}
