<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:location
                                            {country : the country}
                                            {city : the city}
                                            {street : the street}
                                            {house_number : the house number}
                                            {post_code : the post code}
                                            {lat : the lat}
                                            {lon : the lon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'location cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $country = $this->argument('country');
        $city = $this->argument('city');
        $street = $this->argument('street');
        $postCode = $this->argument('post_code');
        $houseNumber = str_replace('-', ' ', $this->argument('house_number'));
        $lat = $this->argument('lat');
        $lon = $this->argument('lon');
        $key = sprintf("%s:%s-%s-%s", 'location', $country, $postCode, $houseNumber);
        $bool = Cache::forever($key, [
            'country' => $country, 'city' => $city,
            'street' => $street, 'house_number' => $houseNumber, 'post_code' => $postCode,
            'lat' => $lat, 'lon' => $lon,
        ]);
        if ($bool) {
            $this->info('location cache successful');
        } else {
            $this->error('location cache failed');
        }
    }
}
