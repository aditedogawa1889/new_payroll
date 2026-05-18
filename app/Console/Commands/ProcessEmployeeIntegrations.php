<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\EmployeeIntegration;
use App\Models\MasterIntegrationType;
use App\Models\TerminationEmployee;
use App\Models\PromotionEmployee;
use App\Models\DemotionEmployee;
use App\Models\MutationEmployee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class ProcessEmployeeIntegrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hris:process-integrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending employee integrations from HRIS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingIntegrations = EmployeeIntegration::where('is_processed', 0)->get();

        if ($pendingIntegrations->isEmpty()) {
            $this->info('No pending integrations to process.');
            return;
        }

        foreach ($pendingIntegrations as $integration) {
            $this->info("Processing integration ID: {$integration->id_integration}");

            DB::beginTransaction();
            try {
                $type = MasterIntegrationType::find($integration->id_integ_type);
                if (!$type) {
                    throw new Exception("Integration type ID {$integration->id_integ_type} not found.");
                }

                $this->processByType($type->description, $integration);

                $integration->is_processed = 1;
                $integration->updated_by = 'System Cron';
                $integration->save();

                DB::commit();
                $this->info("Success: Integration ID {$integration->id_integration} processed.");
            } catch (Exception $e) {
                DB::rollBack();
                $this->error("Error processing integration ID {$integration->id_integration}: " . $e->getMessage());
                // Optionally log to a file or database
            }
        }
    }

    private function processByType(string $description, EmployeeIntegration $data)
    {
        $description = strtolower($description);

        switch ($description) {
            case 'new employee':
                Employee::updateOrCreate(
                    ['emp_number' => $data->emp_number],
                    [
                        'employee_id' => $data->employee_id,
                        'join_date' => $data->join_date,
                        'location_current_year' => $data->location_from_name,
                        'location_future_year' => $data->location_to_name,
                        'job_title' => $data->job_title_name,
                        'effective_location_date' => $data->job_title_effective_date,
                        'effective_location_year' => $data->effective_year,
                        'created_by' => 'HRIS Integration',
                        'updated_by' => 'HRIS Integration',
                    ]
                );
                break;

            case 'update employee':
                $employee = Employee::findOrFail($data->emp_number);
                $employee->update([
                    'employee_id' => $data->employee_id,
                    'location_current_year' => $data->location_from_name,
                    'location_future_year' => $data->location_to_name,
                    'job_title' => $data->job_title_name,
                    'effective_location_date' => $data->job_title_effective_date,
                    'effective_location_year' => $data->effective_year,
                    'updated_by' => 'HRIS Integration',
                ]);
                break;

            case 'termination employee':
                $employee = Employee::findOrFail($data->emp_number);
                $employee->update([
                    'termination_date' => $data->termination_date,
                    'last_payroll_date' => $data->last_payroll_date,
                    'updated_by' => 'HRIS Integration'
                ]);

                TerminationEmployee::create([
                    'emp_number' => $data->emp_number,
                    'employee_id' => $data->employee_id,
                    'termination_date' => $data->termination_date ?? $data->job_title_effective_date,
                    'reason' => 'Terminated from HRIS',
                    'created_by' => 'HRIS Integration',
                ]);
                break;

            case 'promotion employee':
                $employee = Employee::findOrFail($data->emp_number);
                $oldTitle = $employee->job_title;
                
                $employee->update([
                    'job_title' => $data->job_title_future_name,
                    'effective_location_date' => $data->job_title_effective_date,
                    'updated_by' => 'HRIS Integration',
                ]);

                PromotionEmployee::create([
                    'emp_number' => $data->emp_number,
                    'employee_id' => $data->employee_id,
                    'previous_job_title_name' => $oldTitle,
                    'new_job_title_name' => $data->job_title_future_name,
                    'effective_date' => $data->job_title_effective_date,
                    'created_by' => 'HRIS Integration',
                ]);
                break;

            case 'demotion employee':
                $employee = Employee::findOrFail($data->emp_number);
                $oldTitle = $employee->job_title;

                $employee->update([
                    'job_title' => $data->job_title_future_name,
                    'effective_location_date' => $data->job_title_effective_date,
                    'updated_by' => 'HRIS Integration',
                ]);

                DemotionEmployee::create([
                    'emp_number' => $data->emp_number,
                    'employee_id' => $data->employee_id,
                    'previous_job_title_name' => $oldTitle,
                    'new_job_title_name' => $data->job_title_future_name,
                    'effective_date' => $data->job_title_effective_date,
                    'created_by' => 'HRIS Integration',
                ]);
                break;

            case 'mutation employee':
                $employee = Employee::findOrFail($data->emp_number);
                $oldLocation = $employee->location_current_year;

                $employee->update([
                    'location_current_year' => $data->location_to_name,
                    'effective_location_date' => $data->job_title_effective_date,
                    'updated_by' => 'HRIS Integration',
                ]);

                MutationEmployee::create([
                    'emp_number' => $data->emp_number,
                    'employee_id' => $data->employee_id,
                    'previous_location_name' => $oldLocation,
                    'new_location_name' => $data->location_to_name,
                    'effective_date' => $data->job_title_effective_date,
                    'created_by' => 'HRIS Integration',
                ]);
                break;

            default:
                throw new Exception("Unknown integration type description: {$description}");
        }
    }
}
