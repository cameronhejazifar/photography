Dropzone.autoDiscover = false;

$(document).ready(() => {

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
        openPopupWindow($('#post-to-flickr').attr('href'), 'Flickr', 700, 990);
    })
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
