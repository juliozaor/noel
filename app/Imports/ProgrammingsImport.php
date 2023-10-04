<?php

namespace App\Imports;

use App\Models\Programming;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgrammingsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $date = Carbon::createFromFormat('d/m/Y', $row['fecha'])->format('Y-m-d');
        return new Programming([
                'initial_date' => $date,
                'initial_time' => $row['hora'],
                'quota' => $row['cupos'],
                'quota_available' => $row['cupos'],
                'event_id' => 1,
           
        ]);
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
