# Authentication Pages Redesign Plan

## Overview
Redesign the login and signup pages to be professional, polished, and at a high industry standard. The design will be clean, minimalist, and intuitive with the best possible user experience.

## Design Direction

### Visual Theme
- **Style**: Professional neutral gray/black with blue accents
- **Primary Color**: #2563eb (professional blue)
- **Background**: Clean white (#ffffff) with light gray (#f8fafc) accents
- **Typography**: Inter or system fonts for better readability
- **Feel**: Trustworthy, modern, enterprise-grade

### Layout Approach
- **Single column centered design** - More focused, less cluttered
- **Card-based form container** - Clean visual hierarchy
- **Minimal marketing** - Just branding, no heavy promotional content
- **Shared layout component** - Consistent styling between login/signup

## Components to Create

### 1. Shared Auth Layout (`resources/views/layouts/auth.blade.php`)
- Minimal header with logo
- Centered card container
- Consistent footer with links
- CSS variables for theming

### 2. Login Page (`resources/views/auth/login.blade.php`)
- Logo and welcome message
- Email input with validation
- Password input with show/hide toggle
- Remember me checkbox
- Forgot password link
- Submit button with loading state
- Social login options (Google, Facebook)
- Link to signup page with smooth transition

### 3. Signup Page (`resources/views/auth/signup.blade.php`)
- Account type dropdown selector:
  - Player
  - Coach
  - Partner
  - Team Manager
  - Organization
- Conditional fields based on selection:
  - All: First name, Last name, Email, Password, Confirm Password
  - Organization: Additional organization name field
- Terms acceptance checkbox
- Submit button with loading state
- Link to login page

## Validation & UX Features

### Form Validation
- Real-time email format validation
- Password strength indicator (signup)
- Password match validation (signup)
- Required field indicators
- Clear inline error messages
- Visual feedback on focus/blur

### Accessibility
- Proper ARIA labels
- Focus management
- Keyboard navigation
- Screen reader support
- Color contrast compliance

### Responsive Behavior
- Mobile: Full-width card, stacked layout
- Tablet: Centered card with padding
- Desktop: Centered card with max-width

## Technical Implementation

### CSS Architecture
- Use CSS custom properties for theming
- BEM-style class naming
- Mobile-first responsive approach
- CSS Grid/Flexbox for layouts

### JavaScript Features
- Form validation before submit
- Password visibility toggle
- Account type dropdown handling
- Loading state management
- Error message display

## Page Flow

```
┌─────────────────────────────────────┐
│           Auth Layout               │
│  ┌───────────────────────────────┐  │
│  │         Logo / Brand         │  │
│  │                               │  │
│  │     Login / Signup Form      │  │
│  │                               │  │
│  │  Email / Password fields     │  │
│  │  Account type (signup)       │  │
│  │  Remember / Terms checkboxes │  │
│  │  Submit button               │  │
│  │                               │  │
│  │   [Social Login Options]     │  │
│  │                               │  │
│  │  Link to other auth page    │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
```

## Acceptance Criteria

1. ✅ Both pages load without errors
2. ✅ Forms validate correctly before submission
3. ✅ Error messages display clearly
4. ✅ Responsive on mobile, tablet, and desktop
5. ✅ Accessible via keyboard navigation
6. ✅ Smooth transitions between login and signup
7. ✅ Professional appearance matching enterprise standards
8. ✅ All existing functionality preserved
