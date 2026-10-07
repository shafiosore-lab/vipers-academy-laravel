@extends('layouts.admin')

@section('title', __('Role Hierarchy'))

@push('styles')
<style>
    .role-tree {
        padding-left: 20px;
    }
    .role-node {
        position: relative;
        padding: 10px 15px;
        margin: 5px 0;
        border-left: 3px solid #6c757d;
        background: #f8f9fa;
        border-radius: 4px;
    }
    .role-node:hover {
        background: #e9ecef;
    }
    .role-node.super-admin { border-color: #000; }
    .role-node.org-admin { border-color: #0d6efd; }
    .role-node.admin-operations { border-color: #dc3545; }
    .role-node.coaching { border-color: #198754; }
    .role-node.management { border-color: #fd7e14; }
    .role-node.finance { border-color: #6f42c1; }
    .role-node.welfare { border-color: #20c997; }
    .role-node.media { border-color: #e91e63; }

    .role-children {
        margin-left: 30px;
        border-left: 1px dashed #dee2e6;
    }

    .role-connector {
        position: absolute;
        left: -30px;
        top: 0;
        bottom: 0;
        width: 30px;
        border-left: 1px dashed #dee2e6;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Role Hierarchy') }}</h1>
                    <p class="text-muted">{{ __('Visual representation of role relationships and permissions inheritance') }}</p>
                </div>
                <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">{{ __('Role Categories') }}</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <span><span class="badge bg-dark me-1">■</span> Platform Admin</span>
                        <span><span class="badge bg-primary me-1">■</span> Organization Admin</span>
                        <span><span class="badge bg-danger me-1">■</span> Admin Operations</span>
                        <span><span class="badge bg-success me-1">■</span> Coaching</span>
                        <span><span class="badge bg-warning text-dark me-1">■</span> Management</span>
                        <span><span class="badge bg-purple me-1" style="background-color: #6f42c1;">■</span> Finance</span>
                        <span><span class="badge bg-info me-1">■</span> Welfare</span>
                        <span><span class="badge bg-pink me-1" style="background-color: #e91e63;">■</span> Media</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Tree -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>{{ __('Role Hierarchy Tree') }}</h5>
                </div>
                <div class="card-body">
                    @php
                    function renderRoleTree($items, $depth = 0) {
                        foreach ($items as $item) {
                            $role = $item['role'];
                            $children = $item['children'] ?? [];
                            $moduleClass = $role->module ?? 'default';
                            $paddingLeft = $depth * 30;
                    @endphp
                            <div class="role-node {{ $moduleClass }}" style="margin-left: {{ $paddingLeft }}px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $role->name }}</strong>
                                        <span class="badge bg-secondary ms-2">{{ $role->slug }}</span>
                                        @if($role->is_system)
                                            <span class="badge bg-dark ms-1">System</span>
                                        @endif
                                        @if($role->is_template)
                                            <span class="badge bg-info ms-1">Template</span>
                                        @endif
                                        @if($role->parentRole)
                                            <span class="badge bg-light text-dark ms-1">
                                                <i class="fas fa-level-up-alt"></i>
                                                Inherits: {{ $role->parentRole->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">{{ $role->users()->count() }} users</span>
                                        <a href="{{ route('super-admin.roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                @if($role->description)
                                    <p class="mb-0 mt-2 small text-muted">{{ $role->description }}</p>
                                @endif
                            </div>
                    @php
                            if (!empty($children)) {
                                renderRoleTree($children, $depth + 1);
                            }
                        }
                    }
                    @endphp

                    @if(!empty($hierarchy))
                        {!! renderRoleTree($hierarchy) !!}
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No roles configured yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
