<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationLetterhead;
use Barryvdh\DomPDF\Facade\Pdf;

class LetterheadService
{
    /**
     * Get the default letterhead for an organization.
     */
    public function getDefaultLetterhead(int $organizationId): ?OrganizationLetterhead
    {
        return OrganizationLetterhead::where('organization_id', $organizationId)
            ->where('is_default', true)
            ->first() ?? OrganizationLetterhead::where('organization_id', $organizationId)->first();
    }

    /**
     * Generate letterhead header HTML for PDF.
     */
    public function generateHeader(Organization $organization, ?OrganizationLetterhead $letterhead = null): string
    {
        $letterhead = $letterhead ?? $this->getDefaultLetterhead($organization->id);

        if (!$letterhead) {
            // Fallback to basic header
            return $this->generateBasicHeader($organization);
        }

        $logoUrl = $organization->logo ? asset('storage/' . $organization->logo) : null;
        $alignment = $letterhead->header_alignment ?? 'left';
        $primaryColor = $letterhead->primary_color ?? '#ea1c4d';

        $html = '<div class="letterhead-header" style="margin-bottom: 20px;">';

        if ($alignment === 'center') {
            $html .= '<div style="text-align: center;">';
            if ($logoUrl) {
                $html .= '<img src="' . $logoUrl . '" style="max-height: 80px; max-width: 200px; margin-bottom: 10px;" alt="Logo">';
            }
            $html .= '<h1 style="margin: 0; font-size: 20px; color: ' . $primaryColor . ';">' . e($organization->name) . '</h1>';
            $html .= $this->generateContactInfo($organization, $primaryColor);
            $html .= '</div>';
        } else {
            // Left alignment (default)
            $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
            $html .= '<tr>';
            if ($logoUrl) {
                $html .= '<td width="100" valign="top">';
                $html .= '<img src="' . $logoUrl . '" style="max-height: 80px; max-width: 100px;" alt="Logo">';
                $html .= '</td>';
                $html .= '<td valign="top">';
            } else {
                $html .= '<td valign="top">';
            }
            $html .= '<h1 style="margin: 0; font-size: 20px; color: ' . $primaryColor . ';">' . e($organization->name) . '</h1>';
            $html .= $this->generateContactInfo($organization, $primaryColor);
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }

        $html .= '<hr style="border: none; border-top: 2px solid ' . $primaryColor . '; margin-top: 15px;">';
        $html .= '</div>';

        return $html;
    }

    /**
     * Generate basic header without letterhead settings.
     */
    public function generateBasicHeader(Organization $organization): string
    {
        $logoUrl = $organization->logo ? asset('storage/' . $organization->logo) : null;

        $html = '<div class="letterhead-header" style="margin-bottom: 20px;">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';

        if ($logoUrl) {
            $html .= '<td width="100" valign="top">';
            $html .= '<img src="' . $logoUrl . '" style="max-height: 80px; max-width: 100px;" alt="Logo">';
            $html .= '</td>';
            $html .= '<td valign="top">';
        } else {
            $html .= '<td valign="top">';
        }

        $html .= '<h1 style="margin: 0; font-size: 20px; color: #ea1c4d;">' . e($organization->name) . '</h1>';

        if ($organization->email || $organization->phone || $organization->address) {
            $html .= '<p style="margin: 5px 0 0; font-size: 11px; color: #666;">';
            $html .= '<strong>Contact:</strong> ';
            $contactParts = [];
            if ($organization->email) $contactParts[] = e($organization->email);
            if ($organization->phone) $contactParts[] = e($organization->phone);
            if ($organization->address) $contactParts[] = e($organization->address);
            $html .= implode(' | ', $contactParts);
            $html .= '</p>';
        }

        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<hr style="border: none; border-top: 2px solid #ea1c4d; margin-top: 15px;">';
        $html .= '</div>';

        return $html;
    }

