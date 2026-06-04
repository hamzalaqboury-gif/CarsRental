<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'ABC-1001' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80',
            'ABC-1002' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80',
            'ABC-1003' => 'https://images.unsplash.com/photo-1536700503339-1e771d4c14d4?w=800&q=80',
            'ABC-1004' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&q=80',
            'ABC-1005' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80',
            'ABC-1006' => 'https://images.unsplash.com/photo-1606611013016-969c19ba27bb?w=800&q=80',
            'ABC-1007' => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=800&q=80',
            'ABC-1008' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
        ];

        foreach ($images as $plate => $url) {
            DB::table('vehicles')
                ->where('license_plate', $plate)
                ->whereNull('image')
                ->update(['image' => $url]);
        }
    }

    public function down(): void
    {
        DB::table('vehicles')
            ->whereIn('license_plate', ['ABC-1001','ABC-1002','ABC-1003','ABC-1004','ABC-1005','ABC-1006','ABC-1007','ABC-1008'])
            ->update(['image' => null]);
    }
};
