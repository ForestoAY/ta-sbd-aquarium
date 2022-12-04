<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Fish extends Model
{
    use HasFactory,SoftDeletes;
  
    /**
     * The attributes that are mass assignable.
     *  
     * @var array
     */
    protected $fillable = [
        'fishid', 'fishname','price', 'stock', 'supplierid','storeid'
    ];
    protected $primaryKey = 'fishid';
    protected $KeyType = 'bigInteger';
    public $incrementing = false;
}