<?php

declare(strict_types=1);

namespace ConsoleTVs\Progresser\Traits;

use ConsoleTVs\Progresser\Models\Progress;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

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
     * Finds the given progress on the model.
     *
     * @param string|null $name
     * @param string|null $group
     * @return Progress|null
     */
    public function findProgress(?string $name = null, ?string $group = null): ?Progress
    {
        return $this
            ->progresses()
            ->when($name, fn (Builder $query) => $query->where('name', $name))
            ->when($group, fn (Builder $query) => $query->where('group', $group))
            ->first();
    }
}
