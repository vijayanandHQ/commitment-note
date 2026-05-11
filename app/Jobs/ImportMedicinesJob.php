<?php
// app/Jobs/ImportMedicinesJob.php

namespace App\Jobs;

use App\Imports\MedicinesImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportMedicinesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $userId;

    public function __construct($filePath, $userId)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            $import = new MedicinesImport();
            Excel::import($import, $this->filePath);
            
            Log::info('Medicine import completed', [
                'user_id' => $this->userId,
                'file' => $this->filePath,
                'success_count' => $import->getSuccessCount()
            ]);
            
            // You can send notification to user here
        } catch (\Exception $e) {
            Log::error('Medicine import job failed', [
                'user_id' => $this->userId,
                'file' => $this->filePath,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}