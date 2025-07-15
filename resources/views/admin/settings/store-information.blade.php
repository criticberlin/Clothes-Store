@extends('layouts.admin')

@section('title', __('general.store_information'))
@section('description', __('general.store_settings_description'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('general.store_information') }}</h5>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="#" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="store_name" class="form-label">{{ __('general.store_name') }}</label>
                            <input type="text" class="form-control" id="store_name" name="store_name" value="MyClothes Store">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="store_email" class="form-label">{{ __('general.store_email') }}</label>
                            <input type="email" class="form-control" id="store_email" name="store_email" value="info@myclothes.com">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="store_phone" class="form-label">{{ __('general.store_phone') }}</label>
                            <input type="text" class="form-control" id="store_phone" name="store_phone" value="+1 (555) 123-4567">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="store_logo" class="form-label">{{ __('general.store_logo') }}</label>
                            <input type="file" class="form-control" id="store_logo" name="store_logo">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="store_address" class="form-label">{{ __('general.store_address') }}</label>
                    <textarea class="form-control" id="store_address" name="store_address" rows="3">123 Fashion Street, New York, NY 10001</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>{{ __('general.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection 