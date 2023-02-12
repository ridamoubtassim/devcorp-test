<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Attributes
 * @property int id
 * @property int created_by
 * @property int project_id
 * @property string title
 * @property string description
 * @property string status
 * @property DateTime created_at
 * @property DateTime updated_at
 *
 * Functions
 */
class Task extends BaseModel
{
    use HasFactory;

    /**
     * @const int
     */
    const TODO_STATUS = 1;

    /**
     * @const int
     */
    const PENDING_STATUS = 2;

    /**
     * @const int
     */
    const REVIEW_STATUS = 3;

    /**
     * @const int
     */
    const DONE_STATUS = 4;

    /**
     * @const array
     */
    const STATUSES = [
        self::TODO_STATUS => 'Todo',
        self::PENDING_STATUS => 'Pending',
        self::REVIEW_STATUS => 'Review',
        self::DONE_STATUS => 'Done',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by', 'project_id', 'title', 'description',
        'status', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Functions ...

    // Relations ...

    /**
     * User who created this task
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Project
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * Assigned users to this task
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserTask::class, 'task_id', 'id');
    }
}
