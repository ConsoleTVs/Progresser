<?php

declare(strict_types=1);

namespace ConsoleTVs\Progresser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;
use JsonSerializable;
use Illuminate\Database\Eloquent\Builder;

class Progress extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var string[] 
     */
    protected $fillable = [
        'status',
        'current_step',
        'steps',
        'running',
        'failed',
        'failed_payload',
        'default_completed_status',
        'default_failed_status',
        'name',
        'purgeable',
        'group',
        'purge_when_completed',
        'purge_when_failed',
    ];

    /**
     * Attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'current_step' => 'integer',
        'steps' => 'integer',
        'running' => 'boolean',
        'failed' => 'boolean',
        'purgeable' => 'boolean',
        'purge_when_completed' => 'boolean',
        'purge_when_failed' => 'boolean',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => null,
        'default_completed_status' => null,
        'default_failed_status' => null,
        'current_step' => null,
        'steps' => null,
        'running' => false,
        'failed' => false,
        'failed_payload' => null,
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return Config::get('progresser.table', parent::getTable());
    }

    /**
     * Decodes the json of the failed payload.
     *
     * @param string $value
     * @return array
     */
    protected function getFailedPayloadAttribute(?string $value): mixed
    {
        return ($value === null)
            ? null
            : json_decode($value, associative: true);
    }

    /**
     * Scopes the result to return only the running progresses.
     * Additionaly, you can also specify the group you want to see.
     *
     * @param Builder $query
     * @param bool $value
     * @param string|null $group
     * @return Builder
     */
    public function scopeRunning(Builder $query, bool $value = true, ?string $group = null): Builder
    {
        return $query
            ->where('running', $value)
            ->when($group, fn (Builder $query) => $query->where('group', $group));
    }

    /**
     * Determines if the progress is currently running.
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->running;
    }

    /**
     * Determines if the progress has failed.
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return $this->failed;
    }

    /**
     * Determines if the progress is currently running.
     *
     * @return bool
     */
    public function hasCompleted(): bool
    {
        return !$this->isRunning() && !$this->hasFailed();
    }

    /**
     * Determines if the progress has steps.
     *
     * @return bool
     */
    public function hasSteps(): bool
    {
        return $this->steps !== null;
    }

    /**
     * Returns the percetnage given the current task.
     *
     * @return float|null
     */
    public function percentage(): ?float
    {
        return ($this->hasSteps())
            ? ($this->current_step * 100) / $this->steps
            : null;
    }

    /**
     * Sets the default complete status.
     *
     * @param string|null $default_completed_status
     * @return static
     */
    public function defaultCompleteStatus(string|null $default_completed_status = null): static
    {
        $this->update([
            'default_completed_status' => $default_completed_status,
        ]);

        return $this;
    }

    /**
     * Sets the default complete status.
     *
     * @param string|null $default_failed_status
     * @return static
     */
    public function defaultFailedStatus(string|null $default_failed_status = null): static
    {
        $this->update([
            'default_failed_status' => $default_failed_status,
        ]);

        return $this;
    }

    /**
     * Queries only the purgeable progressers.
     *
     * @param Builder $query
     * @param bool $value
     * @return Builder
     */
    public function scopePurgeable(Builder $query, bool $value = true): Builder
    {
        return $query->where('purgeable', $value);
    }

    /**
     * Sets the purgeable attribute of the given progress.
     *
     * @param bool $value
     * @return static
     */
    public function purgeable(bool $value = true): static
    {
        $this->purgeable = $value;

        return $this;
    }

    /**
     * Sets the default statues.
     *
     * @param string|null $completed_status
     * @param string|null $failed_status
     * @return bool
     */
    public function defaultStatuses(string|null $completed_status = null, string|null $failed_status = null): bool
    {
        return $this->update([
            'default_completed_status' => $completed_status,
            'default_failed_status' => $failed_status,
        ]);
    }

    /**
     * Starts a progress with the given message, steps and start index.
     *
     * @param string $message
     * @param int $steps
     * @param int $start_at
     * @return bool
     */
    public function start(string $message, int|null $steps = null, int $start_at = 0): bool
    {
        if (
            $this->isRunning() ||
            ($steps !== null && $steps < 1) ||
            $start_at < 0 ||
            $start_at > $steps
        ) {
            return false;
        }

        return $this->update([
            'status' => $message,
            'steps' => $steps,
            'current_step' => $start_at,
            'running' => true,
            'failed' => false,
            'failed_payload' => null,
        ]);
    }

    /**
     * Sets the current message of the step.
     *
     * @param string $message
     * @return bool
     */
    public function status(string $message): bool
    {
        return (!$this->isRunning())
            ? false
            : $this->update(['status' => $message]);
    }

    /**
     * Determines if the progress should be purged when failed.
     *
     * @param bool $value
     * @return static
     */
    public function purgeWhenFailed(bool $value = true): static
    {
        $this->purge_when_failed = $value;

        return $this;
    }

    /**
     * Determines if the progress should be purged when completed.
     *
     * @param bool $value
     * @return static
     */
    public function purgeWhenCompleted(bool $value = true): static
    {
        $this->purge_when_completed = $value;

        return $this;
    }

    /**
     * Marks the completion of the current step.
     *
     * @param string|null $message
     * @return bool
     */
    public function step(string|null $message = null): bool
    {
        if (!$this->isRunning()) {
            return false;
        }

        // Check if the completion of the step
        // should mark the completion of the progress.
        $current_step = $this->current_step + 1;
        if ($this->hasSteps() && $current_step === $this->steps) {
            return $this->complete($message);
        }

        return $this->update([
            'status' => $message ?? $this->status,
            'current_step' => $current_step,
        ]);
    }

    /**
     * Makes the progress fail with the given message
     * and the given payload in case the progress
     * was running.
     *
     * @param string|null $message
     * @param JsonSerializable|string|int|float|bool|null $payload
     * @return bool
     */
    public function fail(
        string|null $message = null,
        JsonSerializable|string|int|float|bool|null $payload = null
    ): bool {
        if (!$this->isRunning()) {
            return false;
        }

        if ($this->purgeable && $this->purge_when_failed) {
            return $this->delete();
        }

        return $this->update([
            'status' => $message ?? $this->default_failed_status ?? Config::get('progresser.statuses.failed'),
            'running' => false,
            'failed' => true,
            'failed_payload' => ($payload === null)
                ? null
                : json_encode($payload)
        ]);
    }

    /**
     * Completes the progress with the given message.
     * This completes all the remaining steps. Only happens
     * if the progress was running.
     *
     * Keep in mind, this is automatically called when
     * the
     *
     * @param string|null $message
     * @return bool
     */
    public function complete(string|null $message = null): bool
    {
        if (!$this->isRunning()) {
            return false;
        }

        if ($this->purgeable && $this->purge_when_completed) {
            return $this->delete();
        }

        return $this->update([
            'status' => $message ?? $this->default_completed_status ?? Config::get('progresser.statuses.complete'),
            'running' => false,
            'current_step' => $this->steps ?? $this->current_step + 1,
            'failed' => false,
            'failed_payload' => null,
        ]);
    }

    /**
     * Returns the progressable model.
     *
     * @return MorphTo|null
     */
    public function progressable(): ?MorphTo
    {
        return $this->morphTo();
    }
}
