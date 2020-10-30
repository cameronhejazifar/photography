Dropzone.autoDiscover = false;

$(document).ready(() => {

    // Checklist items
    $('.checklist-item').on('change', (e) => {
        const checkbox = $(e.target);
        const spinner = checkbox.siblings('.animate-spin');
        const csrf = checkbox.siblings('[name=_token]');
        checkbox.prop('disabled', true);
        spinner.removeClass('hidden');
        $.ajax({
            url: checkbox.data('action'),
            timeout: 60000,
            cache: false,
            type: checkbox.data('method'),
            data: {
                // csrf
                _token: csrf.val(),
                // form data
                completed: checkbox.prop('checked') ? 1 : 0,
            },
            complete() {
                checkbox.prop('disabled', false);
                spinner.addClass('hidden');
            },
            error(xhr, status, error) {
                alert(xhr.responseText);
                checkbox.prop('checked', !checkbox.prop('checked'));
            },
        });
    });

    // Photo Collection management
    const newCollectionForm = $('#add-collection-form');
    const newCollectionCsrf = newCollectionForm.find('[name=_token]');
    const newCollectionSelect = newCollectionForm.find('[name=title]');
    const newCollectionSubmit = newCollectionForm.find('[type=button]');
    const newCollectionSpinner = newCollectionForm.find('.animate-spin');
    $('.delete-collection').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const btnSpinner = btn.find('.animate-spin');
        const btnTrashIcon = btn.find('.trash-icon');
        btnTrashIcon.addClass('hidden');
        btnSpinner.removeClass('hidden');
        btn.prop('disabled', true);
        $.ajax({
            url: btn.data('action'),
            timeout: 60000,
            cache: false,
            type: btn.data('method'),
            data: {
                // csrf
                _token: newCollectionCsrf.val(),
            },
            complete() {
                btnTrashIcon.removeClass('hidden');
                btnSpinner.addClass('hidden');
                btn.prop('disabled', false);
            },
            success() {
                btn.remove();
            },
            error(xhr) {
                alert(xhr.responseText);
            },
        });
    });
    newCollectionSelect.on('change', () => {
        if (newCollectionSelect.val() !== '_new') {
            return;
        }
        let newTitle = window.prompt('Enter the new collection name', '');
        if (typeof(newTitle) !== 'string' || newTitle.trim().length <= 0) {
            newCollectionSelect.val('');
            return;
        }
        const hasValue = newCollectionSelect.find("option[value='" + newTitle.replace(/'/ig, "\\'") + "']").length > 0;
        if (hasValue) {
            newCollectionSelect.val(newTitle);
            return;
        }
        $('<option></option>').attr('value', newTitle).text(newTitle).appendTo(newCollectionSelect);
        newCollectionSelect.val(newTitle);
        newCollectionForm.submit();
    });
    newCollectionForm.on('submit', (e) => {
        e.preventDefault();
        newCollectionSubmit.prop('disabled', true);
        newCollectionSpinner.removeClass('hidden');
        $.ajax({
            url: newCollectionForm.attr('action'),
            timeout: 60000,
            cache: false,
            type: newCollectionForm.attr('method'),
            data: {
                // csrf
                _token: newCollectionCsrf.val(),
                // form data
                title: newCollectionSelect.val(),
            },
            complete() {
                newCollectionSubmit.prop('disabled', false);
                newCollectionSpinner.addClass('hidden');
            },
            success() {
                window.location.reload();
            },
            error(xhr, status, error) {
                alert(xhr.responseText);
            },
        });
    });

    // Publish Button
    $('#publish-button').on('click', (e) => {
        e.preventDefault();
        $('#profile-info-form [name=status]').val('active');
        $('#profile-info-form').submit();
    });

    // Unpublish Button
    $('#unpublish-button').on('click', (e) => {
        e.preventDefault();
        $('#profile-info-form [name=status]').val('inactive');
        $('#profile-info-form').submit();
    });

    // Google Drive Button
    $('#link-google-drive').on('click', (e) => {
        e.preventDefault();
        openPopupWindow('/googledrive/oauth', 'Google Drive', 500, 600);
    });

    // Upload Photo Edit
    if ($('#upload-edit').length > 0) {
        const photoEditDropzone = new Dropzone('#upload-edit', {
            timeout: null,
            maxFilesize: null,
            maxFiles: null,
            dictDefaultMessage: 'Drag & Drop Image (JPG, PNG, etc.)',
            paramName: $('#upload-edit input[type=file]').attr('name'),
        });
        photoEditDropzone.on('sending', () => {
            photoEditDropzone.removeAllFiles();
        });
        photoEditDropzone.on('success', () => {
            window.location.reload();
        });
    }

    // Upload Photo Raw
    if ($('#upload-raw').length > 0) {
        const photoRawDropzone = new Dropzone('#upload-raw', {
            timeout: null,
            maxFilesize: null,
            maxFiles: null,
            dictDefaultMessage: 'Drag & Drop File',
            paramName: $('#upload-raw input[type=file]').attr('name'),
        });
        photoRawDropzone.on('sending', () => {
            photoRawDropzone.removeAllFiles();
        });
        photoRawDropzone.on('success', () => {
            window.location.reload();
        });
    }

    $('#upload-raw-type').on('change', () => {
        $('#upload-raw [name=other_type]').val($('#upload-raw-type').val());
    });

    $('#post-to-flickr').on('click', (e) => {
        e.preventDefault();
        openPopupWindow($('#post-to-flickr').attr('href'), 'Flickr', 500, 930);
    });

    $('#generate-instagram-post').on('click', (e) => {
        e.preventDefault();
        openPopupWindow($('#generate-instagram-post').attr('href'), 'Instagram', 500, 700);
    });
});

function openPopupWindow(url, title, w, h) {
    // Fixes dual-screen position (Most browsers vs Firefox)
    const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
    const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    const systemZoom = 1; // const systemZoom = width / window.screen.availWidth;
    const left = (width - w) / 2 / systemZoom + dualScreenLeft;
    const top = (height - h) / 2 / systemZoom + dualScreenTop;
    const newWindow = window.open(url, title, `
          scrollbars=yes,
          width=${w / systemZoom},
          height=${h / systemZoom},
          top=${top},
          left=${left}
      `);
    if (window.focus) {
        newWindow.focus();
    }
}

function onGoogleOauthComplete(popupWindow, meta) {
    popupWindow.close();
    if (meta.success) {
        $('#googledrive-auth-success').removeClass('hidden');
        $('#googledrive-auth-failure').addClass('hidden');
        $('#upload-edit-container').removeClass('hidden');
        $('#download-edit').removeClass('disabled cursor-not-allowed');
    } else {
        $('#googledrive-auth-success').addClass('hidden');
        $('#googledrive-auth-failure').removeClass('hidden');
        $('#upload-edit-container').addClass('hidden');
        $('#download-edit').addClass('disabled cursor-not-allowed');
    }
}
window.onGoogleOauthComplete = onGoogleOauthComplete;

function onFlickrPostComplete(popupWindow, meta) {
    popupWindow.close();
    if (meta.success) {
        $('#flickr-post-success').addClass('hidden');
        $('#flickr-post-failure').removeClass('hidden');
    } else {
        $('#flickr-post-success').removeClass('hidden');
        $('#flickr-post-failure').addClass('hidden');
    }
}
window.onFlickrPostComplete = onFlickrPostComplete;
