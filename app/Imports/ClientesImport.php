<?php

namespace App\Imports;

use App\Models\ClienteImportado;
use App\Models\Logincliente;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private int $importedCount = 0;

    public function __construct(
        private readonly Logincliente $logincliente,
        private readonly ?string $sourceFile = null
    ) {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $data = $row instanceof Collection ? $row : collect($row);

            $nombre = $this->value($data, ['nombre', 'name', 'cliente', 'razon_social', 'razon social']);
            $email = $this->value($data, ['email', 'correo', 'correo_electronico', 'mail']);
            $empresa = $this->value($data, ['empresa', 'compania', 'company']);
            $telefono = $this->value($data, ['telefono', 'phone', 'celular', 'whatsapp']);
            $producto = $this->value($data, ['producto', 'producto_interes', 'producto de interes', 'masterbatch']);
            $consulta = $this->value($data, ['consulta', 'inquietud', 'problema', 'observaciones', 'comentarios']);

            if (! $nombre && ! $email && ! $empresa && ! $telefono && ! $producto && ! $consulta) {
                continue;
            }

            ClienteImportado::create([
                'logincliente_id' => $this->logincliente->id,
                'nombre' => $nombre,
                'email' => $email,
                'empresa' => $empresa,
                'telefono' => $telefono,
                'producto' => $producto,
                'consulta' => $consulta,
                'raw_data' => $data->filter(fn ($value) => filled($value))->toArray(),
                'source_file' => $this->sourceFile,
                'imported_at' => now(),
            ]);

            $this->importedCount++;
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    private function value(Collection $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            $normalized = str_replace(' ', '_', mb_strtolower($key));

            if ($row->has($normalized) && filled($row->get($normalized))) {
                return trim((string) $row->get($normalized));
            }

            if ($row->has($key) && filled($row->get($key))) {
                return trim((string) $row->get($key));
            }
        }

        return null;
    }
}
