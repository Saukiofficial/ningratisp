<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class TemplateMessage extends Model
{
    protected $guarded = ['id'];

    public static function getOptionLabel(): array
    {
        return [
            (new Voucher)->getMorphClass() => 'Voucher',
        ];
    }

    public static function getTemplate(Model $model): ?Model
    {
        $template = self::where('used_at', $model->getMorphClass())->first();
        if (empty($template)) {
            Notification::make()
                ->title('Template not found')
                ->body('Please create template first')
                ->danger()
                ->send();
            return null;
        }

        return $template;
    }
}
