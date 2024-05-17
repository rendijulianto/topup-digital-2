<?php

namespace App\Observers;

use App\Models\Log;

class ModelObserver
{
    /**
     * Handle the Log "created" event.
     */
    public function created(Log $log): void
    {
        $this->logActivity('created', $log);
    }

    /**
     * Handle the Log "updated" event.
     */
    public function updated(Log $log): void
    {
        $this->logActivity('updated', $log);
    }

    /**
     * Handle the Log "deleted" event.
     */
    public function deleted(Log $log): void
    {
        $this->logActivity('deleted', $log);
    }

    protected function logActivity(string $activity, Log $log): void
    {
        $log = new Log([
            'aksi' => $activity,
            'model' => get_class($log),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'keterangan' => $log->keterangan,
            'user_id' => '1',
            'data_lama' => json_encode($log->getOriginal()),
            'data_baru' => json_encode($log->getAttributes()),
        ]);
        dd($log);

        $log->save();
    }

    /**
     * Handle the Log "restored" event.
     */
    public function restored(Log $log): void
    {
        //
    }

    /**
     * Handle the Log "force deleted" event.
     */
    public function forceDeleted(Log $log): void
    {
        //
    }
}
