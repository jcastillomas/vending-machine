<?php

declare(strict_types=1);

namespace VM\Shared\UI\Controller\Health;

use VM\Shared\UI\Controller\ApiController;
use VM\Shared\UI\Response\ApiHttpResponse;

final class HealthCheckGetController extends ApiController
{
    public function __invoke(): ApiHttpResponse
    {
        return new ApiHttpResponse(['status' => 'Service Available']);
    }
}
