<?php

namespace Database\Seeders;

use App\Models\Candidato;
use App\Models\Formato;
use App\Models\Lugar;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataInicialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->usuarios();
        $this->lugares();
        $this->formatos();
        $this->candidatos();
        $this->emularFormatos();
    }

    private function usuarios()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'identificacion' => '1102796781',
            'password' => bcrypt('1102796781')
        ]);
        for ($i = 1; $i <= 15; $i++){
            $usuario = User::create([
                'name' => 'Usuario '.$i,
                'email' => 'usuario'.$i.'@email.com',
                'identificacion' => '1234567'.$i,
                'password' => bcrypt('1234567'.$i)
            ]);

            $usuario->createToken('auth_token')->plainTextToken;
        }
    }

    private function lugares()
    {
        for ($i = 1; $i <= 15; $i++){
            Lugar::create([
                'nombre' => 'Lugar '.$i,
                'slug' => Str::slug('Lugar '.$i)
            ]);
        }
    }

    private function formatos()
    {
        for ($i = 1; $i <= 30; $i++)
        {
            $votos = rand(30,150);
            Formato::create([
                'lugar_id' => rand(1,15),
                'mesa' => rand(1,8),
                'user_id' => rand(1,15),
                'sufragantes' => $votos,
                'urnas' => $votos,
                'incinerados' => 0,
            ]);
        }
    }

    private function candidatos()
    {
        for ($i=1; $i<=8; $i++)
        {
            Candidato::create([
                'numero' => '0'.$i,
                'nombre' => 'Nombre de Candidato '.$i,
                'slug' => Str::slug('Nombre de Candidato '.$i),
            ]);
        }
    }

    private function emularFormatos()
    {
        $candidatos = Candidato::all();
        $formatos = Formato::all();
        foreach ($formatos as $formato){
            foreach ($candidatos as $candidato){
                DB::table('candidato_formato')->insert([
                   'candidato_id' => $candidato->id,
                    'formato_id' => $formato->id,
                    'votos' => rand(20,110)
                ]);
            }
        }
    }
}
