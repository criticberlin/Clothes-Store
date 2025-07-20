<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Clear existing cities
        City::truncate();
        
        // Path to the CSV file
        $csvFile = base_path('egypt-governorates-and-cities-db-master/cities.csv');
        
        if (File::exists($csvFile)) {
            $cities = $this->readCsv($csvFile);
            
            if (!empty($cities)) {
                $importedCount = 0;
                $errorCount = 0;
                
                foreach ($cities as $city) {
                    try {
                        City::create([
                            'id' => $city['id'],
                            'governorate_id' => $city['governorate_id'],
                            'name_ar' => $city['city_name_ar'],
                            'name_en' => $city['city_name_en'],
                            'is_active' => true,
                        ]);
                        $importedCount++;
                    } catch (\Exception $e) {
                        $errorCount++;
                        $this->command->error("Error importing city {$city['id']}: " . $e->getMessage());
                    }
                }
                
                $this->command->info("Cities seeded successfully! Imported: {$importedCount}, Errors: {$errorCount}");
            } else {
                $this->command->error('No cities found in CSV file.');
            }
        } else {
            $this->command->error('Cities CSV file not found at: ' . $csvFile);
        }
        
        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
    
    /**
     * Read CSV file and return array of data.
     */
    private function readCsv(string $file): array
    {
        $data = [];
        $header = [];
        
        if (($handle = fopen($file, 'r')) !== false) {
            // Read the header row
            if (($row = fgetcsv($handle)) !== false) {
                $header = $row;
            }
            
            // Read the data rows
            while (($row = fgetcsv($handle)) !== false) {
                if (count($header) === count($row)) {
                    $data[] = array_combine($header, $row);
                }
            }
            
            fclose($handle);
        }
        
        return $data;
    }
}
