<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ComplianceController extends ResourceController
{
    protected $modelName = 'App\\Models\\ComplianceModel';
    protected $format    = 'json';

    public function __construct()
    {
        helper(['filesystem']);
    }

    public function connect()
    {
        try {
            $output = $this->model->runPowerShell('Connect-IPPSSession');
            return $this->respond(['success' => true, 'output' => $output]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function createSearch()
    {
        $payload = $this->request->getJSON(true);
        $name    = esc($payload['name']);
        $query   = str_replace("'", "''", esc($payload['query']));

        $script = "New-ComplianceSearch -Name '$name' -ExchangeLocation All -ContentMatchQuery '$query'";
        try {
            $output = $this->model->runPowerShell($script);
            return $this->respond(['success' => true, 'output' => $output]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function startSearch()
    {
        $payload = $this->request->getJSON(true);
        $name    = esc($payload['name']);

        $script = "Start-ComplianceSearch -Identity '$name'";
        try {
            $output = $this->model->runPowerShell($script);
            return $this->respond(['success' => true, 'output' => $output]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getResults($name = null)
    {
        if (!$name) {
            return $this->failValidationError('Missing search name');
        }
        $safeName = esc($name);
        $script   = "Get-ComplianceSearch -Identity '$safeName' | Format-List | Out-String";
        try {
            $output = $this->model->runPowerShell($script);
            return $this->respond(['success' => true, 'output' => $output]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function purgeEmails()
    {
        $payload = $this->request->getJSON(true);
        $name    = esc($payload['name']);
        $script  = "New-ComplianceSearchAction -SearchName '$name' -Purge -PurgeType SoftDelete";
        try {
            $output = $this->model->runPowerShell($script);
            return $this->respond(['success' => true, 'output' => $output]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function checkStatus($purgeIdentity = null)
    {
        if (!$purgeIdentity) {
            return $this->failValidationError('Missing purge identity');
        }
        $safeName = esc($purgeIdentity);
        $script   = "Get-ComplianceSearchAction -Identity '$safeName' | Out-String";
        try {
            $output = $this->model->runPowerShell($script);
            return $this->respond(['success' => true, 'output' => $output]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
