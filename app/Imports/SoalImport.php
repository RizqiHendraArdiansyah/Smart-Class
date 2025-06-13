<?php

namespace App\Imports;

use App\Models\Pertanyaan\PertanyaanModel;
use App\Models\Kuis\KuisModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SoalImport implements ToCollection, WithHeadingRow
{
    protected $quiz;

    public function __construct()
    {
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Validasi konteks soal dan cari kuis
        $firstRow = $collection->first();
        if (!$firstRow) {
            throw new \Exception('File Excel kosong');
        }

        $this->findAndValidateQuiz($firstRow);

        DB::beginTransaction();
        try {
            $collection->each(function ($row) {
                $validator = Validator::make($row->toArray(), [
                    'kelas' => 'required|string',
                    'modul' => 'required|string',
                    'kuis' => 'required|string',
                    'pertanyaan' => 'required|string',
                    'tipe' => 'required|string',
                    'pilihan_jawaban' => 'required|string',
                    'jawaban_benar' => 'required',
                    'tingkat_kesulitan' => 'required|string',
                    'poin' => 'required|integer'
                ]);

                if ($validator->fails()) {
                    throw new \Exception('Data tidak valid: ' . $validator->errors()->first());
                }

                try {
                    $options = json_decode($row['pilihan_jawaban'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Format pilihan jawaban tidak valid');
                    }
                } catch (\Exception $e) {
                    throw new \Exception('Format pilihan jawaban tidak valid');
                }

                $data = [
                    'quiz_id' => $this->quiz->id,
                    'text' => $row['pertanyaan'],
                    'type' => $row['tipe'],
                    'options' => $options,
                    'correct_answer' => $row['jawaban_benar'],
                    'difficulty_level' => $row['tingkat_kesulitan'],
                    'points' => $row['poin']
                ];

                $existing = PertanyaanModel::where('quiz_id', $this->quiz->id)
                    ->where('text', $row['pertanyaan'])
                    ->first();

                if ($existing) {
                    $existing->update($data);
                } else {
                    PertanyaanModel::create($data);
                }
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mencari dan validasi kuis berdasarkan data Excel
     *
     * @param Collection $row
     * @throws \Exception
     */
    protected function findAndValidateQuiz($row)
    {
        // Cari kuis berdasarkan nama kelas, modul, dan kuis
        $quiz = KuisModel::whereHas('modul', function ($query) use ($row) {
            $query->whereHas('kelas', function ($q) use ($row) {
                $q->where('name', $row['kelas']);
            })->where('title', $row['modul']);
        })->where('title', $row['kuis'])->first();

        if (!$quiz) {
            throw new \Exception(
                "Kuis tidak ditemukan dengan detail:\n" .
                "Kelas: {$row['kelas']}\n" .
                "Modul: {$row['modul']}\n" .
                "Kuis: {$row['kuis']}"
            );
        }

        $this->quiz = $quiz;
    }
}
