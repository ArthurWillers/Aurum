<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoryType;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => CategoryType::class,
    ];

    /**
     * Get the user that owns the category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if the category is of type Income.
     *
     * @return bool
     */
    public function isIncome(): bool
    {
        return $this->type === CategoryType::Income;
    }

    /**
     * Retorna todas as despesas associadas a esta categoria
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /** 
     * Retorna todas as receitas associadas a esta categoria
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
