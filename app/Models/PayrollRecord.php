<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollRecord extends Model
{
    protected $fillable = ['employee_id','periode_from','periode_to','total_gaji','breakdown','created_by'];
    protected $casts = ['breakdown' => 'array'];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
