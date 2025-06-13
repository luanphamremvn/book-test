<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogTrait
{

    /**
     * log error message
     *
     * @param string $value
     * @param string $message
     * @param array $context
     */
    protected function logError(string $value, string $message, array $context = []): void
    {
        // Log the error message with the context
        $message = sprintf('%s: %s', $value, $message);

        sprintf(FORMAT_LOG, $value, $this?->logPrefix, $message);
        Log::error($this->logPrefix . ' ' . $message, $context);
    }
}
