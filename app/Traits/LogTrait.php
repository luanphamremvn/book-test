<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Exception;

trait LogTrait
{
    /**
     * log error message
     *
     * @param string $value
     * @param string $message
     * @param array $context
     * @return void
     * @throws Exception
     */
    protected function logError(string $value, string $message, array $context = []): void
    {
        // Log the error message with the context
        $message = sprintf('%s: %s', $value, $message);
        Log::error($this->logPrefix . ' ' . $message, $context);
    }
}
