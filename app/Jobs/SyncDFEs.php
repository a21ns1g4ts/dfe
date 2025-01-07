<?php

namespace App\Jobs;

use App\DFEService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDFEs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected array $config,
        protected string $certificatePath,
        protected string $certificatePassword,
        protected $ultNSU = null
    ) {}

    public function handle()
    {
        $certificatePfx = file_get_contents($this->certificatePath);

        $dfeService = new DFEService($this->config, $certificatePfx, $this->certificatePassword);

        $ultNSU = $this->ultNSU;

        $dfeService->syncDFEs($ultNSU);
    }
}
