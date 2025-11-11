<?php

namespace App\Console\Commands;

use App\Models\Kyc;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckKycExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kyc:check-expirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for KYCs expiring within a month and send email notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get current date and date one month from now
        $now = Carbon::now();
        $oneMonthFromNow = $now->copy()->addMonth();

        // Get KYCs that need notification at specific intervals
        $expiringKycs = Kyc::with(['responsibleUser', 'kycable'])
            ->whereNotNull('end_date')
            ->whereNotNull('responsible_user_id')
            ->get()
            ->filter(function ($kyc) use ($now) {
                try {
                    // Parse the end_date string to Carbon instance
                    $endDate = Carbon::parse($kyc->end_date);
                    
                    // Only notify at specific intervals: 30, 14, 7, 3, 1 days before expiry
                    $daysUntilExpiry = $now->diffInDays($endDate, false);
                    
                    // Check if today is exactly one of our notification days
                    $notificationDays = [30, 14, 7, 3, 1];
                    return in_array($daysUntilExpiry, $notificationDays);
                    
                } catch (\Exception $e) {
                    // Skip if date parsing fails
                    $this->warn("Could not parse end_date for KYC ID {$kyc->id}: {$kyc->end_date}");
                    return false;
                }
            });

        $notificationCount = 0;

        foreach ($expiringKycs as $kyc) {
            if (!$kyc->responsibleUser || !$kyc->responsibleUser->email) {
                $this->warn("No responsible user or email found for KYC ID {$kyc->id}");
                continue;
            }

            try {
                $endDate = Carbon::parse($kyc->end_date);
                $daysUntilExpiry = $now->diffInDays($endDate);
                
                $responsibleUserEmail = $kyc->responsibleUser->email;
                $subject = "KYC Expiring in {$daysUntilExpiry} days";

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= 'From: CRM <crm@crm.wisorgroup.com>' . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                // Get entity name for the email
                $entityName = 'Unknown Entity';
                if ($kyc->kycable) {
                    $entityName = $kyc->kycable->name ?? 'Unknown Entity';
                }

                $message = '<p><strong>KYC Expiration Notice</strong></p>';
                $message .= '<p>A KYC record under your responsibility is expiring soon:</p>';
                $message .= '<p><strong>Entity:</strong> ' . htmlspecialchars($entityName) . '</p>';
                $message .= '<p><strong>KYC ID:</strong> ' . $kyc->id . '</p>';
                $message .= '<p><strong>End Date:</strong> ' . $endDate->format('Y-m-d') . '</p>';
                $message .= '<p><strong>Days until expiry:</strong> ' . $daysUntilExpiry . '</p>';
                $message .= '<p><strong>Risk Level:</strong> ' . ($kyc->risk ?? 'Not specified') . '</p>';
                
                if ($kyc->comments) {
                    $message .= '<p><strong>Comments:</strong> ' . htmlspecialchars($kyc->comments) . '</p>';
                }

                if (mail($responsibleUserEmail, $subject, $message, $headers)) {
                    $notificationCount++;
                    $this->info("Notification sent to {$responsibleUserEmail} for KYC ID {$kyc->id}");
                } else {
                    $this->error("Failed to send notification to {$responsibleUserEmail} for KYC ID {$kyc->id}");
                }

            } catch (\Exception $e) {
                $this->error("Error processing KYC ID {$kyc->id}: " . $e->getMessage());
            }
        }

        $this->info("KYC expiration check completed. {$notificationCount} notifications sent.");
        return 0;
    }
} 