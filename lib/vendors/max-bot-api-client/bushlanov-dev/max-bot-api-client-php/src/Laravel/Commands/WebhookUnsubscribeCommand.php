<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Laravel\Commands;

use BushlanovDev\MaxMessengerBot\Api;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Artisan command for unsubscribing from webhook updates.
 */
class WebhookUnsubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maxbot:webhook:unsubscribe
                            {url : The webhook URL to unsubscribe from}
                            {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unsubscribe bot from webhook updates';

    /**
     * Execute the console command.
     */
    public function handle(Api $api): int
    {
        $url = (string)$this->argument('url'); // @phpstan-ignore-line
        $confirm = $this->option('confirm');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error('Invalid URL provided.');

            return self::FAILURE;
        }

        if (!$confirm) {
            if (!$this->confirm("Are you sure you want to unsubscribe from webhook URL: $url?")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        }

        $this->info('Unsubscribing from webhook...');
        $this->line("URL: $url");

        try {
            $result = $api->unsubscribe($url);

            if ($result->success) {
                $this->info('✅ Successfully unsubscribed from webhook!');

                return self::SUCCESS;
            } else {
                $this->error('❌ Failed to unsubscribe from webhook.');
                $this->line("Response: $result->message");

                return self::FAILURE;
            }
        } catch (Throwable $e) {
            Log::error("Webhook unsubscribe error: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            $this->error("❌ Webhook unsubscribe error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
