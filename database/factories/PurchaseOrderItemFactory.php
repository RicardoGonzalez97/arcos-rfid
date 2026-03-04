<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderItem>
 */
class PurchaseOrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        $faker = \Faker\Factory::create('es_MX');

        $cantidad = $faker->numberBetween(1, 5);
        $precioUnitario = $faker->randomFloat(2, 100, 5000);
        $subtotal = $cantidad * $precioUnitario;

        return [
            // ❌ NO pongas purchase_order_id aquí

            'codigo_cliente' => $faker->bothify('WM-#####'),
            'modelo' => strtoupper($faker->bothify('MOD-###')),
            'marca' => $faker->company(),
            'fecha_entrega' => $faker->dateTimeBetween('now', '+1 month'),
            'descripcion' => $faker->sentence(),
            'cantidad' => $cantidad,
            'precio_unitario' => $precioUnitario,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ];
    }
}
