<?php

namespace App\Console\Commands;

use App\Models\ClienteAiChat;
use App\Models\ClienteAiRequest;
use App\Models\Logincliente;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PurgeInactiveClienteData extends Command
{
    protected $signature = 'clientes:purge-inactive-data
        {--days=30 : Días de inactividad antes de la eliminación definitiva}
        {--dry-run : Mostrar qué se eliminaría sin modificar datos}';

    protected $description = 'Elimina definitivamente chats y cuentas de clientes con inactividad vencida';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        if ($days < 1) {
            $this->error('La cantidad de días debe ser mayor que cero.');

            return self::INVALID;
        }

        $cutoff = now()->subDays($days);
        $expiredAccounts = $this->expiredAccountsQuery($cutoff);
        $staleChats = $this->staleChatsQuery($cutoff);

        if ($this->option('dry-run')) {
            $accountCount = (clone $expiredAccounts)->count();
            $chatCount = (clone $staleChats)
                ->whereNotIn('logincliente_id', (clone $expiredAccounts)->select('id'))
                ->count();

            $this->table(
                ['Tipo', 'Cantidad', 'Criterio'],
                [
                    ['Cuentas', $accountCount, "Deshabilitadas hace {$days} días o más"],
                    ['Chats', $chatCount, "Sin mensajes hace {$days} días o más (excluye cuentas anteriores)"],
                ]
            );
            $this->info('Vista previa completada. No se modificó ningún dato.');

            return self::SUCCESS;
        }

        $deletedAccounts = 0;
        $deletedChats = 0;
        $deletedAttachments = 0;

        $expiredAccounts->chunkById(100, function ($accounts) use (&$deletedAccounts, &$deletedAttachments): void {
            foreach ($accounts as $account) {
                $paths = $this->accountAttachmentPaths($account);

                DB::transaction(fn () => $account->delete());

                $deletedAccounts++;
                $deletedAttachments += $this->deleteAttachments($paths);
            }
        });

        $this->staleChatsQuery($cutoff)->chunkById(100, function ($chats) use (&$deletedChats, &$deletedAttachments): void {
            foreach ($chats as $chat) {
                $chat->load('messages:id,cliente_ai_chat_id,cliente_ai_request_id,attachment_path');

                $requestIds = $chat->messages
                    ->pluck('cliente_ai_request_id')
                    ->filter()
                    ->unique()
                    ->values();
                $paths = $chat->messages
                    ->pluck('attachment_path')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                DB::transaction(function () use ($chat, $requestIds): void {
                    $chat->delete();

                    if ($requestIds->isNotEmpty()) {
                        ClienteAiRequest::query()->whereIn('id', $requestIds)->delete();
                    }
                });

                $deletedChats++;
                $deletedAttachments += $this->deleteAttachments($paths);
            }
        });

        $summary = compact('days', 'deletedAccounts', 'deletedChats', 'deletedAttachments');

        Log::info('Depuración automática de clientes y chats completada.', $summary);
        $this->info(
            "Depuración completada: {$deletedAccounts} cuentas, {$deletedChats} chats "
            ."y {$deletedAttachments} adjuntos eliminados."
        );

        return self::SUCCESS;
    }

    private function expiredAccountsQuery($cutoff): Builder
    {
        return Logincliente::query()
            ->where('is_enabled', false)
            ->whereNotNull('inactive_since_at')
            ->where('inactive_since_at', '<=', $cutoff);
    }

    private function staleChatsQuery($cutoff): Builder
    {
        return ClienteAiChat::query()
            ->where(function (Builder $query) use ($cutoff): void {
                $query->where('last_message_at', '<=', $cutoff)
                    ->orWhere(function (Builder $withoutMessages) use ($cutoff): void {
                        $withoutMessages
                            ->whereNull('last_message_at')
                            ->where('created_at', '<=', $cutoff);
                    });
            });
    }

    private function accountAttachmentPaths(Logincliente $account): array
    {
        return $account->aiChats()
            ->with('messages:id,cliente_ai_chat_id,attachment_path')
            ->get()
            ->flatMap(fn (ClienteAiChat $chat) => $chat->messages->pluck('attachment_path'))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function deleteAttachments(array $paths): int
    {
        if ($paths === []) {
            return 0;
        }

        Storage::disk('public')->delete($paths);

        return count($paths);
    }
}
