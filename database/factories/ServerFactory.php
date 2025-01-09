<?php

namespace Database\Factories;

use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
{
    protected $model = Server::class;

    public function definition(): array
    {
        return [
            'project_id'    => 1,
            'name'          => $this->faker->word(),
            'ip'            => $this->faker->ipv4(),
            'ip_hash'       => uniqid(),
            'port'          => 22,
            'username'      => strtolower($this->faker->firstName()),
            'passphrase'    => uniqid(),
            'key_file_name' => $this->faker->word(),
        ];
    }
}
