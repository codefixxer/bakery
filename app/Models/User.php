<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        
            'name',
            'email',
            'password',
            'created_by',
        ];
        

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status'            => 'boolean',
    ];

    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}
public function externalSupplies()
{
    return $this->hasMany(ExternalSupply::class);
}
public function equipment()
{
    return $this->hasMany(Equipment::class);
}
public function externalSupplyRecipes()
{
    return $this->hasMany(ExternalSupplyRecipe::class);
}
public function incomes()
{
    return $this->hasMany(Income::class);
}
public function ingredients()
{
    return $this->hasMany(Ingredient::class);
}
public function laborCosts()
{
    return $this->hasMany(LaborCost::class);
}
public function news()
{
    return $this->hasMany(News::class);
}
public function notifications()
{
    return $this->hasMany(Notification::class);
}
public function pastryChefs()
{
    return $this->hasMany(PastryChef::class);
}
public function productions()
{
    return $this->hasMany(Production::class);
}
public function productionDetails()
{
    return $this->hasMany(ProductionDetail::class);
}
public function recipes()
{
    return $this->hasMany(Recipe::class);
}
public function recipeCategories()
{
    return $this->hasMany(RecipeCategory::class);
}
public function recipeIngredients()
{
    return $this->hasMany(RecipeIngredient::class);
}
public function returnedGoods()
{
    return $this->hasMany(ReturnedGood::class);
}
public function showcases()
{
    return $this->hasMany(Showcase::class);
}
public function showcaseRecipes()
{
    return $this->hasMany(ShowcaseRecipe::class);
}

}
