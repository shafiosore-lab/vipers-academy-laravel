{{-- Shared Player Styles Partial --}}
{{-- This file contains common CSS used by biography.blade.php, statistics.blade.php, career.blade.php, and ai-insights.blade.php --}}
{{-- Eliminates ~250 lines of duplicate CSS across multiple files --}}

@push('styles')
<style>
    /* ========================================
        HIGH-DENSITY DESIGN SYSTEM VARIABLES
        ======================================== */

    :root {
        --primary-red: #ea1c4d;
        --primary-red-light: #f87171;
        --primary-red-dark: #dc2626;
        --secondary-green: #059669;
        --neutral-50: #fafafa;
        --neutral-100: #f5f5f5;
        --neutral-200: #e5e5e5;
        --neutral-300: #d4d4d4;
        --neutral-400: #a3a3a3;
        --neutral-500: #737373;
        --neutral-600: #525252;
        --neutral-700: #404040;
        --neutral-800: #262626;
        --neutral-900: #171717;
        --border-radius: 8px;
        --border-radius-lg: 12px;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --transition: all 0.15s ease;
    }

    /* ========================================
        BASE LAYOUT
        ======================================== */

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .compact-player-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 1rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: var(--neutral-50);
        min-height: 100vh;
    }

    /* ========================================
        BACKWARD COMPATIBILITY (for other pages)
        ======================================== */

    .vipers-player-container {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: var(--neutral-50);
        color: var(--neutral-900);
        padding: 1rem;
        min-height: 100vh;
    }

    .player-profile {
        max-width: 1000px;
        margin: auto;
    }

    /* Legacy player header (for other pages) */
    .player-header {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 1.5rem;
        background: white;
        padding: 1.5rem;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow);
        border: 1px solid var(--neutral-200);
        margin-bottom: 1.5rem;
    }

    .player-photo {
        width: 200px;
        height: 240px;
        object-fit: cover;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
    }

    .player-photo-placeholder {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-light) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        color: white;
    }

    .player-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 240px;
    }

    .player-details {
        flex: 1;
    }

    .player-name {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
        color: var(--neutral-900);
    }

    .player-position {
        font-size: 1rem;
        color: var(--primary-red);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .player-description {
        color: var(--neutral-600);
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .player-radar-container {
        width: 100%;
        max-width: 150px;
        height: 150px;
        margin-top: 1rem;
        align-self: flex-end;
    }

    .player-radar-container canvas {
        width: 100% !important;
        height: 100% !important;
    }

    /* Legacy navigation */
    .section-nav {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .nav-link {
        padding: 0.75rem 1.5rem;
        background: white;
        border-radius: 20px;
        text-decoration: none;
        border: 1px solid var(--neutral-300);
        transition: var(--transition);
        font-weight: 600;
        color: var(--neutral-600);
        font-size: 0.875rem;
    }

    .nav-link.active {
        border-color: var(--primary-red);
        color: var(--primary-red);
        background: rgba(234, 28, 77, 0.05);
    }

    .nav-link:hover {
        border-color: var(--primary-red);
        transform: translateY(-1px);
        box-shadow: var(--shadow);
    }

    /* Legacy content section */
    .content-section {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--neutral-200);
    }

    /* ========================================
        RESPONSIVE DESIGN - MOBILE FIRST
        ======================================== */

    @media (max-width: 767px) {
        .compact-player-container,
        .vipers-player-container {
            padding: 0.75rem;
        }

        .player-header {
            grid-template-columns: 1fr;
            padding: 1rem;
            gap: 1rem;
        }

        .player-photo {
            width: 100%;
            max-width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        .player-info {
            min-height: auto;
        }

        .player-radar-container {
            max-width: 120px;
            height: 120px;
            margin: 1rem auto 0;
            align-self: center;
        }

        .player-name {
            font-size: 1.5rem;
            text-align: center;
        }

        .player-position {
            font-size: 0.875rem;
            text-align: center;
        }

        .player-description {
            text-align: center;
        }

        .section-nav {
            justify-content: center;
            gap: 0.25rem;
        }

        .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
        }

        .content-section {
            padding: 1rem;
        }
    }

    @media (max-width: 480px) {
        .compact-player-container,
        .vipers-player-container {
            padding: 0.5rem;
        }

        .player-header {
            padding: 0.75rem;
            gap: 0.75rem;
        }

        .player-photo {
            max-width: 160px;
            height: 160px;
        }

        .player-radar-container {
            max-width: 100px;
            height: 100px;
            margin-top: 0.75rem;
        }

        .player-name {
            font-size: 1.25rem;
        }

        .player-position {
            font-size: 0.8125rem;
        }

        .player-description {
            font-size: 0.8125rem;
        }

        .section-nav {
            gap: 0.125rem;
        }

        .nav-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .content-section {
            padding: 0.75rem;
        }
    }
</style>
@endpush

