<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Supplier extends Model
{
    use HasFactory,SoftDeletes;
  
    /**
     * The attributes that are mass assignable.
     *  
     * @var array
     */
    protected $fillable = [
        'supplierid', 'suppliername', 'sphone', 'slocation'
    ];
    protected $primaryKey = 'supplierid';
    protected $keyType = 'bigInteger';
    public $incrementing = false;
}