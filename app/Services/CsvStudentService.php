<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

class CsvStudentService
{
    public const CSV_PATH = 'data.csv';

    public function getCsvPath(): string
    {
        return base_path(self::CSV_PATH);
    }

    public function readAll(): Collection
    {
        $path = $this->getCsvPath();

        if (!File::exists($path)) {
            return collect();
        }

        $rows = File::lines($path)->filter()->values();
        $headers = $this->parseCsvLine($rows->first() ?? '');

        return $rows->slice(1)->map(function ($line) use ($headers) {
            return collect($this->parseCsvLine($line))->combine($headers)->toArray();
        });
    }

    public function append(array $row): bool
    {
        $path = $this->getCsvPath();

        if (!File::exists($path)) {
            throw new \RuntimeException("CSV file not found: {$path}");
        }

        $headers = $this->getHeaders();
        $ordered = array_map(fn ($header) => $row[$header] ?? '', $headers);

        $line = $this->escapeCsvLine($ordered);
        return File::append($path, PHP_EOL . $line);
    }

    public function getHeaders(): array
    {
        $path = $this->getCsvPath();

        if (!File::exists($path)) {
            return [];
        }

        $firstLine = File::lines($path)->first();
        return $this->parseCsvLine($firstLine ?: '');
    }

    private function parseCsvLine(string $line): array
    {
        $handle = fopen('php://memory', 'rw');
        fwrite($handle, $line);
        rewind($handle);
        $data = fgetcsv($handle);
        fclose($handle);

        return $data ?: [];
    }

    private function escapeCsvLine(array $row): string
    {
        $handle = fopen('php://memory', 'rw');
        fputcsv($handle, $row);
        rewind($handle);
        $csvLine = stream_get_contents($handle);
        fclose($handle);

        return rtrim($csvLine, "\n");
    }
}
