/**
 * File Upload Component
 * Dropzone initialization wrapper for file uploads
 */

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. File upload may not work.');
}

/**
 * Initialize Dropzone for file uploads
 * @param {Object} options - Configuration options
 */
export function initFileUpload(options = {}) {
    if (typeof window.Dropzone === 'undefined') {
        console.warn('Dropzone is not loaded. File upload may not work.');
        return;
    }

    const config = {
        formId: 'dropzoneForm',
        submitButtonId: '#submit-all',
        entityId: options.entityId || null,
        entityType: options.entityType || 'order',
        uploadRoute: options.uploadRoute || '/file/upload',
        loadingSelector: '#loading',
        ...options
    };

    // Configure Dropzone
    if (Dropzone.options[config.formId]) {
        Dropzone.options[config.formId] = {
            autoProcessQueue: true,
            init: function() {
                const myDropzone = this;
                const submitButton = document.querySelector(config.submitButtonId);

                if (submitButton) {
                    submitButton.addEventListener('click', function() {
                        window.location.reload();
                    });
                }

                this.on('sending', function(file, xhr, formData) {
                    if (typeof window.$ !== 'undefined') {
                        $('body').css('opacity', '0.5');
                        if ($(config.loadingSelector).length) {
                            $(config.loadingSelector).show();
                        }
                    }
                    formData.append(config.entityType + 'ID', config.entityId);
                });

                this.on('complete', function() {
                    if (this.getQueuedFiles().length === 0 && this.getUploadingFiles().length === 0) {
                        window.location.reload();
                    }
                });
            }
        };
    }
}

