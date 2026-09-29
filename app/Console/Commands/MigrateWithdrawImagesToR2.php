<?php

namespace App\Console\Commands;

use App\Helpers\ImageHelper;
use App\Models\WithdrawMethod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateWithdrawImagesToR2 extends Command
{
    protected $signature = 'withdraw:migrate-images-to-r2';

    protected $description = 'Upload existing withdrawal method images to Cloudflare R2 and store their R2 URLs';

    public function handle(): int
    {
        $methods = WithdrawMethod::whereNotNull('image')
            ->where('image', '!=', '')
            ->get();

        if ($methods->isEmpty()) {
            $this->info('No withdrawal method images found.');
            return self::SUCCESS;
        }

        $uploaded = 0;
        $skipped  = 0;
        $failed   = 0;

        foreach ($methods as $method) {
            if (str_starts_with($method->image, 'http')) {
                $this->line("Skip #{$method->id} [{$method->name}] - already an R2/remote URL");
                $skipped++;
                continue;
            }

            $localPath = 'assets/images/withdraw_method/' . $method->image;

            if (!file_exists(public_path($localPath))) {
                $this->warn("Missing local file for #{$method->id} [{$method->name}]: {$localPath}");
                $failed++;
                continue;
            }

            try {
                $key = 'withdraw_methods/' . $method->image;

                Storage::disk('r2')->put(
                    $key,
                    file_get_contents(public_path($localPath)),
                    ['visibility' => 'public']
                );

                $url = Storage::disk('r2')->url($key);

                $method->image = $url;
                $method->save();

                $this->info("Migrated #{$method->id} [{$method->name}] -> {$url}");
                $uploaded++;
            } catch (\Throwable $e) {
                $this->error("Failed #{$method->id} [{$method->name}]: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done. Migrated: {$uploaded}, Skipped: {$skipped}, Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
