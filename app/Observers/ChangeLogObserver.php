<?php

namespace App\Observers;

use App\Models\ChangeLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChangeLogObserver
{
    public function created(Model $model)
    {
        $this->logChange($model, 'create');
    }

    public function updated(Model $model)
    {
        $this->logChange($model, 'update');
    }

    public function deleted(Model $model)
    {
        $this->logChange($model, 'delete');
    }

    protected function logChange(Model $model, $action)
    {
        $before = $action === 'update' ? $model->getOriginal() : null;
        $after = $action === 'delete' ? null : $model->getAttributes();

        ChangeLog::create([
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'before' => json_encode($before),
            'after' => json_encode($after),
            'created_by' => Auth::id()
        ]);
    }
}
