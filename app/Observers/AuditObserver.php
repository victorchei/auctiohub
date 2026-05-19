<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    /** Поля, які не логуються (sensitive дані). */
    protected const REDACTED_KEYS = ['password', 'remember_token', 'api_token'];

    public function created(Model $model): void
    {
        $this->log('created', $model, $this->redact($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $changes = $this->redact($model->getChanges());
        $original = $this->redact(array_intersect_key($model->getOriginal(), $model->getChanges()));
        $this->log('updated', $model, ['changes' => $changes, 'original' => $original]);
    }

    protected function redact(array $payload): array
    {
        foreach (self::REDACTED_KEYS as $key) {
            if (array_key_exists($key, $payload)) {
                $payload[$key] = '***';
            }
        }
        return $payload;
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model, ['id' => $model->getKey()]);
    }

    public function restored(Model $model): void
    {
        $this->log('restored', $model, ['id' => $model->getKey()]);
    }

    protected function log(string $action, Model $model, array $payload): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => get_class($model),
            'subject_id' => $model->getKey() ?? 0,
            'payload' => $payload,
        ]);
    }
}
