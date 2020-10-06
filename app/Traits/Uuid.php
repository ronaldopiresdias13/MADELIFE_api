<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuid
{
    /**
     * Indicates if the IDs are UUIDs.
     *
     * @return bool
     */
    protected function keyIsUuid(): bool
    {
        return true;
    }

    /**
     * The UUID version to use.
     *
     * @return int
     */
    protected function uuidVersion(): int
    {
        return 4;
    }

    /**
     * The "booting" method of the model.
     */
    public static function bootUuid(): void
    {
        static::creating(function (self $model): void {
            if (!$model->id && empty($model->id)) {
                $model->id = $model->generateUuid();
            }
            // if (! $model->uuid && empty($model->uuid)) {
            //     $model->uuid = $model->generateUuid();
            // }
        });
    }

    /**
     * @throws \Exception
     * @return string
     */
    protected function generateUuid(): string
    {
        switch ($this->uuidVersion()) {
            case 1:
                return RamseyUuid::uuid1()->toString();
            case 4:
                return RamseyUuid::uuid4()->toString();
        }

        throw new Exception("UUID version [{$this->uuidVersion()}] not supported.");
    }
}
