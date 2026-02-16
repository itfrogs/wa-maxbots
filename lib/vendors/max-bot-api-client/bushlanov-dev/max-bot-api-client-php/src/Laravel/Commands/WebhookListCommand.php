<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Laravel\Commands;

use BushlanovDev\MaxMessengerBot\Api;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Artisan command for listing active webhook subscriptions.
 */
class WebhookListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maxbot:webhook:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all active webhook subscriptions';

    /**
     * Execute the console command.
     */
    public function handle(Api $api): int
    {
        $this->info('Fetching webhook subscriptions...');

        try {
            $subscriptions = $api->getSubscriptions();

            if (empty($subscriptions)) {
                $this->info('No active webhook subscriptions found.');

                return self::SUCCESS;
            }

            $this->info('Found ' . count($subscriptions) . ' active webhook subscription(s):');
            $this->newLine();

            $headers = ['URL', 'Update Types', 'Created At'];
            $rows = [];

            foreach ($subscriptions as $subscription) {
                $rows[] = [
                    $subscription->url,
                    implode(', ', $subscription->updateTypes ? array_map(fn (UpdateType $updateType) => $updateType->value, $subscription->updateTypes) : ['all']),
                    date('Y-m-d H:i:s', $subscription->time),
                ];
            }

            $this->table($headers, $rows);

            return self::SUCCESS;
        } catch (Throwable $e) {
            Log::error("Webhook list error: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            $this->error("❌ Webhook list error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
