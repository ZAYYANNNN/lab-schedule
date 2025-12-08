<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lab;
use App\Models\Prodi;

class UpdateLabProdiId extends Command
{
    protected $signature = 'app:update-lab-prodi-id';
    protected $description = 'Update prodi_id on labs based on prodi name and vice versa';

    public function handle()
    {
        $this->info('Syncing prodi_id on labs...');

        $prodis = Prodi::all();
        $prodiByName = $prodis->pluck('id', 'name');
        $prodiById = $prodis->pluck('name', 'id');
        
        $this->info('Found prodis: ' . $prodiByName->toJson());

        // Sync prodi_id from prodi string
        $labsWithoutId = Lab::whereNull('prodi_id')->orWhere('prodi_id', 0)->get();
        $this->info('Labs without prodi_id: ' . $labsWithoutId->count());

        foreach ($labsWithoutId as $lab) {
            $prodiId = null;
            foreach ($prodiByName as $name => $id) {
                if (stripos($lab->prodi, $name) !== false || stripos($name, $lab->prodi) !== false) {
                    $prodiId = $id;
                    break;
                }
            }

            if ($prodiId) {
                $lab->prodi_id = $prodiId;
                $lab->save();
                $this->info("Updated prodi_id: {$lab->name} -> prodi_id: {$prodiId}");
            } else {
                $this->warn("No match for: {$lab->name} (prodi: {$lab->prodi})");
            }
        }

        // Sync prodi string from prodi_id (for labs that have prodi_id but empty prodi string)
        $labsWithIdButNoString = Lab::whereNotNull('prodi_id')
            ->where('prodi_id', '>', 0)
            ->where(function($q) {
                $q->whereNull('prodi')->orWhere('prodi', '');
            })
            ->get();
        $this->info('Labs with prodi_id but no prodi string: ' . $labsWithIdButNoString->count());

        foreach ($labsWithIdButNoString as $lab) {
            if (isset($prodiById[$lab->prodi_id])) {
                $lab->prodi = $prodiById[$lab->prodi_id];
                $lab->save();
                $this->info("Updated prodi string: {$lab->name} -> prodi: {$lab->prodi}");
            }
        }

        // Also update labs that have prodi_id but show it for all labs
        $this->info("\n--- Current Lab Status ---");
        Lab::all()->each(function($lab) use ($prodiById) {
            $prodiName = $prodiById[$lab->prodi_id] ?? 'NO PRODI';
            $this->line("{$lab->name} | prodi={$lab->prodi} | prodi_id={$lab->prodi_id} | prodi_relation={$prodiName}");
        });

        $this->info('Done!');
    }
}
