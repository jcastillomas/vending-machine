<?php

declare(strict_types=1);

namespace VM\Shared\UI\Response;

class ApiHttpErrorResponse extends ApiHttpResponse
{
    public function __construct(
        array $errors,
        int $statusCode = HttpResponseCode::HTTP_BAD_REQUEST,
        array $headers = []
    ) {
        $this->guardHttpResponseError($statusCode);
        $data = $this->formatErrorDataResponse($errors);

        parent::__construct($data, $statusCode, array_merge($headers, self::DEFAULT_HEADERS));
    }

    public static function uniqueError(
        string $message,
        int $statusCode = HttpResponseCode::HTTP_BAD_REQUEST,
        array $headers = []
    ): self {
        return new self([$message], $statusCode, $headers);
    }

    private function guardHttpResponseError(int $statusCode): void
    {
        if ($statusCode < self::HTTP_BAD_REQUEST || $statusCode >= self::HTTP_INTERNAL_SERVER_ERROR) {
            throw new \Exception("Unexpected Error");
        }
    }

    private function formatErrorDataResponse(array $errors): array
    {
        $dataResponse = ["errors" => array()];

        foreach ($errors as $error) {
            $dataResponse["errors"][] = [
                "message" => $error
            ];
        }

        return $dataResponse;
    }
}
