<?php

declare(strict_types=1);

namespace ConsoleTVs\Progresser\Traits;

use ConsoleTVs\Progresser\Models\Progress;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Progressable
{
    /**
     * Returns all the progressables.
     *
     * @return MorphMany
     */
    public function progresses(): MorphMany
    {
        return $this->morphMany(Progress::class, 'progressable');
    }

    /**
     * Returns the first progress.
     *
     * @param string $name
     * @return Progress
     */
    public function progress(string $name): Progress
    {
        return $this
            ->progresses()
            ->firstOrCreate([
                'name' => $name
            ]);
    }
}
