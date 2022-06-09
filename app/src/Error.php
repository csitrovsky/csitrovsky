<?php

namespace app\src;

use JsonException;
use RuntimeException;
use function header;
use function json_encode;

class Error
{

    /**
     * @var int
     */
    private int $headers = JSON_PRETTY_PRINT;

    /**
     * @var string[]
     */
    private array $status = [
        200 => 'Ok',
        204 => 'No Content',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        409 => 'Conflict',
        500 => 'Internal Server Error',
    ];

    /**
     *
     */
    public function __construct()
    {
        $this->headers();
    }

    /**
     * @return void
     */
    private function headers(): void
    {
        header('Content-Type: application/json');
    }

    /**
     * @param string $message
     * @param int    $code
     *
     * @return void
     * @throws JsonException
     */
    public function throw(string $message, int $code = 200): void
    {
        if ($code !== 200) {
            $success = false;
        }
        throw new RuntimeException($this->output([
            'success' => $success ?? true,
            'message' => $message,
        ], $code), $code);
    }

    /**
     * @param     $message
     * @param int $code
     *
     * @return bool|string
     * @throws JsonException
     */
    public function output($message, int $code = 200)
    {
        header("HTTP/1.1 $code " . $this->request_status($code));
        return json_encode($message, JSON_THROW_ON_ERROR | $this->headers);
    }

    /**
     * @param int $code
     *
     * @return string
     */
    private function request_status(int $code): string
    {
        return ($this->status[$code]) ?: $this->status[500];
    }
}