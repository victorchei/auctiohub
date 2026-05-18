<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->log('created', $model, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $this->log('updated', $model, ['changes' => $model->getChanges(), 'original' => array_intersect_key($model->getOriginal(), $model->getChanges())]);
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
