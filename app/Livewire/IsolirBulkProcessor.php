<?php

namespace App\Livewire;

use App\Services\CustomerIsolirService;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class IsolirBulkProcessor extends Component
{
    public string $type = 'sync';
    public array $tasks = [];
    public int $currentIndex = 0;
    public int $totalTasks = 0;
    public bool $isProcessing = false;
    public string $status = 'Ready to start';
    public array $log = [];
    public int $successCount = 0;
    public int $errorCount = 0;
    public int $skippedCount = 0;

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function start()
    {
        $this->isProcessing = true;
        $this->status = 'Fetching tasks...';
        $this->log = [];
        $this->currentIndex = 0;
        $this->successCount = 0;
        $this->errorCount = 0;
        $this->skippedCount = 0;

        $service = app(CustomerIsolirService::class);

        $this->tasks = $this->type === 'sync'
            ? $service->getSyncTasks()
            : $service->getOpenTasks();

        $this->totalTasks = count($this->tasks);

        if ($this->totalTasks === 0) {
            $this->isProcessing = false;
            $this->status = 'No tasks to process.';
            Notification::make()->title('No tasks found')->info()->send();
            return;
        }

        $this->status = 'Processing...';
        $this->dispatch('process-next');
    }

    #[On('process-next')]
    public function processNext()
    {
        if (!$this->isProcessing) return;

        if ($this->currentIndex < $this->totalTasks) {
            $task = $this->tasks[$this->currentIndex];
            $service = app(CustomerIsolirService::class);
            $username = $task['username'] ?? 'unknown';

            try {
                if ($this->type === 'sync') {
                    $result = $service->processSyncTask($task);
                } else {
                    $result = $service->processOpenTask($task);
                }

                // Check if service returns a skip signal (optional)
                if (isset($result['skipped']) && $result['skipped']) {
                    $this->skippedCount++;
                    $this->log[] = ['status' => 'skip', 'message' => "Skipped: {$username}"];
                } else {
                    $this->successCount++;
                    $this->log[] = ['status' => 'ok', 'message' => "Processed: {$username}"];
                }
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->log[] = ['status' => 'error', 'message' => "Error: {$username} — {$e->getMessage()}"];
                Log::error("Isolir bulk error: " . $e->getMessage());
            }

            $this->currentIndex++;

            if ($this->currentIndex < $this->totalTasks) {
                $this->status = "Processing {$username} ({$this->currentIndex}/{$this->totalTasks})";
                $this->dispatch('process-next');
            } else {
                $this->isProcessing = false;
                $this->status = 'Completed!';
                Notification::make()->title('Process Completed')->success()->send();
            }
        }
    }

    public function render()
    {
        return view('livewire.isolir-bulk-processor');
    }
}
