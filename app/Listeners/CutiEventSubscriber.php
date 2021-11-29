<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;

use App\Events\CutiSubmitEvent;
use App\Events\CutiPrintEvent;
use Throwable;

class CutiEventSubscriber
{

    public function handleCutiSubmit($event)
    {
        error_log('Cuti is submited by :'.$event->nip." on ".$event->no_cuti);
    }

    public function handleCutiPrint($event)
    {
        try
        {
            $nip = $event->nip;
            $no_cuti = $event->no_cuti;

            $check = (array) DB::table('data_pegawai')->where('nip','=',$nip)->first();

            if($check['golongan'] === 'PJLP')
            {
                $asigment = DB::table('asigment_pjlp');
                $daftar = DB::table('daftar_cuti_pjlp');
                $tahunan = DB::table('cuti_tahunan_pjlp');
            }
            else
            {
                $asigment = DB::table('asigment_asn');
                $daftar = DB::table('daftar_cuti_asn');
                $tahunan = DB::table('cuti_tahunan_asn');
            }


            $status = $asigment->select('selesai')->where('no_cuti','=',$no_cuti)->value('selesai');
            
            if($status == 0)
            {
                $t = $daftar->where('id','=',$no_cuti)->first();
                $totalHari = $t->total_cuti;
                error_log('total hari ; '.$totalHari);
                $item = $tahunan->where('nip','=',$nip)->first();
    
                $sisa = $item->sisa;
                error_log('sisa : '.$sisa);
                $sisa = $sisa - $totalHari;
    
                $terpakai = $item->terpakai;
                $terpakai = $terpakai + $totalHari;
    
                $i = $tahunan->where('nip','=',$nip);

                $i->update(array(
                        'sisa' => $sisa,
                        'terpakai' => $terpakai
                    ));
    
                if($check !== 'PJLP')
                {

                    /**
                     * NOTE : 
                     * Jika user tidak cuti / sisa cuti <= 6, maka tahun depan N1 dan N2 = 6
                     * Jika user cuti melebihi 6 hari, maka tahun depan N1 dan N2 = 0
                     * 
                     * Code dibawah mengasumsikan penyesuaian nilai N1 dan N2 = 6, alias tidak cuti 2 tahun.
                     * N2 diprioritaskan utk dikurang terlebih dahulu
                     */


                    $n1 = $item->n1;
                    $n2 = $item->n2;

                    $sisa = $sisa + $n1;
                    $n1 = $n2;
                    $n2 = 0;

                    if($sisa < 12 && $n1 > 0)
                    {
                        $sisa += $n1;

                        if($sisa > 12)
                        $n1 += ($sisa - 12);
                        else
                        $n1 = 0;
                    }

                    $i->update(array(
                        'sisa' => $sisa,
                        'n1' => $n1,
                        'n2' => $n2
                    ));
                }
    
                $asigment->where('no_cuti','=',$no_cuti)->update(array('selesai' => 1));
                
            }
            else
                error_log('kelewat');
        }
        catch(Throwable $e)
        {
            error_log('error handle cuti print : '.$e);
        }
    }

    /**
     * Subscriber, mendengarkan banyak event sekaligus
     * 
     * @param \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(CutiSubmitEvent::class,
        [CutiEventSubscriber::class,'handleCutiSubmit']);
        
        $events->listen(CutiPrintEvent::class,
        [CutiEventSubscriber::class,'handleCutiPrint']);
    }

    

}

?>