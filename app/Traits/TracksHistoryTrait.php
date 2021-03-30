<?php

namespace App\Traits;

use App\Models\Historico;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Filter Empresa of user
 */
trait TracksHistoryTrait
{
    /**
     * The "booting" method of the model.
     */
    public static function bootTracksHistoryTrait(): void
    {
        static::updated(function (self $model): void {
            $model->getUpdated($model)
                ->map(function ($value, $field) use ($model) {
                    return call_user_func_array([$model, 'getHistoryBody'], [$value, $field]);
                })
                ->each(function ($fields) use ($model) {
                    Historico::create([
                        'tipo'           => 2,
                        'historico_type' => $model->getTable(),
                        'historico_id'   => $model->id,
                        'user_id'        => Auth::user()->id,
                    ] + $fields);
                });
        });

        // static::updated(function (self $model): void {
        //     $updated = '';
        //     $model->getUpdated($model)
        //         ->map(function ($value, $field) use ($model) {
        //             return call_user_func_array([$model, 'getHistoryBody'], [$value, $field]);
        //         })
        //         ->each(function ($fields) use ($updated) {
        //             $updated = $updated . $fields['body'];
        //             // Historico::create([
        //             //     'tipo'           => 2,
        //             //     'historico_type' => $model->getTable(),
        //             //     'historico_id'   => $model->id,
        //             //     'user_id'        => Auth::user()->id,
        //             // ] + $fields);
        //         });

        //         if ($updated) {
        //             Historico::create([
        //                 'tipo'           => 2,
        //                 'historico_type' => $model->getTable(),
        //                 'historico_id'   => $model->id,
        //                 'user_id'        => Auth::user()->id,
        //                 'body'           => $updated
        //             ]);
        //         }

        // });
    }

    protected function getHistoryBody($value, $field)
    {
        return [
            'body' => "Atualizado {$field} para ${value}",
        ];
    }

    protected function getUpdated($model)
    {
        return collect($model->getDirty())->filter(function ($value, $key) {
            // We don't care if timestamps are dirty, we're not tracking those
            return !in_array($key, ['created_at', 'updated_at']);
        })->mapWithKeys(function ($value, $key) {
            // Take the field names and convert them into human readable strings for the description of the action
            // e.g. first_name -> first name
            return [str_replace('_', ' ', $key) => $value];
        });
    }
}
