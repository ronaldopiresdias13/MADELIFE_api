<?php

namespace App\Jobs;

use App\Models\Acaomedicamento;
use App\Models\Horariomedicamento;
use App\Models\TranscricaoProduto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OcorrenciasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $transcricao = TranscricaoProduto::with(['produto','horariomedicamentos','acoesmedicamentos'])->get();

        // $horarios = Horariomedicamento::where('horario','')
        
        // HorarioHorariomedicamento::where('horario','>','23:50')->where('horario','<','00:10')->get();

        
    }
}
