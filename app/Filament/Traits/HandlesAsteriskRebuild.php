<?php

namespace App\Filament\Traits;

use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\CreateRecord;
use Filament\Events\{
    AfterCreate,
    AfterDelete,
    AfterBulkDelete,
    AfterUpdate
};
use App\Models\Tenant;

trait HandlesAsteriskRebuild
{
    /**
     * =========================
     * REGISTRA LISTENERS GLOBAIS
     * =========================
     */
    public static function getListeners(): array
    {
        return [
            AfterCreate::class   => 'afterCreateHook',
            AfterUpdate::class   => 'afterUpdateHook',
            AfterDelete::class   => 'afterDeleteHook',
            AfterBulkDelete::class => 'afterBulkDeleteHook',
        ];
    }

    /**
     * =========================
     * HOOK: DEPOIS DO CREATE
     * =========================
     */
    public static function afterCreateHook(AfterCreate $event): void
    {
        static::runAsteriskRebuild($event->record);
    }

    /**
     * =========================
     * HOOK: DEPOIS DO UPDATE
     * =========================
     */
    public static function afterUpdateHook(AfterUpdate $event): void
    {
        static::runAsteriskRebuild($event->record);
    }

    /**
     * =========================
     * HOOK: DEPOIS DO DELETE
     * =========================
     */
    public static function afterDeleteHook(AfterDelete $event): void
    {
        static::runAsteriskRebuild($event->record);
    }

    /**
     * ===============================
     * HOOK: DEPOIS DO BULK DELETE
     * ===============================
     */
    public static function afterBulkDeleteHook(AfterBulkDelete $event): void
    {

    }

    /**
     * ===================================================
     * FUNÇÃO CENTRAL: CHAMA O SERVICE DO ASTERISK
     * ===================================================
     */
    protected static function runAsteriskRebuild($record): void
    {

    }
}
