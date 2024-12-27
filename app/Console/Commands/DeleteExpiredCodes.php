<?php

namespace App\Console\Commands;

use App\Models\RegistroJuez;
use Illuminate\Console\Command;

use Carbon\Carbon;

class DeleteExpiredCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrojueces:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar registros de jueces que hayan expirado';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Obtenemos los registros que hayan expirado
        $expiredRecords = RegistroJuez::whereNotNull('expiracion_date')
            ->where('expiracion_date', '<', Carbon::now())
            ->get();

        if ($expiredRecords->isEmpty()) {
            $this->info('No hay registros expirados para eliminar.');
            return;
        }

        // Eliminamos los registros expirados
        foreach ($expiredRecords as $record) {
            $record->delete();
        }

        $this->info('Registros expirados eliminados con éxito.');
    }
}
