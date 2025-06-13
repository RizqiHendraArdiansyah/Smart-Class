<?php

namespace App\Exports;

use App\Models\Pertanyaan\PertanyaanModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use Illuminate\Support\Collection;

class SoalExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithProperties
{
    protected $questions;
    protected $quiz;

    public function __construct()
    {
        // Ambil semua soal dengan relasi kuis dan modul
        $this->questions = PertanyaanModel::with(['kuis.modul.kelas'])->get();

        // Kelompokkan soal berdasarkan kuis
        $this->questions = $this->questions->groupBy('quiz_id');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->questions->first() ?? collect();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'kelas',
            'modul',
            'kuis',
            'pertanyaan',
            'tipe',
            'pilihan_jawaban',
            'jawaban_benar',
            'tingkat_kesulitan',
            'poin'
        ];
    }

    /**
    * @param mixed $row
    * @return array
    */
    public function map($row): array
    {
        return [
            $row->kuis->modul->kelas->name ?? '-',
            $row->kuis->modul->title ?? '-',
            $row->kuis->title ?? '-',
            $row->text,
            $row->type,
            json_encode($row->options, JSON_UNESCAPED_UNICODE),
            $row->correct_answer,
            $row->difficulty_level,
            $row->points
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $firstQuestion = $this->questions->first()->first();
        return 'Soal ' . ($firstQuestion->kuis->title ?? 'Kuis');
    }

    /**
     * @return array
     */
    public function properties(): array
    {
        $firstQuestion = $this->questions->first()->first();
        return [
            'creator' => 'Smart Class',
            'lastModifiedBy' => 'Smart Class',
            'title' => 'Export Soal',
            'description' => 'Export soal untuk ' . ($firstQuestion->kuis->title ?? 'Kuis'),
            'subject' => 'Soal',
            'keywords' => 'soal,export,kuis',
            'category' => 'Soal',
        ];
    }
}
