<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AutoBackup extends Command
{
    protected $signature = 'backup:auto {--keep=7 : عدد النسخ المحتفظ بها}';
    protected $description = 'نسخ احتياطي يومي لقاعدة بيانات SQLite + ملفات storage';

    public function handle(): int
    {
        $keep = (int) $this->option('keep');
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $backupDir = storage_path('backups');
        if (!File::exists($backupDir)) File::makeDirectory($backupDir, 0755, true);

        // SQLite backup
        $dbPath = database_path('database.sqlite');
        if (File::exists($dbPath)) {
            $dest = $backupDir . "/backup-{$date}.sqlite";
            File::copy($dbPath, $dest);
            $this->info("✓ SQLite backup: {$dest} (".round(File::size($dest)/1024)." KB)");
        } else {
            $this->warn("SQLite file not found: {$dbPath}");
        }

        // cleanup old backups (keep N latest)
        $files = collect(File::files($backupDir))
            ->filter(fn($f) => str_starts_with($f->getFilename(), 'backup-') && $f->getExtension() === 'sqlite')
            ->sortByDesc(fn($f) => $f->getMTime())
            ->values();

        if ($files->count() > $keep) {
            $toDelete = $files->slice($keep);
            foreach ($toDelete as $f) {
                File::delete($f->getPathname());
                $this->line("  حذف نسخة قديمة: {$f->getFilename()}");
            }
        }

        $this->info("تم النسخ الاحتياطي — محتفظ بآخر {$keep} نسخ في storage/backups/");
        return self::SUCCESS;
    }
}
