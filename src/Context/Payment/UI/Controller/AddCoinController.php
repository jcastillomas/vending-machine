<?php

declare(strict_types=1);

namespace VM\Context\Payment\UI\Controller;

use Symfony\Component\HttpFoundation\Request;
use VM\Context\Payment\Application\Command\AddFund\AddFundCommand;
use VM\Shared\Domain\Write\Exception\EntityNotFoundException;
use VM\Shared\UI\Controller\ApiController;
use VM\Shared\UI\Response\ApiHttpErrorResponse;
use VM\Shared\UI\Response\ApiHttpResponse;
use VM\Shared\UI\Response\HttpResponseCode;

final class AddCoinController extends ApiController
{
    public function __invoke(Request $request): ApiHttpResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->dispatch(
                AddFundCommand::create(
                    $data,
                )
            );
        }  catch (EntityNotFoundException $e) {
            return ApiHttpErrorResponse::uniqueError($e->getMessage(), HttpResponseCode::HTTP_NOT_FOUND);

        }

        return new ApiHttpResponse([], HttpResponseCode::HTTP_ACCEPTED);
    }
}
