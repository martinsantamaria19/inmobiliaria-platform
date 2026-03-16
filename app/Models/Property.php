<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'inmobiliaria_id',
        'titulo',
        'descripcion',
        'tipo',
        'operacion',
        'precio_venta',
        'precio_alquiler',
        'moneda',
        'estado',
        'dormitorios',
        'banos',
        'superficie_total',
        'superficie_construida',
        'garage',
        'piscina',
        'direccion',
        'barrio',
        'ciudad',
        'latitud',
        'longitud',
        'destacado',
        'views_count',
    ];

    protected $casts = [
        'garage' => 'boolean',
        'piscina' => 'boolean',
        'destacado' => 'boolean',
        'precio_venta' => 'decimal:2',
        'precio_alquiler' => 'decimal:2',
    ];

    public function inmobiliaria()
    {
        return $this->belongsTo(Inmobiliaria::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('orden');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    public function features()
    {
        return $this->hasMany(PropertyFeature::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->where('is_primary', true)->first();
        if ($primary) return $primary->url;
        $first = $this->images->first();
        if ($first) return $first->url;
        return 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop';
    }

    public function getPrecioFormateadoAttribute(): string
    {
        if ($this->operacion === 'venta' || $this->operacion === 'ambas') {
            if ($this->precio_venta) {
                return ($this->moneda === 'USD' ? 'USD ' : '$') . number_format($this->precio_venta, 0, ',', '.');
            }
        }
        if ($this->operacion === 'alquiler') {
            if ($this->precio_alquiler) {
                return ($this->moneda === 'USD' ? 'USD ' : '$') . number_format($this->precio_alquiler, 0, ',', '.') . '/mes';
            }
        }
        return 'Consultar';
    }

    public function scopeDestacado($query)
    {
        return $query->where('destacado', true);
    }

    public function scopeDisponible($query)
    {
        return $query->where('estado', 'disponible');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('titulo', 'like', "%{$search}%")
              ->orWhere('descripcion', 'like', "%{$search}%")
              ->orWhere('barrio', 'like', "%{$search}%")
              ->orWhere('ciudad', 'like', "%{$search}%");
        });
    }
}
