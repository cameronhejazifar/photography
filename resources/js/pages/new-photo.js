$(document).ready(() => {
    $('#link-google-drive').on('click', (e) => {
        e.preventDefault();
        openPopupWindow('/googledrive/oauth', 'Google Drive', 250, 300);
    });
});

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
window.closePopupWindow = closePopupWindow;
