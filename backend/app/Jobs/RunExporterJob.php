<?php

namespace App\Jobs;

use App\Mail\ExportCompletedSuccessfully;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Export;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RunExporterJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $export;

    /**
     * Create a new job instance.
     *
     * @param mixed $action
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->export->status = 'RUNNING';
        $this->export->save();
        $start_time = microtime(true);

        try {
            "App\\Actions\\Exports\\{$this->export->action_class}"::run($this->export);

            $this->export->fresh();
            $this->export->status = 'FINISHED';
            $this->export->duration = round(microtime(true) - $start_time);
            $this->export->save();

            if ($this->export->params['emails'] ?? false) {
                $to = [];
                foreach (explode(',', $this->export->params['emails']) as $email) {
                    $to[] = trim($email);
                }
                Mail::to($to)->send(new ExportCompletedSuccessfully($this->export->file));
            }
        } catch (\Exception|\Error $ex) {
            Log::error($ex);

            $this->export->status = 'FAILED';
            $this->export->message = $ex->getMessage();
            $this->export->duration = round(microtime(true) - $start_time);
            $this->export->save();
        }
    }
}
