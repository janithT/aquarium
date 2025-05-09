<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Processors\AvatarProcessor;
use Carbon\Carbon;

class FishTemplate extends Model
{
    use HasFactory, SoftDeletes;

    // Define the table associated with the model

    // Define the fillable columns for mass assignment
    protected $fillable = [
        'animal_name',
        'unique_identity',
        'status',
    ];

    // Optionally, you can define any casts for your attributes
    protected $casts = [
        'status' => 'string', // Casting 'status' as string
    ];

    // Optionally, if you want to customize the date formats for soft deletes or timestamps
    protected $dates = ['deleted_at'];


    public function getAvatarFilenameAttribute() {
        if (!empty($this->file)) {
            return $this->file->name;
        }

        return null;
    }

    public function getAvatarAttribute() {
        return AvatarProcessor::get($this);
    }

    public function getCreatedAttribute() {
        if (empty($this->created_at)) {
            return null;
        }

        return $this->created_at->toFormattedDateString();
    }

    public function getCreatedMmDdYyyyAttribute() {
        if (empty($this->created_at)) {
            return null;
        }

        return $this->created_at->format('m-d-Y');
    }

    public function setCreatedDateAttribute( $value ) {
        try {
            $this->attributes['created_at'] = new Carbon($value);
        } catch (\Exception $exception) {
            $this->attributes['created_at'] = now();
        }
    }
}
