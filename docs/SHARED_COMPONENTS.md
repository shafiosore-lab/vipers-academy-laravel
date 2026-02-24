# Shared Components Architecture

This document describes the unified sidebar and taskbar component system for Vipers Academy dashboards.

## Overview

The shared component system provides consistent styling, layout, navigation structure, and visual design across all dashboard views including:
- Super Admin Dashboard
- Admin Dashboard
- Staff/Coach Dashboard
- Team Manager Dashboard
- Finance Officer Dashboard
- Media Officer Dashboard
- Welfare Officer Dashboard
- Player Portal
- Parent Portal
- Partner Portal

## Component Files

### 1. CSS Variables (`resources/views/components/layout/css-variables.blade.php`)

Contains all CSS custom properties used across dashboards. This is the **single source of truth** for all styling.

**Key Variables:**
- `--primary: #ea1c4d` - Main brand color
- `--secondary: #65c16e` - Success/secondary color
- `--sidebar-width: 260px` - Sidebar width
- `--header-height: 60px` - Header/taskbar height
- `--font-family: 'Roboto'` - Typography

### 2. Component Styles (`resources/views/components/layout/styles.blade.php`)

Additional component-specific styles that complement the CSS variables. Automatically included in the unified layout.

### 3. Header/Taskbar (`resources/views/components/layout/header.blade.php`)

The unified header component used across all dashboards.

**Features:**
- Role-based dashboard link (redirects to appropriate dashboard)
- Notification bell with count badge
- User avatar and dropdown menu
- Mobile sidebar toggle
- Breadcrumbs support

**Usage:**
```php
@include('components.layout.header')
```

**Props:**
- `title` - Page title (optional)
- `breadcrumbs` - Array of breadcrumb items (optional)
- `actions` - Custom actions slot (optional)
- `headerClass` - Custom CSS class for header

### 4. Unified Sidebar (`resources/views/components/layout/sidebar-unified.blade.php`)

The sidebar component that automatically displays role-appropriate menu items.

**Features:**
- Two modes: `accordion` (admin) and `simple` (staff)
- Automatic role-based menu filtering
- Accordion state persistence (localStorage)
- Active route highlighting
- Badge support for notifications

**Usage:**
```php
// Simple mode (staff-style)
@include('components.layout.sidebar-unified', ['mode' => 'simple'])

// Accordion mode (admin-style)
@include('components.layout.sidebar-unified', ['mode' => 'accordion'])
```

### 5. Unified Dashboard Layout (`resources/views/layouts/dashboard.blade.php`)

The master layout that combines all components.

**Features:**
- Single layout for all dashboard types
- Automatic role detection for menu mode
- Responsive behavior
- Flash message handling
- Accordion JavaScript

**Usage:**
```php
@extends('layouts.dashboard')
```

## Role-Based Menu System

The sidebar automatically displays menu items based on user roles:

### Super Admin / Admin Roles
- Dashboard
- Players (accordion)
- Communication (accordion)
- Competition (accordion)
- Finance (accordion)
- Academy (accordion)
- Content (accordion)
- System (accordion)

### Coach Roles
- Coaching Dashboard
- Sessions
- Players
- Attendance

### Team Manager Roles
- Management Dashboard
- Registrations
- Logistics
- Equipment Management

### Finance Officer Roles
- Finance Dashboard
- Payments
- Reports

### Media Officer Roles
- Media Dashboard
- Blogs
- Gallery

### Welfare Officer Roles
- Welfare Dashboard
- Attention List
- Compliance

### Player Roles
- My Portal (Dashboard, Profile, Programs, Training, etc.)

### Parent Roles
- Parent Portal (Dashboard, My Child, Finances, Training, etc.)

### Partner Roles
- Partner Portal (Dashboard, Players, Analytics)

## Visual Design Consistency

### Colors (CSS Variables)
All colors are defined in `css-variables.blade.php` and used consistently:

| Color | Hex | Usage |
|-------|-----|-------|
| Primary | `#ea1c4d` | Main brand, active states |
| Secondary | `#65c16e` | Success states |
| Accent | `#fbc761` | Warnings, highlights |
| Danger | `#dc2626` | Errors, alerts |
| Info | `#0891b2` | Information |

### Typography
- Font Family: Roboto
- Headings: Bold (700)
- Body: Regular (400)

### Spacing
Consistent spacing using CSS variables:
- `--spacing-xs`: 0.25rem
- `--spacing-sm`: 0.5rem
- `--spacing-md`: 1rem
- `--spacing-lg`: 1.5rem
- `--spacing-xl`: 2rem

### Layout Dimensions
- Sidebar width: 260px
- Header height: 60px
- Border radius: 6px (default), 8px (md), 12px (lg)

## Responsive Behavior

### Desktop (>992px)
- Sidebar always visible
- Full navigation

### Tablet (768px-992px)
- Sidebar hidden by default
- Mobile toggle visible

### Mobile (<768px)
- Sidebar hidden
- Hamburger menu toggle
- Compact header

## Making Updates

### To Update Styling
1. Edit `css-variables.blade.php` for colors, spacing, typography
2. Changes automatically reflect across ALL dashboards

### To Add New Menu Items
1. Edit `sidebar-unified.blade.php`
2. Add new menu items in the appropriate role section
3. Specify roles that should see the items

### To Add New Dashboard Type
1. Add role handling in `sidebar-unified.blade.php`
2. Add route logic in `header.blade.php`
3. Create new dashboard blade extending `layouts.dashboard`

## Migration Guide

### From Admin Layout
The existing `layouts/admin.blade.php` can be migrated by:
1. Changing `@extends('layouts.admin')` to `@extends('layouts.dashboard')`
2. Removing inline CSS and JavaScript
3. Using shared components

### From Staff Layout
Similarly, `layouts/staff.blade.php` can be migrated by:
1. Changing `@extends('layouts.staff')` to `@extends('layouts.dashboard')`
2. Removing duplicate CSS definitions

## JavaScript Functions

### Sidebar Toggle
```javascript
toggleSidebar() // Toggle sidebar visibility
```

### Accordion Functions
```javascript
toggleAccordionMode() // Toggle one-section-at-a-time mode
toggleAccordion(name) // Toggle specific accordion section
loadAccordionState() // Load saved state from localStorage
saveAccordionState() // Save current state to localStorage
```

### Alert Functions
```javascript
initAlerts() // Auto-hide alerts after 5 seconds
```

## Best Practices

1. **Never duplicate CSS** - Always use CSS variables
2. **Use components** - Include header/sidebar instead of inline HTML
3. **Keep menu logic in one place** - Edit sidebar-unified.blade.php
4. **Test responsive** - Verify behavior on all breakpoints
5. **Use role checks** - Check roles before showing role-specific content

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile Safari (iOS 12+)
- Chrome Mobile (Android 8+)
