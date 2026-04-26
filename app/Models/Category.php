<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'color'];

    /**
     * Daftar Kategori Statis MKAS
     */
    public static function getStaticList()
    {
        return [
            ['id' => 1, 'name' => 'Makanan Pokok', 'icon' => 'shopping-cart', 'color' => 'blue'],
            ['id' => 2, 'name' => 'Listrik', 'icon' => 'bolt', 'color' => 'amber'],
            ['id' => 3, 'name' => 'Jasa Bersih', 'icon' => 'sparkles', 'color' => 'emerald'],
            ['id' => 4, 'name' => 'Perjalanan', 'icon' => 'map', 'color' => 'indigo'],
            ['id' => 5, 'name' => 'Olahraga', 'icon' => 'heart', 'color' => 'rose'],
            ['id' => 6, 'name' => 'Air', 'icon' => 'droplet', 'color' => 'sky'],
            ['id' => 7, 'name' => 'Internet', 'icon' => 'wifi', 'color' => 'violet'],
            ['id' => 8, 'name' => 'Perabotan', 'icon' => 'home', 'color' => 'orange'],
            ['id' => 9, 'name' => 'Lainnya', 'icon' => 'dots-horizontal', 'color' => 'slate'],
        ];
    }

    /**
     * Helper untuk mendapatkan Ikon SVG berdasarkan slug/nama
     */
    public static function getIconHtml($iconName, $class = "w-6 h-6")
    {
        $icons = [
            'shopping-cart' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
            'bolt' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
            'sparkles' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>',
            'map' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>',
            'heart' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>',
            'droplet' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13c0 5-3.5 7-8 7s-8-2-8-7c0-3 3-7 8-11 5 4 8 8 8 11z" /></svg>',
            'wifi' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>',
            'home' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>',
            'dots-horizontal' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>',
        ];

        return $icons[$iconName] ?? $icons['dots-horizontal'];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
