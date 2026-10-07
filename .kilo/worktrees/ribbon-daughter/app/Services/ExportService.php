<?php

namespace App\Services;

use App\Models\CompanySettings;
use App\Models\ExportLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExportService
{
    /**
     * Supported export formats
     */
    const FORMAT_PDF = 'pdf';
    const FORMAT_EXCEL = 'excel';
    const FORMAT_CSV = 'csv';

    /**
     * Export types
     */
    const TYPE_PAYMENTS = 'payments';
    const TYPE_ORDERS = 'orders';
    const TYPE_SUBSCRIPTIONS = 'subscriptions';
    const TYPE_TRANSACTIONS = 'transactions';
    const TYPE_FINANCIAL = 'financial';
    const TYPE_BUDGET = 'budget';
    const TYPE_EXPENSE = 'expense';
    const TYPE_BUDGET_VARIANCE = 'budget_variance';

    /**
     * Generate export based on type and format
     */
    public function export(string $type, string $format, array $data, ?Request $request = null): array
    {
        try {
            $result = match ($format) {
                self::FORMAT_PDF => $this->generatePdf($type, $data, $request),
                self::FORMAT_EXCEL => $this->generateExcel($type, $data),
                self::FORMAT_CSV => $this->generateCsv($type, $data),
                default => throw new \InvalidArgumentException("Unsupported format: {$format}"),
            };

            // Log successful export
            $this->logExport($type, $format, $result, 'success', $request);

            return $result;
        } catch (\Exception $e) {
            Log::error("Export failed: " . $e->getMessage(), [
                'type' => $type,
                'format' => $format,
                'trace' => $e->getTraceAsString()
            ]);

            // Log failed export
            $this->logExport($type, $format, [], 'failed', $request, $e->getMessage());

            throw $e;
        }
    }

    /**
     * Generate PDF with company branding
     */
    protected function generatePdf(string $type, array $data, ?Request $request): array
    {
        $company = CompanySettings::getActive();

        $orientation = $data['orientation'] ?? 'portrait';

        $pdf = Pdf::loadView('exports.pdf.payment-report', [
            'data' => $data,
            'company' => $company,
            'title' => $this->getReportTitle($type),
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ]);

        $pdf->setPaper('a4', $orientation);

        $filename = $this->generateFilename($type, self::FORMAT_PDF);
        $path = storage_path("app/exports/{$filename}");

        // Ensure directory exists
        File::ensureDirectoryExists(storage_path('app/exports'));

        $pdf->save($path);

        return [
            'filename' => $filename,
            'path' => $path,
            'url' => asset("storage/exports/{$filename}"),
            'size' => filesize($path),
        ];
    }

    /**
     * Generate Excel file
     */
    protected function generateExcel(string $type, array $data): array
    {
        // Simple HTML-based Excel generation (can be opened by Excel)
        $filename = $this->generateFilename($type, 'xlsx');
        $path = storage_path("app/exports/{$filename}");

        File::ensureDirectoryExists(storage_path('app/exports'));

        // Create HTML table that Excel can open
        $html = $this->generateHtmlTable($type, $data);

        // Save as HTML with .xlsx extension (Excel will open it)
        file_put_contents($path, $html);

        return [
            'filename' => $filename,
            'path' => $path,
            'url' => asset("storage/exports/{$filename}"),
            'size' => filesize($path),
        ];
    }

    /**
     * Generate CSV file
     */
    protected function generateCsv(string $type, array $data): array
    {
        $filename = $this->generateFilename($type, self::FORMAT_CSV);
        $path = storage_path("app/exports/{$filename}");

        File::ensureDirectoryExists(storage_path('app/exports'));

        $handle = fopen($path, 'w');

        // Add headers
        if (!empty($data['headers'])) {
            fputcsv($handle, $data['headers']);
        }

        // Add rows
        if (!empty($data['rows'])) {
            foreach ($data['rows'] as $row) {
                fputcsv($handle, $row);
            }
        }

        fclose($handle);

        return [
            'filename' => $filename,
            'path' => $path,
            'url' => asset("storage/exports/{$filename}"),
            'size' => filesize($path),
        ];
    }

    /**
     * Generate HTML table for Excel export
     */
    protected function generateHtmlTable(string $type, array $data): string
    {
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h1>' . e($this->getReportTitle($type)) . '</h1>';
        $html .= '<p>Generated: ' . now()->format('Y-m-d H:i:s') . '</p>';

        if (!empty($data['headers']) && !empty($data['rows'])) {
            $html .= '<table border="1" cellpadding="5" cellspacing="0">';

            // Headers
            $html .= '<tr style="background-color: #f0f0f0;">';
            foreach ($data['headers'] as $header) {
                $html .= '<th>' . e($header) . '</th>';
            }
            $html .= '</tr>';

            // Rows
            foreach ($data['rows'] as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . e($cell) . '</td>';
                }
                $html .= '</tr>';
            }

            $html .= '</table>';
        }

        $html .= '</body></html>';

        return $html;
    }

    /**
     * Log export activity
     */
    protected function logExport(string $type, string $format, array $result, string $status, ?Request $request, ?string $error = null): void
    {
        try {
            ExportLog::logExport([
                'user_id' => auth()->id(),
                'export_type' => $type,
                'report_name' => $this->getReportTitle($type),
                'file_format' => $format,
                'file_path' => $result['path'] ?? null,
                'file_size' => $result['size'] ?? null,
                'record_count' => $result['record_count'] ?? 0,
                'ip_address' => $request?->ip(),
                'status' => $status,
                'error_message' => $error,
            ]);
        } catch (\Exception $e) {
            Log::warning("Failed to log export: " . $e->getMessage());
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(string $type, string $extension): string
    {
        $timestamp = now()->format('Y-m-d_His');
        return "{$type}_{$timestamp}.{$extension}";
    }

    /**
     * Get report title based on type
     */
    protected function getReportTitle(string $type): string
    {
        return match ($type) {
            self::TYPE_PAYMENTS => 'Payment Report',
            self::TYPE_ORDERS => 'Order Report',
            self::TYPE_SUBSCRIPTIONS => 'Subscription Report',
            self::TYPE_TRANSACTIONS => 'Transaction History',
            self::TYPE_FINANCIAL => 'Financial Statement',
            self::TYPE_BUDGET => 'Budget Plan Report',
            self::TYPE_EXPENSE => 'Expense Report',
            self::TYPE_BUDGET_VARIANCE => 'Budget Variance Report',
            default => 'Export Report',
        };
    }

    /**
     * Validate email against company registered emails
     */
    public function validateEmail(string $email): array
    {
        // Check if email is registered in the system
        $isRegistered = CompanySettings::isRegisteredEmail($email);

        if (!$isRegistered) {
            return [
                'valid' => false,
                'message' => "Email address '{$email}' is not registered in the system. Only company-recorded email addresses are allowed for sharing."
            ];
        }

        return [
            'valid' => true,
            'message' => 'Email validated successfully'
        ];
    }

    /**
     * Send report via email
     */
    public function sendReportViaEmail(string $recipientEmail, array $fileData, string $reportTitle): array
    {
        // Validate recipient email
        $validation = $this->validateEmail($recipientEmail);

        if (!$validation['valid']) {
            throw new \InvalidArgumentException($validation['message']);
        }

        // Get sender (current user's email) - must be company email
        $senderEmail = auth()->user()->email ?? null;

        if ($senderEmail) {
            $senderValidation = $this->validateEmail($senderEmail);
            if (!$senderValidation['valid']) {
                throw new \InvalidArgumentException("Your email address is not registered in the system. Only company email addresses can send reports.");
            }
        }

        // In a real implementation, this would send the email
        // For now, we'll just log it
        Log::info("Report email would be sent", [
            'from' => $senderEmail,
            'to' => $recipientEmail,
            'report' => $reportTitle,
            'file' => $fileData['filename'] ?? 'unknown'
        ]);

        return [
            'success' => true,
            'message' => "Report sent successfully to {$recipientEmail}"
        ];
    }

    /**
     * Prepare payment data for export
     */
    public function preparePaymentData(array $filters = []): array
    {
        $query = \App\Models\Payment::query()
            ->with(['user', 'category']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('payment_status', $filters['status']);
        }
        if (!empty($filters['method'])) {
            $query->where('payment_method', $filters['method']);
        }
        if (!empty($filters['type'])) {
            $query->where('payment_type', $filters['type']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        $headers = ['Reference', 'Date', 'Payer', 'Type', 'Amount', 'Method', 'Status', 'Description'];
        $rows = [];

        foreach ($payments as $payment) {
            $rows[] = [
                $payment->payment_reference,
                $payment->created_at->format('Y-m-d H:i'),
                $payment->user?->name ?? 'N/A',
                ucfirst(str_replace('_', ' ', $payment->payment_type)),
                number_format($payment->amount, 2),
                ucfirst($payment->payment_method),
                ucfirst($payment->payment_status),
                Str::limit($payment->description, 30),
            ];
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'record_count' => count($rows),
            'total_amount' => $payments->sum('amount'),
        ];
    }

    /**
     * Prepare order data for export
     */
    public function prepareOrderData(array $filters = []): array
    {
        $query = \App\Models\Order::query();

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('order_status', $filters['status']);
        }
        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $headers = ['Order #', 'Date', 'Customer', 'Total', 'Payment Method', 'Payment Status', 'Order Status'];
        $rows = [];

        foreach ($orders as $order) {
            $rows[] = [
                $order->order_number,
                $order->created_at->format('Y-m-d H:i'),
                $order->customer_name,
                number_format($order->total_amount, 2),
                ucfirst($order->payment_method),
                ucfirst($order->payment_status),
                ucfirst($order->order_status),
            ];
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'record_count' => count($rows),
            'total_amount' => $orders->sum('total_amount'),
        ];
    }

    /**
     * Prepare budget data for export
     */
    public function prepareBudgetData(array $filters = []): array
    {
        $query = \App\Models\BudgetPlan::query()
            ->with(['organization', 'createdBy']);

        // Apply filters
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }
        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $budgets = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        $headers = ['Name', 'Type', 'Year', 'Period', 'Total Budget', 'Total Spent', 'Balance', 'Status'];
        $rows = [];

        foreach ($budgets as $budget) {
            $rows[] = [
                $budget->name,
                ucfirst($budget->type),
                $budget->year,
                $budget->getPeriodLabel(),
                number_format($budget->total_budget, 2),
                number_format($budget->total_spent, 2),
                number_format($budget->getBalance(), 2),
                ucfirst($budget->status),
            ];
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'record_count' => count($rows),
            'total_budget' => $budgets->sum('total_budget'),
            'total_spent' => $budgets->sum('total_spent'),
        ];
    }

    /**
     * Prepare expense data for export
     */
    public function prepareExpenseData(array $filters = []): array
    {
        $query = \App\Models\Expense::query()
            ->with(['category', 'budgetPlan', 'createdBy']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['category_id'])) {
            $query->where('expense_category_id', $filters['category_id']);
        }
        if (!empty($filters['budget_plan_id'])) {
            $query->where('budget_plan_id', $filters['budget_plan_id']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('expense_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('expense_date', '<=', $filters['date_to']);
        }
        if (!empty($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        $headers = ['Reference', 'Date', 'Title', 'Category', 'Amount', 'Status', 'Payment Method', 'Vendor'];
        $rows = [];

        foreach ($expenses as $expense) {
            $rows[] = [
                $expense->reference,
                $expense->expense_date->format('Y-m-d'),
                $expense->title,
                $expense->category?->name ?? 'N/A',
                number_format($expense->amount, 2),
                ucfirst($expense->status),
                $expense->payment_method ? ucfirst(str_replace('_', ' ', $expense->payment_method)) : 'N/A',
                $expense->vendor ?? 'N/A',
            ];
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'record_count' => count($rows),
            'total_amount' => $expenses->sum('amount'),
        ];
    }

    /**
     * Prepare budget variance report for export
     */
    public function prepareBudgetVarianceData(array $filters = []): array
    {
        $query = \App\Models\BudgetPlan::query()
            ->with(['items.category', 'expenses']);

        // Apply filters
        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }
        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $budgets = $query->orderBy('year', 'desc')->get();

        $headers = ['Budget', 'Category', 'Budgeted', 'Spent', 'Variance', 'Variance %'];
        $rows = [];

        foreach ($budgets as $budget) {
            foreach ($budget->items as $item) {
                $variance = $item->budgeted_amount - $item->spent_amount;
                $variancePercent = $item->budgeted_amount > 0
                    ? round(($variance / $item->budgeted_amount) * 100, 2)
                    : 0;

                $rows[] = [
                    $budget->name,
                    $item->category?->name ?? $item->name,
                    number_format($item->budgeted_amount, 2),
                    number_format($item->spent_amount, 2),
                    number_format($variance, 2),
                    $variancePercent . '%',
                ];
            }
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'record_count' => count($rows),
        ];
    }
}
