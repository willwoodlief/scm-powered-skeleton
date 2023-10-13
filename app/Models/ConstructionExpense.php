<?php

namespace App\Models;

use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int project_id
 * @property string transaction_type
 * @property string date
 * @property string business
 * @property string description
 * @property float amount
 *
 * employee_id is not numeric id but name
 * @property string employee_id
 * @property string created_at
 * @property string updated_at
 *
 * @property Project expense_project
 */
class ConstructionExpense extends Model
{
    use HasFactory;

    protected $table = 'construction_expenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'transaction_type',
        'date',
        'business',
        'description',
        'amount',
        'employee_id'
    ];

    protected static function booted(): void
    {
        static::creating(function (ConstructionExpense $expense) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_CONSTRUCTION_EXPENSE, $expense);
        });

        static::created(function (ConstructionExpense $expense) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_CONSTRUCTION_EXPENSE, $expense);
        });

        static::updating(function (ConstructionExpense $expense) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_CONSTRUCTION_EXPENSE, $expense);
        });

        static::updated(function (ConstructionExpense $expense) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_CONSTRUCTION_EXPENSE, $expense);
        });

        static::deleted(function (ConstructionExpense $expense) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_CONSTRUCTION_EXPENSE, $expense);
        });

        static::deleting(function (ConstructionExpense $expense) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_CONSTRUCTION_EXPENSE, $expense);
        });
    }

    public function expense_project() : BelongsTo {
        return $this->belongsTo('App\Models\Project','project_id');
    }

    public static function totalExpenses() : float {
        return (float)DB::select("SELECT SUM(amount) AS total_expenses FROM construction_expenses")[0]?->total_expenses??0;
    }

    /**
     * @return ConstructionExpense[]
     */
    public static function getAllConstructionExpenses() {
        $query = function() : Builder {
            $query = ConstructionExpense::where('id','>',0)
                /**
                 * @uses ConstructionExpense::expense_project()
                 */
                ->with('expense_project');
            return Eventy::filter(Plugin::FILTER_QUERY_EXPENSES, $query);
        };

        return $query()->get();
    }
}
