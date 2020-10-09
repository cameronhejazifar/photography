$(document).ready(() => {

    // Google Drive Button
    $('#link-google-drive').on('click', (e) => {
        e.preventDefault();
        openPopupWindow('/googledrive/oauth', 'Google Drive', 250, 300);
    });

    // Tags
    $('#tags-input').on('keypress', (e) => {
        if (e.which === 13) {
            e.preventDefault();
            e.stopPropagation();
            const val = $(e.target).val().trim();
            $(e.target).val('');
            const vals = val.replace(/#/ig, ' ')
                .replace(/\s\s+/ig, ' ')
                .split(/[ ,]/ig);
            vals.forEach((tag) => {
                addNewPhotoTag(tag);
            });
        }
    });
});

function addNewPhotoTag(tag) {
    const alreadyHasTag = $('#tag-input-container input[value="' + tag + '"]').length > 0;
    if (alreadyHasTag || tag.length <= 0) {
        return;
    }
    const hiddenInput = $('<input/>')
        .attr('type', 'hidden')
        .attr('name', 'tags[]')
        .val(tag);
    const displayText = $('<span/>')
        .addClass('flex flex-no-wrap flex-row items-center justify-center px-3 py-1 mb-1 mr-1 bg-gradient-to-b from-blue-400 to-blue-500 rounded-full cursor-pointer')
        .append($('<span/>').addClass('text-xs font-bold text-gray-200').text(tag));
    const closeButton = $('<button/>')
        .attr('type', 'button')
        .addClass('ml-2 w-3 h-3 close');
    displayText.append(closeButton);
    $('#tag-input-container').append(hiddenInput);
    $('#tag-display-container').append(displayText);
    $('#tags-count').text($('#tag-input-container input').length);
    closeButton.on('click', (closeEvent) => {
        closeEvent.preventDefault();
        hiddenInput.detach();
        displayText.detach();
    });
}

function openPopupWindow(url, title, w, h) {
    // Fixes dual-screen position (Most browsers vs Firefox)
    const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
    const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    const systemZoom = width / window.screen.availWidth;
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

function closePopupWindow(popupWindow, meta) {
    popupWindow.close();
    if (meta.success) {
        $('#googledrive-auth-success').removeClass('hidden');
        $('#googledrive-auth-failure').addClass('hidden');
    } else {
        $('#googledrive-auth-success').addClass('hidden');
        $('#googledrive-auth-failure').removeClass('hidden');
    }
}

window.addNewPhotoTag = addNewPhotoTag;
window.closePopupWindow = closePopupWindow;
