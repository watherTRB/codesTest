<?php namespace App\Models;

use CodeIgniter\Model;

class ComplianceModel extends Model
{
    protected $powerShellPath = 'powershell';

    public function runPowerShell(string $script): string
    {
        $cmd = escapeshellcmd($this->powerShellPath) . ' -NoProfile -NonInteractive -Command "' . $script . '"';

        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($process)) {
            throw new \Exception('Could not start PowerShell process');
        }

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);
        if ($exitCode !== 0) {
            $stderr = trim($stderr) . " [Exit code: $exitCode]";
            return trim($stdout) . "\n---ERROR---\n" . trim($stderr);
        }

        return trim($stdout);
    }
}
