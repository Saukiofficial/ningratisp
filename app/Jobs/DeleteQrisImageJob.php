<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteQrisImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $filePath) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Storage::disk('public')->exists($this->filePath)) {
            Storage::disk('public')->delete($this->filePath);
        }
    }
}
