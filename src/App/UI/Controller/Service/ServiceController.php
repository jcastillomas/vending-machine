<?php

declare(strict_types=1);

namespace VM\App\UI\Controller\Service;

use VM\App\Application\Command\Service\ServiceCommand;
use VM\Shared\UI\Controller\ApiController;
use VM\Shared\UI\Response\ApiHttpResponse;
use VM\Shared\UI\Response\ResourceAcceptedHttpResponse;
use Symfony\Component\HttpFoundation\Request;

final class ServiceController extends ApiController
{
    public function __invoke(Request $request): ApiHttpResponse
    {
        $data = json_decode($request->getContent(), true);

        $stock = $data['stock'];
        $cash = $data['cash'];

        $this->dispatch(ServiceCommand::create($stock, $cash));

        return new ResourceAcceptedHttpResponse();
    }
}
