# Dashboard Cleanup Plan - FINAL

## Objective (APPROVED)
Clean all dashboard views by:
1. Replacing Mumias Vipers logo with text-based "GameSuite" brand
2. Replacing all "Vipers Academy" text with "GameSuite" in dashboard interfaces

**Note: Navigation changes (item 3) NOT included per user request.**

---

## Files to Modify

### 1. Layout Files

#### [`resources/views/layouts/admin.blade.php`](resources/views/layouts/admin.blade.php)
- **Line 8**: Title tag - change "Vipers Academy" to "GameSuite"
- **Line 553**: Logo image - replace with text-based brand
- **Line 555**: Brand text - change "Vipers Academy" to "GameSuite"
- **Line 950**: Footer - change "Vipers Academy" to "GameSuite"

#### [`resources/views/layouts/staff.blade.php`](resources/views/layouts/staff.blade.php)
- **Line 8**: Title tag - change "Vipers Academy" to "GameSuite"
- **Line 617**: Logo image - replace with text-based brand
- **Line 622-623**: Brand text - change "Vipers Academy" to "GameSuite"
- **Line 1117**: Footer - change "Vipers Academy" to "GameSuite"

#### [`resources/views/layouts/dashboard.blade.php`](resources/views/layouts/dashboard.blade.php)
- **Line 27**: Title tag - change "Vipers Academy" to "GameSuite"
- **Line 108**: Footer - change "Vipers Academy" to "GameSuite"

### 2. Component Files

#### [`resources/views/components/layout/header.blade.php`](resources/views/components/layout/header.blade.php)
- **Line 91**: Logo image - replace with text-based brand
- **Line 92**: Alt text - change "Vipers Academy Logo" to "GameSuite"
- **Line 96**: Brand text - change "Vipers Academy" to "GameSuite"

#### [`resources/views/components/application-logo.blade.php`](resources/views/components/application-logo.blade.php)
- **Entire file**: Replace SVG logo with text-based "GameSuite" span

### 3. Player Portal

#### [`resources/views/player/portal/layout.blade.php`](resources/views/player/portal/layout.blade.php)
- **Line 7**: Title tag - change "Vipers Academy" to "GameSuite"
- **Line 826**: Logo image - replace with text-based brand
- **Line 828**: Brand text - change "Vipers Academy" to "GameSuite"

---

## Implementation Order

1. Replace logo in `application-logo.blade.php` (text-based)
2. Update `layouts/admin.blade.php`
3. Update `layouts/staff.blade.php` (logo + text)
4. Update `layouts/dashboard.blade.php`
5. Update `components/layout/header.blade.php`
6. Update `player/portal/layout.blade.php`
