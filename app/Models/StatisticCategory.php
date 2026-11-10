<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Wisatawan Nusantara
    public function nusantaraTouristStatistics()
    {
        return $this->hasMany(NusantaraTouristStatistic::class, 'statistic_category_id');
    }

    // Relasi ke Wisatawan Mancanegara (Ini yang menyebabkan error sebelumnya)
    public function foreignTouristStatistics()
    {
        return $this->hasMany(ForeignTouristStatistic::class, 'statistic_category_id');
    }

    // Relasi ke Ekonomi Kreatif (Opsional, jika sebelumnya sudah dibuat)
    public function creativeEconomyStatistics()
    {
        return $this->hasMany(CreativeEconomyStatistic::class, 'statistic_category_id');
    }

    public function nationalTouristStatistics()
    {
        return $this->hasMany(NationalTouristStatistic::class, 'statistic_category_id');
    }

    public function tourismServiceStatistics()
    {
        return $this->hasMany(TourismServiceStatistic::class, 'statistic_category_id');
    }

    public function tourismSatelliteStatistics()
    {
        return $this->hasMany(TourismSatelliteStatistic::class, 'statistic_category_id');
    }

    public function tourismForeignExchangeStatistics()
    {
        return $this->hasMany(TourismForeignExchangeStatistic::class, 'statistic_category_id');
    }

    public function restaurantStatistics()
    {
        return $this->hasMany(RestaurantStatistic::class, 'statistic_category_id');
    }
}
