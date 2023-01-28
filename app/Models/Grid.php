<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Grid extends Model
{
    use HasFactory, Traits\ScopeMineTrait;

    protected $guarded = ['id'];

    // protected $casts = [
    //     'grid_json' => 'json',
    // ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($record) {
            // We dont need configuration file anymore.
            if (\File::exists($record->file_path)) {
                 \File::delete($record->file_path);
             }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function running_bots()
    {
        return $this->hasMany(Bot::class)
            ->where('pid', '>', 0)
            ->whereNotNull('started_at');
    }

    public function saveConfigToDisk()
    {
        if (!\App::environment('local')) {
            $disk = Storage::build([
                'driver' => 'local',
                'root' => $this->storage_path,
            ]);
            $disk->put($this->file_name, $this->grid_json);
        }
    }

    public function getFilePathAttribute()
    {
        return "{$this->storage_path}/{$this->file_name}";
    }

    public function getFileNameAttribute()
    {
        $base_name = Str::upper(Str::slug($this->name));

        return "$base_name.json";
    }

    public function getCommonStoragePathAttribute()
    {
        $passivbot_path = config('antbot.paths.passivbot_path');
        return "$passivbot_path/configs/live";
    }

    public function getStoragePathAttribute()
    {
        return "{$this->common_storage_path}/{$this->user->id}";
    }

    public function getGridAttribute()
    {
        return json_decode($this->grid_json, true);
    }

    public function getTypeAttribute()
    {
        $grid = $this->grid;
        if (\Arr::get($grid, 'long.ddown_factor'))
            return 'recursive';
        elseif (\Arr::get($grid, 'long.eprice_exp_base'))
            return 'static';
        elseif (\Arr::get($grid, 'long.eqty_exp_base'))
            return 'neat';
        else
            return 'N/A';
    }
}
