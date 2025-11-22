<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    protected static int $currentIndex = 0;

    protected static array $categoryNames = [
        'hoodies',
        'accessories',
        'bags',
        'jackets',
        'shorts',
        'shoes',
    ];

    protected static array $categoryImages = [
        'https://m.media-amazon.com/images/I/61HEM8RoZAL._AC_UL320_.jpg',
        'https://m.media-amazon.com/images/I/61ijf8wheQL._AC_SX522_.jpg',
        'https://m.media-amazon.com/images/I/71JV+Go-mML._AC_UL320_.jpg',
        'https://m.media-amazon.com/images/I/71rNJdgvB1L._AC_UL320_.jpg',
        'https://m.media-amazon.com/images/I/71h5hF1cE+L._AC_SX322_CB1169409_QL70_.jpg',
        'https://m.media-amazon.com/images/I/711lA5rk08L._AC_UL320_.jpg',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Mendapatkan total nama kategori yang tersedia
        $totalNames = count(self::$categoryNames);
        $totalNameImage = count(self::$categoryImages);

        // Memastikan currentIndex berada dalam batas array
        if (self::$currentIndex >= $totalNames && self::$currentIndex >= $totalNameImage) {
            // Jika sudah melebihi batas, kembali ke 0 (mengulang) atau
            // berikan nama acak dari daftar yang sama.
            // Di sini kita akan mengulang untuk memastikan 6 nama unik digunakan
            // sebelum mulai mengulang. Jika Anda memanggil factory(5), ini aman.
            self::$currentIndex = 0;
        }

        // Ambil nama kategori sesuai dengan index saat ini
        $name = self::$categoryNames[self::$currentIndex];
        $categoryImage = self::$categoryImages[self::$currentIndex];

        // Tingkatkan index untuk panggilan factory berikutnya
        self::$currentIndex++;

        return [
            'title' => $name,
            'image' => $categoryImage,
        ];
    }
}
