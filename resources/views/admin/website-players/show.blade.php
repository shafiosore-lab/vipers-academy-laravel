@extends('layouts.admin')

@section('title', 'View Website Player - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Player Details: {{ $websitePlayer->full_name }}</h3>
                    <div class="float-right">
                        <a href="{{ route('admin.website-players.edit', $websitePlayer) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.website-players.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Players
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($websitePlayer->image_url)
                                <img src="{{ $websitePlayer->image_url }}" alt="{{ $websitePlayer->full_name }}"
                                     class="img-fluid rounded mb-3" style="max-width: 200px;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 200px; height: 200px; border-radius: 10px; font-size: 4rem;">
                                    {{ substr($websitePlayer->first_name, 0, 1) }}{{ substr($websitePlayer->last_name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Full Name:</th>
                                    <td>{{ $websitePlayer->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $websitePlayer->formatted_category }}</td>
                                </tr>
                                <tr>
                                    <th>Position:</th>
                                    <td>{{ $websitePlayer->formatted_position }}</td>
                                </tr>
                                <tr>
                                    <th>Age:</th>
                                    <td>{{ $websitePlayer->age }} years</td>
                                </tr>
                                <tr>
                                    <th>Jersey Number:</th>
                                    <td>{{ $websitePlayer->jersey_number ?: 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Goals:</th>
                                    <td>{{ $websitePlayer->goals }}</td>
                                </tr>
                                <tr>
                                    <th>Assists:</th>
                                    <td>{{ $websitePlayer->assists }}</td>
                                </tr>
                                <tr>
                                    <th>Appearances:</th>
                                    <td>{{ $websitePlayer->appearances }}</td>
                                </tr>
                                @if($websitePlayer->bio)
                                    <tr>
                                        <th>Biography:</th>
                                        <td>{{ $websitePlayer->bio }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $websitePlayer->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $websitePlayer->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
