<?php

namespace Modules\Dashboard\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardWidgetSetting extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_widget_settings';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'settings',
        'widget_id',
        'user_id',
        'order',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'settings' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function widget()
    {
        return $this->belongsTo(DashboardWidget::class);
    }
}
