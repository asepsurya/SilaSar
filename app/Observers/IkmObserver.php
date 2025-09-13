<?php

namespace App\Observers;

use App\Models\ikm;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
class IkmObserver
{
    /**
     * Handle the Ikm "created" event.
     */
    public function created(ikm $ikm): void
    {
          activity()
            ->causedBy(Auth::user())
            ->performedOn($ikm)
            ->log('Menambahkan data IKM');
    }

    /**
     * Handle the Ikm "updated" event.
     */
    public function updated(ikm $ikm): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($ikm)
            ->withProperties([
                'old' => $ikm->getOriginal(),
                'new' => $ikm->getChanges(),
            ])
            ->log('Memperbarui data IKM');
    }

    /**
     * Handle the Ikm "deleted" event.
     */
    public function deleted(ikm $ikm): void
    {
         activity()
            ->causedBy(Auth::user())
            ->performedOn($ikm)
            ->log('Menghapus data IKM');
    }

    /**
     * Handle the Ikm "restored" event.
     */
    public function restored(ikm $ikm): void
    {
        //
    }

    /**
     * Handle the Ikm "force deleted" event.
     */
    public function forceDeleted(ikm $ikm): void
    {
        //
    }
}
