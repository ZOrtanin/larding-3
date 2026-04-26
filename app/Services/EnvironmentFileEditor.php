<?php

namespace App\Services;

class EnvironmentFileEditor
{
    public function update(array $values, ?string $path = null): void
    {
        $path ??= base_path('.env');

        $contents = file_exists($path)
            ? (string) file_get_contents($path)
            : '';

        foreach ($values as $key => $value) {
            $contents = $this->replaceOrAppend($contents, (string) $key, $this->normalizeValue($value));
        }

        file_put_contents($path, $contents);
    }

    private function replaceOrAppend(string $contents, string $key, string $value): string
    {
        $pattern = "/^".preg_quote($key, '/')."=.*/m";
        $line = $key.'='.$value;

        if (preg_match($pattern, $contents) === 1) {
            return (string) preg_replace($pattern, $line, $contents, 1);
        }

        return rtrim($contents).PHP_EOL.$line.PHP_EOL;
    }

    private function normalizeValue(?string $value): string
    {
        $value ??= '';

        if ($value === '') {
            return 'null';
        }

        if (preg_match('/\s|#|"|\'/', $value) === 1) {
            return '"'.addcslashes($value, "\\\"").'"';
        }

        return $value;
    }
}
