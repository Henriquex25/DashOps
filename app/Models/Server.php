<?php

namespace App\Models;

use App\Models\Scopes\OwnerServerScope;
use App\Service\SSH\SSH;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy(OwnerServerScope::class)]
class Server extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'ip',
        'ip_hash',
        'port',
        'username',
        'passphrase',
        'key_file_name',
    ];

    protected $casts = [
        'ip'         => 'encrypted',
        'port'       => 'encrypted',
        'username'   => 'encrypted',
        'passphrase' => 'encrypted',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getResolvedProjectName(?string $projectName = null): string
    {
        $projectName = $projectName ?? $this->project->name;

        return str($projectName)
            ->kebab()
            ->trim()
            ->toString();
    }

    public function getKeyPath(?string $fileName = null, ?string $projectName = null): string
    {
        $projectName = $this->getResolvedProjectName($projectName);
        $path = storage_path("app/private/ssh_keys/{$projectName}/");

        if ($fileName) {
            $fileName = str_starts_with('/', $fileName) ? substr($fileName, 1) : $fileName;

            return $path . $fileName;
        }

        return $path;
    }

    public static function resolveIpHashHmacKey(string $ip): string
    {
        $key = auth()->user()->selected_project_id . '_' . config('app.hmac_secret');

        return hash_hmac('sha256', $ip, $key);
    }

    public function ssh(): SSH
    {

    }
}
