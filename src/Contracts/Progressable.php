<?php

declare(strict_types=1);

namespace ConsoleTVs\Progresser\Contracts;

use ConsoleTVs\Progresser\Models\Progress;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Progressable
{
    /**
     * Returns all the progressables.
     *
     * @return MorphMany
     */
    public function progresses(): MorphMany;

    /**
     * Finds the given progress on the model.
     *
     * @param string|null $name
     * @param string|null $group
     * @return Progress|null
     */
    public function findProgress(?string $name = null, ?string $group = null): ?Progress;
}
