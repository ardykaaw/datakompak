<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetrySyncFailedData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $modelId;
    protected $sourceConnection;
    protected $attempts = 0;
    public $maxAttempts = 3;

    public function __construct($model, $modelId, $sourceConnection)
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->sourceConnection = $sourceConnection;
    }

    public function handle()
    {
        try {
            $modelClass = "App\\Models\\{$this->model}";
            $data = $modelClass::on($this->sourceConnection)
                            ->find($this->modelId);

            if (!$data) {
                Log::error("Data not found for retry sync", [
                    'model' => $this->model,
                    'id' => $this->modelId,
                    'connection' => $this->sourceConnection
                ]);
                return;
            }

            // Re-trigger event
            $eventClass = "App\\Events\\{$this->model}Created";
            event(new $eventClass($data, $this->sourceConnection));

            Log::info("Successfully retried sync", [
                'model' => $this->model,
                'id' => $this->modelId
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to retry sync", [
                'model' => $this->model,
                'id' => $this->modelId,
                'error' => $e->getMessage()
            ]);

            if ($this->attempts < $this->maxAttempts) {
                $this->attempts++;
                $this->release(30); // Retry after 30 seconds
            }
        }
    }
} 