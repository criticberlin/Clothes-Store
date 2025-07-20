<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Clear existing governorates
        Governorate::truncate();
        
        // Path to the CSV file
        $csvFile = base_path('egypt-governorates-and-cities-db-master/governorates.csv');
        
        if (File::exists($csvFile)) {
            $governorates = $this->readCsv($csvFile);
            
            if (!empty($governorates)) {
                foreach ($governorates as $governorate) {
                    try {
                        Governorate::create([
                            'id' => $governorate['id'],
                            'name_ar' => $governorate['governorate_name_ar'],
                            'name_en' => $governorate['governorate_name_en'],
                            'is_active' => true,
                        ]);
                    } catch (\Exception $e) {
                        $this->command->error("Error importing governorate: " . $e->getMessage());
                    }
                }
                
                $this->command->info('Governorates seeded successfully! Total: ' . count($governorates));
            } else {
                $this->command->error('No governorates found in CSV file.');
            }
        } else {
            $this->command->error('Governorates CSV file not found at: ' . $csvFile);
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
