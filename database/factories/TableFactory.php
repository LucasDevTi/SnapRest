<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $status = rand(0, 4);
        // switch ($status) {
        //     case 0:
        //         $descriptionStatus = "Liberada";
        //         break;
        //     case 1:
        //         $descriptionStatus = "Aberta";
        //         break;
        //     case 2:
        //         $descriptionStatus = "Fechada";
        //         break;
        //     case 3:
        //         $descriptionStatus = "Reservada";
        //         break;
        //     case 4:
        //         $descriptionStatus = "Inativa";
        //         break;
        //     default:
        //         $descriptionStatus = "teste";
        //         break;
        // }

        return [
            'status' =>  0,
            'description_status' => "Liberada"

        ];
    }
}
