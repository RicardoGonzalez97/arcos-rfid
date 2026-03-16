<?php
namespace Database\Seeders;
use App\Models\SupplierAppointmentSlotDock;
use Illuminate\Database\Seeder;

class SupplierAppointmentSlotDockSeeder extends Seeder
{
    public function run(): void
    {
        $docks = [
            ['number' => 1, 'name' => 'Dock 1'],
            ['number' => 2, 'name' => 'Dock 2'],
            ['number' => 3, 'name' => 'Dock 3'],
            ['number' => 4, 'name' => 'Dock 4'],
        ];

        foreach ($docks as $dock) {
            SupplierAppointmentSlotDock::updateOrCreate(
                ['number' => $dock['number']],
                $dock
            );
        }
    }
}