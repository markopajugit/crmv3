<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Kyc;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function lastUpdatedOrders(){
        $notRecentlyUpdatedOrders = Order::where('updated_at', '<', Carbon::now()->subDays(7))->get();

        foreach($notRecentlyUpdatedOrders as $order){
            if($order->status == 'Not Active' && $order->stapayment_status == 'Not Paid'){
                $responsibleUserEmail = $order->responsible_user->email;

                $subject = 'Order not recently updated';

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= 'From: CRM <crm@crm.wisorgroup.com>' . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $message = '<p><strong>Order id:'.$order->id.'</strong> not updated for more than 7 days.</p><p>View Order here: <a href="https://crm.wisorgroup.com/orders/'.$order->id.'">'.$order->name.'</a></p>';

                //echo $message;
                //$responsibleUserEmail = 'info@hardcoded.ee';

                if(mail($responsibleUserEmail, $subject, $message, $headers)){
                    $order->notification_sent = true;
                    $order->save();
                }

                echo "FINISHED";
            }
        }
    }

    public function checkKycExpirations(){
        // Get current date and date one month from now
        $now = Carbon::now();
        $oneMonthFromNow = $now->copy()->addMonth();

        echo "<h2>KYC Expiration Debug Information</h2>";
        echo "<p><strong>Current Date:</strong> " . $now->format('Y-m-d H:i:s') . "</p>";
        echo "<p><strong>One Month From Now:</strong> " . $oneMonthFromNow->format('Y-m-d H:i:s') . "</p><br>";

        // Get ALL KYCs first for debugging
        $allKycs = Kyc::with(['responsibleUser', 'kycable'])->get();
        echo "<h3>All KYCs in Database (" . $allKycs->count() . " total):</h3>";

        foreach ($allKycs as $kyc) {
            echo "<div style='border: 1px solid #ccc; margin: 5px; padding: 10px;'>";
            echo "<strong>KYC ID:</strong> " . $kyc->id . "<br>";
            echo "<strong>End Date:</strong> " . ($kyc->end_date ?? 'NULL') . "<br>";
            echo "<strong>Responsible User ID:</strong> " . ($kyc->responsible_user_id ?? 'NULL') . "<br>";
            echo "<strong>Responsible User Email:</strong> " . ($kyc->responsibleUser->email ?? 'NULL') . "<br>";
            echo "<strong>Entity:</strong> " . ($kyc->kycable->name ?? 'NULL') . "<br>";
            echo "<strong>Risk:</strong> " . ($kyc->risk ?? 'NULL') . "<br>";

            if ($kyc->end_date) {
                try {
                    $endDate = Carbon::parse($kyc->end_date);
                    echo "<strong>Parsed End Date:</strong> " . $endDate->format('Y-m-d') . "<br>";
                    echo "<strong>Days from now:</strong> " . $now->diffInDays($endDate, false) . "<br>";
                                         $daysUntilExpiry = $now->diffInDays($endDate, false);
                     $notificationDays = [30];
                     $shouldNotify = in_array($daysUntilExpiry, $notificationDays);
                     echo "<strong>Should notify today:</strong> " . ($shouldNotify ? 'YES' : 'NO') . "<br>";
                } catch (\Exception $e) {
                    echo "<strong>Date Parse Error:</strong> " . $e->getMessage() . "<br>";
                }
            }
            echo "</div>";
        }

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
                    $notificationDays = [30];
                    return in_array($daysUntilExpiry, $notificationDays);

                } catch (\Exception $e) {
                    // Skip if date parsing fails
                    return false;
                }
            });

        echo "<br><h3>KYCs Requiring Notification Today (" . $expiringKycs->count() . " total):</h3>";
        echo "<p><em>Only notifies at: 30 days before expiry</em></p>";

        foreach ($expiringKycs as $kyc) {
            echo "<div style='border: 2px solid #ff0000; margin: 5px; padding: 10px; background-color: #ffe6e6;'>";
            echo "<strong>KYC ID:</strong> " . $kyc->id . "<br>";
            echo "<strong>End Date:</strong> " . $kyc->end_date . "<br>";
            echo "<strong>Responsible User:</strong> " . ($kyc->responsibleUser->email ?? 'NULL') . "<br>";
            echo "<strong>Entity:</strong> " . ($kyc->kycable->name ?? 'NULL') . "<br>";
            echo "</div>";
        }

        $notificationCount = 0;

        foreach ($expiringKycs as $kyc) {
            if (!$kyc->responsibleUser || !$kyc->responsibleUser->email) {
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
                }

            } catch (\Exception $e) {
                // Skip this KYC if there's an error
                continue;
            }
        }

        echo "KYC expiration check completed. {$notificationCount} notifications sent.";
    }
}
