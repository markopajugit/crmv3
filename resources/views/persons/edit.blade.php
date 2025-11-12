@extends('layouts.app')

@section('content')
<style>
    /* Modal Styling */
    .modal-content {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .modal-header {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        border-radius: 8px 8px 0 0;
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .modal-body {
        padding: 1.5rem;
        color: #374151;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-radius: 0 0 8px 8px;
    }

    .modal-footer .btn {
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        border-radius: 6px;
        margin-left: 0.5rem;
    }

    .modal-footer .btn-secondary {
        background-color: #6B7280;
        border-color: #6B7280;
        color: #ffffff;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #4B5563;
        border-color: #4B5563;
    }

    .modal-footer .btn-success {
        background-color: #10B981;
        border-color: #10B981;
        color: #ffffff;
    }

    .modal-footer .btn-success:hover {
        background-color: #059669;
        border-color: #059669;
    }

    .modal-footer .btn-primary {
        background-color: #DC2626;
        border-color: #DC2626;
        color: #ffffff;
    }

    .modal-footer .btn-primary:hover {
        background-color: #B91C1C;
        border-color: #B91C1C;
    }

    .btn-close {
        opacity: 0.5;
        transition: opacity 0.2s ease;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Form Elements in Modals */
    .modal-body .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    /* Style all inputs, selects, and textareas in modals (including dynamically created ones) */
    .modal-body input[type="text"],
    .modal-body input[type="email"],
    .modal-body input[type="tel"],
    .modal-body input[type="number"],
    .modal-body input[type="date"],
    .modal-body input[type="search"],
    .modal-body input[type="password"],
    .modal-body textarea,
    .modal-body select,
    .modal-body .form-control {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        color: #1f2937;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .modal-body input[type="text"]:focus,
    .modal-body input[type="email"]:focus,
    .modal-body input[type="tel"]:focus,
    .modal-body input[type="number"]:focus,
    .modal-body input[type="date"]:focus,
    .modal-body input[type="search"]:focus,
    .modal-body input[type="password"]:focus,
    .modal-body textarea:focus,
    .modal-body select:focus,
    .modal-body .form-control:focus {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    .modal-body input[type="text"]::placeholder,
    .modal-body input[type="email"]::placeholder,
    .modal-body input[type="tel"]::placeholder,
    .modal-body input[type="number"]::placeholder,
    .modal-body input[type="search"]::placeholder,
    .modal-body input[type="password"]::placeholder,
    .modal-body textarea::placeholder,
    .modal-body .form-control::placeholder {
        color: #9CA3AF;
    }

    .modal-body textarea,
    .modal-body textarea.form-control {
        resize: vertical;
    }

    .modal-body select,
    .modal-body select.form-control {
        cursor: pointer;
    }

    /* Alert Styling in Modals */
    .modal-body .alert {
        border-radius: 6px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
    }

    .modal-body .alert-danger {
        background-color: #FEF2F2;
        border-color: #FECACA;
        color: #991B1B;
    }

    .modal-body .alert-danger ul {
        margin-bottom: 0;
        padding-left: 1.25rem;
    }

    /* Modal Backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Responsive Modal */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-content {
            border-radius: 8px;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1rem;
        }
    }
</style>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit client</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('persons.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('persons.update',$person->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $person->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Detail:</strong>
                    <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ $person->detail }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>

<style>
    /* Base Typography - Ensure Nunito font is used */
    body, .card, .panel, .modal, input, select, textarea, button, .btn {
        font-family: 'Nunito', sans-serif;
    }

    /* Form Styling for Edit Page */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group strong {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        display: block;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        color: #1f2937;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        width: 100%;
        box-sizing: border-box;
        font-family: 'Nunito', sans-serif;
    }

    .form-control:focus {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #9CA3AF;
    }

    textarea.form-control {
        resize: vertical;
    }

    .btn-primary {
        background-color: #DC2626;
        border-color: #DC2626;
        color: #ffffff;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-family: 'Nunito', sans-serif;
    }

    .btn-primary:hover {
        background-color: #B91C1C;
        border-color: #B91C1C;
    }
</style>

<script>
    // Ensure jQuery is loaded before executing
    (function($) {
        'use strict';
        
        // Function to apply styles to dynamically created inputs in modals
        function applyModalInputStyles() {
            // Find all inputs, selects, and textareas in modals that don't have the form-control class
            $('.modal-body input[type="text"]:not(.form-control), ' +
              '.modal-body input[type="email"]:not(.form-control), ' +
              '.modal-body input[type="tel"]:not(.form-control), ' +
              '.modal-body input[type="number"]:not(.form-control), ' +
              '.modal-body input[type="date"]:not(.form-control), ' +
              '.modal-body input[type="search"]:not(.form-control), ' +
              '.modal-body input[type="password"]:not(.form-control), ' +
              '.modal-body textarea:not(.form-control), ' +
              '.modal-body select:not(.form-control)').each(function() {
                // Add form-control class if not present
                if (!$(this).hasClass('form-control')) {
                    $(this).addClass('form-control');
                }
            });
        }

        // Apply styles when document is ready
        $(document).ready(function() {
            applyModalInputStyles();
        });

        // Use MutationObserver to watch for dynamically added elements in modals
        if (typeof MutationObserver !== 'undefined') {
            // Observe all modals (both existing and future ones)
            function observeModals() {
                $('.modal').each(function() {
                    var modal = this;
                    // Skip if already observed
                    if ($(modal).data('modal-observed')) {
                        return;
                    }
                    $(modal).data('modal-observed', true);
                    
                    var observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.addedNodes.length) {
                                // Check if any added nodes are inputs, selects, or textareas
                                mutation.addedNodes.forEach(function(node) {
                                    if (node.nodeType === 1) { // Element node
                                        // Check if the node itself is an input/select/textarea
                                        if (node.tagName === 'INPUT' || node.tagName === 'SELECT' || node.tagName === 'TEXTAREA') {
                                            if (!$(node).hasClass('form-control')) {
                                                $(node).addClass('form-control');
                                            }
                                        }
                                        // Check for inputs/selects/textareas within the added node
                                        if (node.querySelectorAll) {
                                            var formElements = node.querySelectorAll('input, select, textarea');
                                            for (var i = 0; i < formElements.length; i++) {
                                                if (!$(formElements[i]).hasClass('form-control')) {
                                                    $(formElements[i]).addClass('form-control');
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    });

                    observer.observe(modal, {
                        childList: true,
                        subtree: true
                    });
                });
            }

            // Observe modals on document ready
            $(document).ready(function() {
                observeModals();
            });

            // Also observe when new modals are added to the DOM
            var modalObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && $(node).hasClass('modal')) {
                            observeModals();
                        }
                    });
                });
            });

            modalObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        // Also apply styles when modals are shown (as a fallback)
        $(document).on('shown.coreui.modal', '.modal', function() {
            applyModalInputStyles();
        });

        // Apply styles when modals are shown (Bootstrap 5 compatibility)
        $(document).on('shown.bs.modal', '.modal', function() {
            applyModalInputStyles();
        });

    })(jQuery);
</script>
@endsection
