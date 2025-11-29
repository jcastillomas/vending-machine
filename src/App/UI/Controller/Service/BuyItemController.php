<?php

declare(strict_types=1);

namespace VM\App\UI\Controller\Service;

use VM\App\Application\Command\BuyItem\BuyItemCommand;
use VM\Shared\UI\Controller\ApiController;
use VM\Shared\UI\Response\ApiHttpResponse;
use VM\Shared\UI\Response\HttpResponseCode;
use Symfony\Component\HttpFoundation\Request;

final class BuyItemController extends ApiController
{
    public function __invoke(Request $request): ApiHttpResponse
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->dispatchWithResponse(BuyItemCommand::create($data));

        return new ApiHttpResponse($result->result(), HttpResponseCode::HTTP_OK);
    }
}
