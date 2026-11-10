<?php

namespace App\Imports;

use App\Models\CreativeEconomyGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use OpenSpout\Reader\XLSX\Reader;
use Throwable;

class CreativeEconomyGroupImporter
{
    private const MAX_ROWS = 5000;

    private const REQUIRED_HEADERS = [
        'kelompok' => 'group_name',
        'label_oap_non_oap' => 'classification',
        'ketua_sumber' => 'leader_source',
        'subsektor' => 'subsector',
        'distrik' => 'district',
    ];

    public function import(string $filePath, int $year): int
    {
        $reader = new Reader;
        $worksheets = [];

        try {
            $reader->open($filePath);

            foreach ($reader->getSheetIterator() as $sheet) {
                $rows = [];

                foreach ($sheet->getRowIterator() as $row) {
                    if (count($rows) <= self::MAX_ROWS + 100) {
                        $rows[] = $row->toArray();
                    }
                }

                $worksheets[] = [
                    'name' => $sheet->getName(),
                    'rows' => $rows,
                ];
            }
        } catch (Throwable $exception) {
            throw ValidationException::withMessages([
                'dataFile' => 'File tidak dapat dibaca. Pastikan file merupakan Excel .xlsx dengan format template yang benar.',
            ]);
        } finally {
            $reader->close();
        }

        $records = [];
        foreach ($worksheets as $worksheet) {
            if ($worksheet['rows'] === []) {
                continue;
            }

            $header = $this->findHeader($worksheet['rows']);

            if ($header === null) {
                $this->fail("Sheet '{$worksheet['name']}' tidak memiliki header template yang sesuai.");
            }

            foreach (array_slice($worksheet['rows'], $header['row'] + 1) as $index => $cells) {
                $rowNumber = $header['row'] + $index + 2;
                $values = array_map(
                    fn (string $column): string => trim((string) ($cells[$header['map'][$column]] ?? '')),
                    array_keys(self::REQUIRED_HEADERS)
                );

                if (count(array_filter($values, fn (string $value): bool => $value !== '')) === 0) {
                    continue;
                }

                $record = [];
                foreach (self::REQUIRED_HEADERS as $column => $field) {
                    $record[$field] = trim((string) ($cells[$header['map'][$column]] ?? '')) ?: null;
                }

                if (blank($record['group_name'])) {
                    $this->fail("Sheet '{$worksheet['name']}', baris {$rowNumber}: kolom KELOMPOK wajib diisi.");
                }

                $record['classification'] = $this->normalizeClassification($record['classification'], $rowNumber);
                $record['year'] = $year;
                $this->validateFieldLength($record['group_name'], 255, 'KELOMPOK', $rowNumber);
                $this->validateFieldLength($record['leader_source'], 255, 'KETUA (SUMBER)', $rowNumber);
                $this->validateFieldLength($record['subsector'], 100, 'SUBSEKTOR', $rowNumber);
                $this->validateFieldLength($record['district'], 100, 'DISTRIK', $rowNumber);

                $records[] = $record;

                if (count($records) > self::MAX_ROWS) {
                    $this->fail('File hanya boleh berisi maksimal '.self::MAX_ROWS.' baris data.');
                }
            }
        }

        if ($records === []) {
            $this->fail('File tidak memiliki baris data yang dapat diimpor.');
        }

        $this->ensureUniqueGroupNames($records);

        DB::transaction(function () use ($records): void {
            foreach (array_chunk($records, 100) as $chunk) {
                CreativeEconomyGroup::query()->upsert(
                    $chunk,
                    ['year', 'group_name'],
                    ['classification', 'leader_source', 'subsector', 'district', 'updated_at']
                );
            }
        });

        return count($records);
    }

    private function findHeader(array $rows): ?array
    {
        foreach ($rows as $rowIndex => $cells) {
            $normalizedHeaders = [];

            foreach ($cells as $index => $cell) {
                $header = Str::of((string) $cell)
                    ->lower()
                    ->replaceMatches('/[^a-z0-9]+/', '_')
                    ->trim('_')
                    ->value();

                $normalizedHeaders[$header] = $index;
            }

            $headerMap = [];
            foreach (self::REQUIRED_HEADERS as $header => $field) {
                if (! array_key_exists($header, $normalizedHeaders)) {
                    continue 2;
                }

                $headerMap[$header] = $normalizedHeaders[$header];
            }

            return ['map' => $headerMap, 'row' => $rowIndex];
        }

        return null;
    }

    private function normalizeClassification(?string $classification, int $rowNumber): string
    {
        $normalized = Str::of((string) $classification)
            ->lower()
            ->replace([' ', '_'], '-')
            ->value();

        if ($normalized === 'oap' || Str::startsWith($normalized, 'oap-')) {
            return 'OAP';
        }

        if (in_array($normalized, ['non-oap', 'non-papua'], true)
            || Str::startsWith($normalized, 'non-oap-')
            || Str::startsWith($normalized, 'non-papua-')) {
            return 'Non-OAP';
        }

        $this->fail("Baris {$rowNumber}: label harus OAP atau Non-OAP.");
    }

    private function ensureUniqueGroupNames(array $records): void
    {
        $seen = [];

        foreach ($records as $record) {
            $key = Str::lower($record['group_name']);

            if (isset($seen[$key])) {
                $this->fail("Nama kelompok '{$record['group_name']}' muncul lebih dari sekali dalam file.");
            }

            $seen[$key] = true;
        }
    }

    private function validateFieldLength(?string $value, int $maximumLength, string $field, int $rowNumber): void
    {
        if ($value !== null && mb_strlen($value) > $maximumLength) {
            $this->fail("Baris {$rowNumber}: kolom {$field} maksimal {$maximumLength} karakter.");
        }
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['dataFile' => $message]);
    }
}
