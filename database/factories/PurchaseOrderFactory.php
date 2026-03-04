<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('es_MX');

        $startDate = $faker->dateTimeBetween('-1 month');
        $subtotal = $faker->randomFloat(2, 1000, 50000);
        $iva = $subtotal * 0.16;
        $costoManiobras = $faker->randomFloat(2, 0, 5000);
        $total = $subtotal + $iva + $costoManiobras;

        return [
            'company_id' => null,
            'supplier_id' => null,
            'estado_orden' => 'ACTIVO',
            'fecha' => $faker->date(),
            'proveedor' => $faker->company(),
            'numero_proveedor' => $faker->bothify('PROV-####'),
            'fecha_inicial' => $startDate,
            'fecha_entrega' => (clone $startDate)->modify('+3 days'),
            'fecha_envio' => (clone $startDate)->modify('+2 days'),
            'folio_periodo' => $faker->bothify('FP-####'),
            'tipo' => $faker->randomElement(['NORMAL', 'URGENTE']),
            'solicitante' => $faker->name(),
            'no_determinante' => $faker->bothify('ND-####'),
            'no_ot' => $faker->bothify('OT-####'),
            'nombre_determinante' => $faker->name(),
            'direccion_entrega' => $faker->address(),
            'ciudad' => $faker->city(),
            'estado' => $faker->state(),
            'formato_negocio' => $faker->randomElement(['LOCAL', 'FORANEO']),
            'facturar_a' => $faker->company(),
            'comentarios' => $faker->optional()->sentence(),
            'subtotal' => $subtotal,
            'iva' => $iva,
            'costo_maniobras' => $costoManiobras,
            'total' => $total,
            'aceptado_por' => $faker->optional()->name(),
            'fecha_aceptacion' => $faker->optional()->dateTimeBetween($startDate),
            'codigo_validacion' => strtoupper(Str::random(10)),
        ];
    }

    public function withItems(int $count = 10): static
    {
        return $this->has(
            PurchaseOrderItem::factory()->count($count),
            'items'
        );
    }
}