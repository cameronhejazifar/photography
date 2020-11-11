$(document).ready(() => {

    const pictureContainer = $('#picture-container');
    const picture = $('#picture');
    const pictureSpinner = $('#picture-spinner');
    if (picture.length <= 0) {
        return;
    }

    /**
     * Preloads an image and then runs the callback function after it has loaded.
     *
     * @param urls
     * @param callback
     */
    function loadImage(urls, callback) {
        if (typeof(urls) === 'string') {
            urls = [urls];
        }
        for (let i = 0; i < urls.length; i++) {
            const img = $('<img/>');
            img.on('load', () => {
                img.off('load');
                callback();
            });
            img.attr('src', urls[i]);
        }
    }

    // Photo Loader
    const url = picture.data('image-url');
    loadImage(url, () => {
        picture.css('background-image', "url('" + url + "')");
        picture.removeClass('opacity-0').addClass('opacity-100');
        pictureSpinner.removeClass('opacity-100').addClass('opacity-0');
    });

    // Picture size
    const originalWidth = picture.data('original-width');
    const originalHeight = picture.data('original-height');
    function resizePicture() {
        const width = pictureContainer.outerWidth();
        const aspectRatio = originalWidth / originalHeight;
        const height = parseInt(width / aspectRatio);
        pictureContainer.css('height', height);
    }
    $(window).resize(resizePicture);
    resizePicture();

});
