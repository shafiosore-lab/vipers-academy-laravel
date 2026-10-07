<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\CompanySettings;
use App\Models\ExportLog;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Display export options page
     */
    public function index()
    {
        $recentExports = ExportLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('staff.exports.index', compact('recentExports'));
    }

    /**
     * Export payments report
     */
    public function exportPayments(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'format' => ['required', Rule::in(['pdf', 'excel', 'csv'])],
            'status' => ['nullable', Rule::in(['pending', 'completed', 'failed', 'refunded'])],
            'method' => ['nullable', Rule::in(['mpesa', 'cash', 'bank_transfer', 'card', 'cheque'])],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'orientation' => ['nullable', Rule::in(['portrait', 'landscape'])],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Prepare filters
            $filters = $request->only(['status', 'method', 'date_from', 'date_to']);

            // Get payment data
            $data = $this->exportService->preparePaymentData($filters);

            // Add orientation for PDF
            $data['orientation'] = $request->input('orientation', 'portrait');

            // Generate export
            $result = $this->exportService->export(
                ExportService::TYPE_PAYMENTS,
                $request->input('format'),
                $data,
                $request
            );

            return response()->download($result['path'], $result['filename'], [
                'Content-Type' => $this->getContentType($request->input('format')),
            ]);

        } catch (\Exception $e) {
            Log::error("Payment export failed: " . $e->getMessage());

            return back()->with('error', 'Failed to export payments: ' . $e->getMessage());
        }
    }

    /**
     * Export orders report
     */
    public function exportOrders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => ['required', Rule::in(['pdf', 'excel', 'csv'])],
            'status' => ['nullable', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
            'payment_status' => ['nullable', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'orientation' => ['nullable', Rule::in(['portrait', 'landscape'])],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $filters = $request->only(['status', 'payment_status', 'date_from', 'date_to']);
            $data = $this->exportService->prepareOrderData($filters);
            $data['orientation'] = $request->input('orientation', 'portrait');

            $result = $this->exportService->export(
                ExportService::TYPE_ORDERS,
                $request->input('format'),
                $data,
                $request
            );

            return response()->download($result['path'], $result['filename'], [
                'Content-Type' => $this->getContentType($request->input('format')),
            ]);

        } catch (\Exception $e) {
            Log::error("Order export failed: " . $e->getMessage());

            return back()->with('error', 'Failed to export orders: ' . $e->getMessage());
        }
    }

    /**
     * Export financial statement
     */
    public function exportFinancialStatement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => ['required', Rule::in(['pdf', 'excel', 'csv'])],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'orientation' => ['nullable', Rule::in(['portrait', 'landscape'])],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Get payment summary for the period
            $payments = \App\Models\Payment::whereBetween('created_at', [
                $request->input('date_from'),
                $request->input('date_to')
            ])->get();

            $completedPayments = $payments->where('payment_status', 'completed');
            $pendingPayments = $payments->where('payment_status', 'pending');

            $data = [
                'headers' => ['Date', 'Reference', 'Description', 'Amount', 'Status'],
                'rows' => [],
                'record_count' => $payments->count(),
                'total_amount' => $completedPayments->sum('amount'),
                'total_completed' => $completedPayments->sum('amount'),
                'total_pending' => $pendingPayments->sum('amount'),
                'orientation' => $request->input('orientation', 'portrait'),
            ];

            foreach ($payments as $payment) {
                $data['rows'][] = [
                    $payment->created_at->format('Y-m-d'),
                    $payment->payment_reference,
                    $payment->description,
                    number_format($payment->amount, 2),
                    ucfirst($payment->payment_status),
                ];
            }

            $result = $this->exportService->export(
                ExportService::TYPE_FINANCIAL,
                $request->input('format'),
                $data,
                $request
            );

            return response()->download($result['path'], $result['filename'], [
                'Content-Type' => $this->getContentType($request->input('format')),
            ]);

        } catch (\Exception $e) {
            Log::error("Financial statement export failed: " . $e->getMessage());

            return back()->with('error', 'Failed to export financial statement: ' . $e->getMessage());
        }
    }

    /**
     * Share report via email
     */
    public function shareViaEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => ['required', Rule::in(['payments', 'orders', 'financial'])],
            'format' => ['required', Rule::in(['pdf', 'excel', 'csv'])],
            'recipient_email' => ['required', 'email'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Validate sender (current user must have company email)
            $senderEmail = auth()->user()->email;
            $senderValidation = $this->exportService->validateEmail($senderEmail);

            if (!$senderValidation['valid']) {
                return back()->with('error', $senderValidation['message']);
            }

            // Validate recipient
            $recipientValidation = $this->exportService->validateEmail($request->input('recipient_email'));

            if (!$recipientValidation['valid']) {
                return back()->with('error', $recipientValidation['message']);
            }

            // Generate the report first
            $filters = $request->only(['date_from', 'date_to']);

            $data = match ($request->input('report_type')) {
                'payments' => $this->exportService->preparePaymentData($filters),
                'orders' => $this->exportService->prepareOrderData($filters),
                default => throw new \InvalidArgumentException("Invalid report type"),
            };

            $data['orientation'] = 'portrait';

            $result = $this->exportService->export(
                $request->input('report_type'),
                $request->input('format'),
                $data,
                $request
            );

            // Send email
            $reportTitle = ucfirst($request->input('report_type')) . ' Report';
            $emailResult = $this->exportService->sendReportViaEmail(
                $request->input('recipient_email'),
                $result,
                $reportTitle
            );

            // Log email activity
            ExportLog::logExport([
                'user_id' => auth()->id(),
                'export_type' => $request->input('report_type'),
                'report_name' => $reportTitle,
                'file_format' => $request->input('format'),
                'file_path' => $result['path'],
                'file_size' => $result['size'],
                'record_count' => $data['record_count'],
                'email_recipients' => [$request->input('recipient_email')],
                'ip_address' => $request->ip(),
                'status' => 'email_sent',
            ]);

            return back()->with('success', $emailResult['message']);

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Email share failed: " . $e->getMessage());

            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * View export logs
     */
    public function logs()
    {
        $logs = ExportLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('staff.exports.logs', compact('logs'));
    }

    /**
     * Get content type for download
     */
    protected function getContentType(string $format): string
    {
        return match ($format) {
            'pdf' => 'application/pdf',
            'excel' => 'application/vnd.ms-excel',
            'csv' => 'text/csv',
            default => 'application/octet-stream',
        };
    }
}
