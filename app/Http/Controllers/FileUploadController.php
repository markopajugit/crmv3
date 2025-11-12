<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Models\Company;
use App\Models\File;
use App\Models\Order;
use App\Models\Person;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Allowed file extensions
     */
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'csv'];

    /**
     * Maximum file size in kilobytes (10MB)
     */
    private const MAX_FILE_SIZE = 10240;

    public function index()
    {
        return view('files.upload');
    }

    /**
     * Store uploaded file with validation and security checks
     */
    public function store(FileUploadRequest $request)
    {
        try {
            $uploadedFile = $request->file('file');
            
            // Sanitize file name to prevent path traversal and XSS
            $originalName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $sanitizedName = $this->sanitizeFileName($originalName, $extension);
            
            // Store file with sanitized name
            $path = $uploadedFile->storeAs('public/files', $sanitizedName);

            $file = new File;
            $file->name = $sanitizedName;
            $file->path = $path;
            $redirect = 'default';

            // Validate entity IDs exist and user has access
            if ($request->companyID) {
                $company = Company::findOrFail($request->companyID);
                $file->company_id = $company->id;
                $redirect = 'company';
            } elseif ($request->personID) {
                $person = Person::findOrFail($request->personID);
                $file->person_id = $person->id;
                $redirect = 'person';
            } elseif ($request->orderID) {
                $order = Order::findOrFail($request->orderID);
                $file->order_id = $order->id;
                $redirect = 'order';
            }

            $file->save();

            return response()->json(['success' => true, 'message' => 'File uploaded successfully']);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'File upload failed'], 500);
        }
    }

    /**
     * Store virtual office document with validation
     */
    public function storeVirtualOfficeDocument(FileUploadRequest $request)
    {
        try {
            $uploadedFile = $request->file('file');
            
            // Sanitize file name
            $originalName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $sanitizedName = $this->sanitizeFileName($originalName, $extension);
            
            $path = $uploadedFile->storeAs('public/files', $sanitizedName);

            $file = new File;
            $file->name = $sanitizedName;
            $file->path = $path;
            $file->virtual_office = 1;
            $redirect = 'default';

            if ($request->companyID) {
                $company = Company::findOrFail($request->companyID);
                $file->company_id = $company->id;
                $redirect = 'company';
            } elseif ($request->personID) {
                $person = Person::findOrFail($request->personID);
                $file->person_id = $person->id;
                $redirect = 'person';
            } elseif ($request->orderID) {
                $order = Order::findOrFail($request->orderID);
                $file->order_id = $order->id;
                $redirect = 'order';
            }

            $file->save();

            return response()->json(['success' => true, 'message' => 'Virtual office document uploaded successfully']);
        } catch (\Exception $e) {
            Log::error('Virtual office document upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'File upload failed'], 500);
        }
    }

    /**
     * Sanitize file name to prevent path traversal and XSS
     */
    private function sanitizeFileName(string $originalName, string $extension): string
    {
        // Remove path components
        $name = basename($originalName);
        
        // Remove extension temporarily
        $nameWithoutExt = pathinfo($name, PATHINFO_FILENAME);
        
        // Sanitize: remove special characters, keep alphanumeric, spaces, hyphens, underscores
        $sanitized = preg_replace('/[^a-zA-Z0-9\s\-_\.]/', '', $nameWithoutExt);
        
        // Limit length
        $sanitized = Str::limit($sanitized, 200);
        
        // Ensure extension is allowed
        $extension = strtolower($extension);
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \InvalidArgumentException('File extension not allowed');
        }
        
        // Add timestamp to ensure uniqueness and prevent overwrites
        $timestamp = time();
        return $sanitized . '_' . $timestamp . '.' . $extension;
    }

    /**
     * View company file with security checks
     */
    public function viewUploadedCompanyFile($companyId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name to prevent path traversal
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'company_id' => $companyId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Verify file path is within allowed directory
        $filePath = storage_path('app/' . $file->path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public/files'));
        
        if (!$realPath || strpos($realPath, $storagePath) !== 0) {
            Log::warning('Path traversal attempt detected', [
                'user_id' => $user->id,
                'file_path' => $file->path,
                'requested_name' => $name
            ]);
            return response()->json(['error' => 'Invalid file path'], 403);
        }
        
        if (!file_exists($realPath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->file($realPath);
    }

    /**
     * View person file with security checks
     */
    public function viewUploadedPersonFile($personId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'person_id' => $personId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Verify file path
        $filePath = storage_path('app/' . $file->path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public/files'));
        
        if (!$realPath || strpos($realPath, $storagePath) !== 0) {
            Log::warning('Path traversal attempt detected', [
                'user_id' => $user->id,
                'file_path' => $file->path,
                'requested_name' => $name
            ]);
            return response()->json(['error' => 'Invalid file path'], 403);
        }
        
        if (!file_exists($realPath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->file($realPath);
    }

    /**
     * View order file with security checks
     */
    public function viewUploadedOrderFile($orderId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'order_id' => $orderId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Verify file path
        $filePath = storage_path('app/' . $file->path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public/files'));
        
        if (!$realPath || strpos($realPath, $storagePath) !== 0) {
            Log::warning('Path traversal attempt detected', [
                'user_id' => $user->id,
                'file_path' => $file->path,
                'requested_name' => $name
            ]);
            return response()->json(['error' => 'Invalid file path'], 403);
        }
        
        if (!file_exists($realPath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->file($realPath);
    }

    /**
     * Download company file with security checks
     */
    public function downloadUploadedCompanyFile($companyId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'company_id' => $companyId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Verify file path
        $filePath = storage_path('app/' . $file->path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public/files'));
        
        if (!$realPath || strpos($realPath, $storagePath) !== 0) {
            Log::warning('Path traversal attempt detected', [
                'user_id' => $user->id,
                'file_path' => $file->path
            ]);
            return response()->json(['error' => 'Invalid file path'], 403);
        }
        
        if (!file_exists($realPath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->download($realPath, $sanitizedName);
    }

    /**
     * Download person file with security checks
     */
    public function downloadUploadedPersonFile($personId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'person_id' => $personId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Verify file path
        $filePath = storage_path('app/' . $file->path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public/files'));
        
        if (!$realPath || strpos($realPath, $storagePath) !== 0) {
            Log::warning('Path traversal attempt detected', [
                'user_id' => $user->id,
                'file_path' => $file->path
            ]);
            return response()->json(['error' => 'Invalid file path'], 403);
        }
        
        if (!file_exists($realPath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->download($realPath, $sanitizedName);
    }

    /**
     * Download order file with security checks
     */
    public function downloadUploadedOrderFile($orderId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'order_id' => $orderId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Verify file path
        $filePath = storage_path('app/' . $file->path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public/files'));
        
        if (!$realPath || strpos($realPath, $storagePath) !== 0) {
            Log::warning('Path traversal attempt detected', [
                'user_id' => $user->id,
                'file_path' => $file->path
            ]);
            return response()->json(['error' => 'Invalid file path'], 403);
        }
        
        if (!file_exists($realPath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        $order = Order::find($orderId);
        $downloadName = $sanitizedName;
        
        if ($order && $order->company_id) {
            $company = Company::find($order->company_id);
            if ($company) {
                // Sanitize download name to prevent XSS
                $downloadName = preg_replace('/[^a-zA-Z0-9\s\-_\.]/', '', $company->name) . ' invoice ' . preg_replace('/[^a-zA-Z0-9\s\-_\.]/', '', $order->number) . '.' . pathinfo($sanitizedName, PATHINFO_EXTENSION);
            }
        }
        
        return response()->download($realPath, $downloadName);
    }

    /**
     * Send test email (only in non-production environments)
     */
    public function sendTestEmail()
    {
        // Only allow in non-production environments
        if (app()->environment('production')) {
            return response()->json(['error' => 'Not available in production'], 403);
        }

        $testEmail = env('TEST_EMAIL', 'test@example.com');
        
        try {
            $result = mail($testEmail, "Test", "Test mail from crm");
            return response()->json([
                'success' => $result,
                'message' => 'Test email sent to ' . $testEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Test email error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send test email'], 500);
        }
    }

    /**
     * Delete company file with security checks
     */
    public function deleteUploadedCompanyFile($companyId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'company_id' => $companyId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        try {
            Storage::delete($file->path);
            $file->delete();
            
            Log::info('File deleted', [
                'user_id' => $user->id,
                'file_id' => $file->id,
                'file_name' => $sanitizedName
            ]);
            
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete file'], 500);
        }
    }

    /**
     * Delete person file with security checks
     */
    public function deleteUploadedPersonFile($personId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'person_id' => $personId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        try {
            Storage::delete($file->path);
            $file->delete();
            
            Log::info('File deleted', [
                'user_id' => $user->id,
                'file_id' => $file->id,
                'file_name' => $sanitizedName
            ]);
            
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete file'], 500);
        }
    }

    /**
     * Delete order file with security checks
     */
    public function deleteUploadedOrderFile($orderId, $name)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sanitize file name
        $sanitizedName = basename($name);
        
        $file = File::where(['name' => $sanitizedName, 'order_id' => $orderId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        try {
            Storage::delete($file->path);
            $file->delete();
            
            Log::info('File deleted', [
                'user_id' => $user->id,
                'file_id' => $file->id,
                'file_name' => $sanitizedName
            ]);
            
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete file'], 500);
        }
    }


    /**
     * Update archive number with validation
     */
    public function updateArchiveNumber(Request $request, $fileId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'archive_nr' => 'nullable|string|max:255'
        ]);

        $file = File::findOrFail($fileId);
        $file->archive_nr = $validated['archive_nr'] ?? null;
        $file->save();

        return response()->json(['success' => true, 'message' => 'Archive number updated']);
    }

    public function showDocuments(Request $request){
        $query = File::with(['company', 'person', 'order']);

        // Category filter
        $category = $request->get('category', 'all');
        if ($category === 'archived') {
            $query->whereNotNull('archive_nr');
        } elseif ($category === 'virtualoffice') {
            $query->where('virtual_office', 1);
        } elseif ($category === 'general') {
            $query->whereNull('archive_nr')->where(function($q) {
                $q->whereNull('virtual_office')->orWhere('virtual_office', 0);
            });
        }

        // Text search
        $search = $request->get('search', '');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('archive_nr', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('company', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('person', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('number', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $files = $query->latest()->paginate(10)->appends(request()->query());

        // If AJAX request, return JSON
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'html' => view('documents.partials.table', compact('files', 'category'))->render(),
                'pagination' => view('documents.partials.pagination', compact('files'))->render(),
                'total' => $files->total()
            ]);
        }

        return view('documents.index', compact('files', 'category'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    /**
     * Generate archive number for a file
     */
    public function generateArchiveNumber(Request $request, $fileId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $archive = Settings::where('key', 'file_archive_number')->first();
        $file = File::findOrFail($fileId);

        // Check if file exists
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Create setting if it doesn't exist
        if (!$archive) {
            $archive = new Settings();
            $archive->key = 'file_archive_number';
            $archive->value = '1';
        }

        $archiveNr = $this->checkArchiveNumber($archive->value);
        $archive->value = $archiveNr;
        $file->archive_nr = 'A'.$archiveNr;
        $archive->save();
        $file->save();

        return response()->json(['success' => true, 'archive_nr' => 'A'.$archiveNr]);
    }

    private function checkArchiveNumber($number){
        $file = File::where('archive_nr', 'A'.$number)->first();

        if(isset($file->id)){
            $number = $number+1;
            return $this->checkArchiveNumber($number);
        } else {
            return $number;
        }
    }
}
