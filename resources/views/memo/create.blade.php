@extends('layouts.app')

@section('page-title', 'Create Memo Request - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">Create Memo Request</h2>
        <a href="{{ route('memo.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
            ← Back to Requests
        </a>
    </div>

    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <h4 style="margin: 0 0 10px 0;">Please correct the following errors:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('memo.store') }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        @csrf

        <!-- Title -->
        <div style="margin-bottom: 25px;">
            <label for="title" style="display: block; color: #495057; font-weight: 600; margin-bottom: 8px; font-size: 16px;">
                Request Title <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" 
                   placeholder="Enter a descriptive title for your memo request"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.2s ease;"
                   onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'"
                   required>
            <small style="color: #6c757d; font-size: 12px; margin-top: 5px; display: block;">
                Provide a clear, descriptive title for your memo request
            </small>
        </div>

        <!-- Description -->
        <div style="margin-bottom: 25px;">
            <label for="description" style="display: block; color: #495057; font-weight: 600; margin-bottom: 8px; font-size: 16px;">
                Description <span style="color: #dc3545;">*</span>
            </label>
            <textarea id="description" name="description" rows="6" 
                      placeholder="Describe the purpose of your memo request and provide any relevant details..."
                      style="width: 100%; padding: 12px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.2s ease; resize: vertical;"
                      onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'"
                      required>{{ old('description') }}</textarea>
            <small style="color: #6c757d; font-size: 12px; margin-top: 5px; display: block;">
                Minimum 10 characters. Explain what forms you're submitting and why you need a memo.
            </small>
        </div>

        <!-- Form Files Upload -->
        <div style="margin-bottom: 30px;">
            <label for="form_files" style="display: block; color: #495057; font-weight: 600; margin-bottom: 8px; font-size: 16px;">
                Upload Forms <span style="color: #dc3545;">*</span>
            </label>
            <div style="border: 2px dashed #e9ecef; border-radius: 8px; padding: 30px; text-align: center; background: #f8f9fa; transition: all 0.2s ease;" 
                 onmouseover="this.style.borderColor='#3498db'; this.style.backgroundColor='#e7f3ff'" 
                 onmouseout="this.style.borderColor='#e9ecef'; this.style.backgroundColor='#f8f9fa'">
                <div style="font-size: 48px; margin-bottom: 15px; color: #6c757d;">📄</div>
                <input type="file" id="form_files" name="form_files[]" multiple accept=".pdf" 
                       style="display: none;" required>
                <label for="form_files" style="display: inline-block; background: #3498db; color: white; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: background 0.2s ease;"
                       onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                    Choose PDF Files
                </label>
                <div style="margin-top: 15px; color: #6c757d; font-size: 14px;">
                    <strong>Select multiple PDF files</strong><br>
                    Maximum file size: 10MB per file<br>
                    Supported format: PDF only
                </div>
                <div id="file-list" style="margin-top: 15px; text-align: left; display: none;">
                    <h4 style="color: #2c3e50; margin-bottom: 10px;">Selected Files:</h4>
                    <ul id="file-names" style="color: #495057; margin: 0; padding-left: 20px;"></ul>
                </div>
            </div>
            <small style="color: #6c757d; font-size: 12px; margin-top: 5px; display: block;">
                Upload all the completed forms that you need included in your memo request. You can select multiple PDF files at once.
            </small>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <a href="{{ route('memo.index') }}" style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; transition: background 0.2s ease;"
               onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                Cancel
            </a>
            <button type="submit" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;"
                    onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                📝 Submit Memo Request
            </button>
        </div>
    </form>
</div>

<style>
.content-card {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    margin: 20px;
}

@media (max-width: 768px) {
    .content-card {
        margin: 10px;
        padding: 20px;
    }
    
    .content-card form {
        padding: 20px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('form_files');
    const fileList = document.getElementById('file-list');
    const fileNames = document.getElementById('file-names');
    
    fileInput.addEventListener('change', function() {
        const files = this.files;
        
        if (files.length > 0) {
            fileList.style.display = 'block';
            fileNames.innerHTML = '';
            
            for (let i = 0; i < files.length; i++) {
                const li = document.createElement('li');
                li.textContent = files[i].name;
                fileNames.appendChild(li);
            }
        } else {
            fileList.style.display = 'none';
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const files = fileInput.files;
        if (files.length === 0) {
            e.preventDefault();
            alert('Please select at least one PDF file to upload.');
            return false;
        }
        
        // Check file sizes
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 10 * 1024 * 1024) { // 10MB
                e.preventDefault();
                alert(`File "${files[i].name}" is too large. Maximum file size is 10MB.`);
                return false;
            }
            
            if (files[i].type !== 'application/pdf') {
                e.preventDefault();
                alert(`File "${files[i].name}" is not a PDF file. Please upload only PDF files.`);
                return false;
            }
        }
    });
});
</script>
@endsection
