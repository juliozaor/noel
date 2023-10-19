<?php

namespace App\Imports;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithCustomCsvSettings
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use Importable;
    
    public function model(array $row)
    {
        $profile = Profile::where('document', $row['documento'])->first();

        if (!$profile) {
            $date = Carbon::createFromFormat('d/m/Y', $row['fecha_nacimiento'])->format('Y-m-d');
            $experiencia = strtolower($row['experiencia_2022']);
       
            $user = User::create([
                'name' => $row['nombre'],
                'email' => $row['email'],
                'password' => bcrypt($row['documento'])
            ]);
    
            $user->assignRole('User');
    
            Profile::create([
                'user_id' => $user->id,
                'document' => $row['documento'],
                'cell' => $row['celular'],
                'address' => $row['direccion'],
                'neighborhood' => $row['barrio'],
                'birth' => $date,
                'eps' => $row['eps'],
                'reference' => $row['como_se_entero'],
                'experience2022' => ( $experiencia == 'si' || $experiencia == 'sí')?1:0
            ]);
        }

    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
        ];
    }
}
