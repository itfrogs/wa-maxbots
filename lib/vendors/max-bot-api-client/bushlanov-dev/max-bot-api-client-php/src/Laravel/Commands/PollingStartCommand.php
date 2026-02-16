<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Laravel\Commands;

use BushlanovDev\MaxMessengerBot\Laravel\MaxBotManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Artisan command to start processing updates via long polling.
 */
class PollingStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maxbot:polling:start
                            {--timeout=90 : Timeout in seconds for long polling}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the bot to process updates via long polling';

    /**
     * Execute the console command.
     */
    public function handle(MaxBotManager $botManager): int
    {
        $timeout = (int)$this->option('timeout');

        $this->info("Starting long polling with a timeout of $timeout seconds... Press Ctrl+C to stop.");

        try {
            $botManager->startLongPolling($timeout);

            // @codeCoverageIgnoreStart
            // This part is unreachable as startLongPolling is an infinite loop
            return self::SUCCESS;
            // @codeCoverageIgnoreEnd
        } catch (Throwable $e) {
            Log::error("Long polling failed to start or crashed: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            $this->error("❌ Long polling failed: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
