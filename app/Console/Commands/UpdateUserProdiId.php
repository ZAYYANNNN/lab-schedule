<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Prodi;

class UpdateUserProdiId extends Command
{
    protected $signature = 'app:update-user-prodi-id';
    protected $description = 'Check and update prodi_id on users';

    public function handle()
    {
        $this->info('Checking user prodi_id status...');

        $prodis = Prodi::all();
        $prodiByName = $prodis->pluck('id', 'name');
        $prodiById = $prodis->pluck('name', 'id');
        
        $this->info('Found prodis: ' . $prodiByName->toJson());

        $this->info("\n--- Current User Status ---");
        User::where('role', 'admin')->get()->each(function($user) use ($prodiById) {
            $prodiName = $user->prodi_id ? ($prodiById[$user->prodi_id] ?? 'INVALID ID') : 'NO PRODI_ID';
            $this->line("{$user->name} | email={$user->email} | prodi_id={$user->prodi_id} | prodi_relation={$prodiName}");
        });

        $this->info('Done!');
    }
}
