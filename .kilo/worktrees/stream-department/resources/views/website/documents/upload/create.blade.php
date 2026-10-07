@extends('layouts.app')

@section('title', 'Upload ' . $documentInfo['name'])

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('documents.upload.index') }}" class="text-indigo-600 hover:text-indigo-500 mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Documents
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Upload {{ $documentInfo['name'] }}</h1>
            <p class="text-gray-600">{{ $documentInfo['description'] }}</p>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('documents.upload.store', $documentType) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Document File
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="document_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="document_file" name="document_file" type="file"
                                           class="sr-only" accept="{{ implode(',', array_map(function($mime) { return '.' . str_replace('image/', '', $mime); }, explode(',', $documentInfo['allowed_mimes'] ?? 'pdf,jpg,jpeg,png'))) }}"
                                           required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ implode(', ', array_map('strtoupper', explode(',', $documentInfo['allowed_mimes'] ?? 'PDF, JPG, JPEG, PNG'))) }}
                                up to {{ number_format(($documentInfo['max_size'] ?? 5120) / 1024, 0) }}MB
                            </p>
                        </div>
                    </div>
                    @error('document_file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Preview -->
                <div id="file-preview" class="hidden mb-6 p-4 bg-gray-50 rounded-md">
                    <div class="flex items-center">
                        <i class="fas fa-file text-gray-400 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900" id="file-name"></p>
                            <p class="text-sm text-gray-500" id="file-size"></p>
                        </div>
                        <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Notes (Optional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                              placeholder="Any additional information about this document..."></textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms Agreement -->
                <div class="mb-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms_agreed" name="terms_agreed" type="checkbox" required
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms_agreed" class="font-medium text-gray-700">
                                I confirm that this document is authentic and accurately represents the required information.
                            </label>
                            <p class="text-gray-500 mt-1">
                                By uploading this document, you agree that it will be reviewed by academy administrators for account approval purposes.
                            </p>
                        </div>
                    </div>
                    @error('terms_agreed')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('documents.upload.index') }}"
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" id="upload-btn"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Document
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Document Requirements</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Ensure the document is clear and legible</li>
                            <li>All information must be current and accurate</li>
                            <li>Documents will be reviewed within 24-48 hours</li>
                            <li>You will receive a notification once your document is approved</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('document_file');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFile = document.getElementById('remove-file');
    const uploadBtn = document.getElementById('upload-btn');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.classList.remove('hidden');
            uploadBtn.disabled = false;
        } else {
            filePreview.classList.add('hidden');
            uploadBtn.disabled = true;
        }
    });

    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('hidden');
        uploadBtn.disabled = true;
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endsection