    /**
     * Generate contact info HTML.
     */
    private function generateContactInfo(Organization $organization, string $primaryColor): string
    {
        $html = '<p style="margin: 5px 0 0; font-size: 11px; color: #666;">';

        $parts = [];

        if ($organization->phone) {
            $parts[] = '<i class="fas fa-phone"></i> ' . e($organization->phone);
        }

        if ($organization->email) {
            $parts[] = '<i class="fas fa-envelope"></i> ' . e($organization->email);
        }

        if ($organization->website) {
            $parts[] = '<i class="fas fa-globe"></i> ' . e($organization->website);
        }

        if ($organization->address) {
            $parts[] = '<i class="fas fa-map-marker-alt"></i> ' . e($organization->address);
        }

        $html .= implode(' &nbsp;|&nbsp; ', $parts);
        $html .= '</p>';

        return $html;
    }

    /**
     * Generate footer HTML for PDF.
     */
    public function generateFooter(Organization $organization, ?OrganizationLetterhead $letterhead = null, int $page = 1, int $totalPages = 1): string
    {
        $letterhead = $letterhead ?? $this->getDefaultLetterhead($organization->id);
        $primaryColor = $letterhead?->primary_color ?? '#ea1c4d';

        $html = '<div class="letterhead-footer" style="margin-top: 30px;">';
        $html .= '<hr style="border: none; border-top: 1px solid #ddd; margin-bottom: 10px;">';

        $footerText = 'Generated via Mumias Vipers Sports Management SaaS Platform';

        if ($letterhead && $letterhead->custom_footer_note) {
            $footerText .= ' | ' . $letterhead->custom_footer_note;
        }

        $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td style="font-size: 10px; color: #999;">' . $footerText . '</td>';

        if ($letterhead && $letterhead->show_page_numbers) {
            $html .= '<td style="text-align: right; font-size: 10px; color: #999;">';
            $html .= 'Page ' . $page . ' of ' . $totalPages;
            $html .= '</td>';
        }

        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Generate full PDF with letterhead.
     */
    public function generatePdf(string $view, array $data, Organization $organization, ?string $filename = null): \Barryvdh\DomPDF\PDF
    {
        $letterhead = $this->getDefaultLetterhead($organization->id);

        $data['letterheadService'] = $this;
        $data['letterheadOrganization'] = $organization;
        $data['letterhead'] = $letterhead;

        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('a4');

        return $pdf;
    }

    /**
     * Generate report with automatic letterhead.
     */
    public function generateReportPdf(string $view, array $data, Organization $organization, string $reportTitle): \Barryvdh\DomPDF\PDF
    {
        $letterhead = $this->getDefaultLetterhead($organization->id);

        // Add report-specific data
        $data['reportTitle'] = $reportTitle;
        $data['reportDate'] = now()->format('F d, Y');
        $data['letterheadService'] = $this;
        $data['letterheadOrganization'] = $organization;
        $data['letterhead'] = $letterhead;

        $pdf = Pdf::loadView('reports.with-letterhead', $data);
        $pdf->setPaper('a4');

        return $pdf;
    }

    /**
     * Get Mumias Vipers master letterhead for super admin reports.
     */
    public function getMasterLetterhead(): array
    {
        return [
            'name' => 'Mumias Vipers Sports Academy',
            'email' => 'info@mumiasvipers.com',
            'phone' => '+254 700 000 000',
            'address' => 'Mumias, Kakamega County, Kenya',
            'website' => 'www.mumiasvipers.com',
            'logo' => asset('assets/img/logo/vps.jpeg'),
            'primary_color' => '#ea1c4d',
        ];
    }

    /**
     * Check if an organization has a letterhead configured.
     */
    public function hasLetterhead(int $organizationId): bool
    {
        return OrganizationLetterhead::where('organization_id', $organizationId)->exists();
    }

    /**
     * Create default letterhead for an organization.
     */
    public function createDefaultLetterhead(int $organizationId): OrganizationLetterhead
    {
        return OrganizationLetterhead::create([
            'organization_id' => $organizationId,
            'name' => 'Default Letterhead',
            'style' => OrganizationLetterhead::STYLE_CLASSIC,
            'header_alignment' => OrganizationLetterhead::ALIGNMENT_LEFT,
            'primary_color' => '#ea1c4d',
            'show_watermark' => true,
            'show_page_numbers' => true,
            'is_default' => true,
        ]);
    }
}
