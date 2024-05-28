<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Prefix, Brand};

class PrefixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefixes = [
            'telkomsel' => ["0811","0812","0813","0821","0822","0823","0851","0852","0853"],
            'indosat' => ["0814","0815","0816","0855","0856","0857","0858"],
            'tri' => ["0895","0896","0897","0898","0899"],
            'smartfren' => ["0881","0882","0883","0884","0885","0886","0887","0888","0889"],
            'xl' => ["0817","0818","0819","0859","0877","0878"],
            'axis' => ["0831","0832","0833","0838"],
        ];

        foreach ($prefixes as $key => $value) {
            foreach ($value as $prefix) {
                $brand = Brand::where('name', 'like', '%'.$key.'%')->first();
                if ($brand) {
                    $brand_id = $brand->id;
                } else {
                    $brand_id = null;
                }
                Prefix::create([
                    'number' => $prefix,
                    'brand_id' => $brand_id,
                ]);
            }
        }
    }
}
