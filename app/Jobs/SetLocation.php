<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SetLocation implements ShouldQueue
{
    use Queueable;

    protected $location;

    /**
     * Create a new job instance.
     */
    public function __construct($ip, public $item, public $columnName)
    {
        $this->location = get_location($ip);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->item->update([$this->columnName => (array)$this->location ?? '']);
    }
}
