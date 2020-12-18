$(document).ready(() => {

    const photoList = $('#photo-list');
    const collectionList = $('#collection-list');
    if (photoList.length <= 0) {
        return;
    }
    $.ajax({
        url: collectionList.data('collection-endpoint'),
        timeout: 60000,
        cache: false,
        type: 'GET',
        data: {},
        success(response) {
            $('#collections-header').removeClass('hidden');
            buildCollectionsView(response);
        },
        error(xhr, status, error) {
            alert(xhr.responseText);
        },
    });

    let results = null;

    // Load the initial list
    const loadMoreBtnContainer = $('#load-more-button-container');
    const loadMoreBtn = $('#load-more-button');
    const loadMoreBtnSpinner = loadMoreBtn.find('.animate-spin');
    $.ajax({
        url: photoList.data('initial-query'),
        timeout: 60000,
        cache: false,
        type: 'GET',
        data: {},
        success(response) {
            results = response;
            if (results.next_page_url) {
                loadMoreBtnContainer.removeClass('hidden');
            } else {
                loadMoreBtnContainer.addClass('hidden');
            }
            $('#photos-header').removeClass('hidden');
            photoList.html('');
            buildResultsViews();
        },
        error(xhr, status, error) {
            alert(xhr.responseText);
        },
    });

    // Load More Button
    loadMoreBtn.on('click', (e) => {
        e.preventDefault();
        if (results.next_page_url) {
            loadMoreBtn.attr('disabled', true);
            loadMoreBtnSpinner.removeClass('hidden');
            $.ajax({
                url: results.next_page_url,
                timeout: 60000,
                cache: false,
                type: 'GET',
                data: {},
                complete() {
                    loadMoreBtn.attr('disabled', false);
                    loadMoreBtnSpinner.addClass('hidden');
                },
                success(response) {
                    results = response;
                    if (results.next_page_url) {
                        loadMoreBtn.removeClass('hidden');
                    } else {
                        loadMoreBtn.addClass('hidden');
                    }
                    buildResultsViews();
                },
                error(xhr, status, error) {
                    alert(xhr.responseText);
                },
            });
        }
    });

    function buildCollectionsView(collections) {
        collections.forEach((collection) => {

            // Container
            const container = $('<a/>');
            container.addClass('flex-shrink-0 relative block w-64 h-64 md:w-72 md:h-72 mx-1 bg-black');
            container.attr('href', collection.browse_url);

            // Loading Spinner
            const spinner = $('<span/>');
            spinner.addClass('absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none transition-all duration-500 ease-in-out opacity-100');
            spinner.html('<svg class="block w-8 h-8 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');
            spinner.appendTo(container);

            // Content
            const divContent = $('<div/>');
            divContent.addClass('absolute block top-0 left-0 w-full h-full transition-all duration-500 ease-in-out opacity-0');
            divContent.appendTo(container);

            // Thumbnail Container
            const thumbs = $('<div/>');
            thumbs.addClass('absolute left-0 top-0 w-full h-full flex flex-row flex-wrap items-center justify-center shadow-lg z-0 opacity-25 hover:opacity-50 transition-all duration-300 ease-in-out');
            thumbs.appendTo(divContent);

            // Thumbnails
            const images = [];
            collection.thumbnail_urls.forEach((url) => {
                const image = $('<span/>');
                image.addClass('block bg-transparent bg-center bg-no-repeat bg-cover');
                image.css('background-image', 'url("' + url + '")');
                image.appendTo(thumbs);
                images.push(image);
            });

            // Thumbnail Organization
            if (collection.thumbnail_urls.length === 1) {
                images[0].addClass('w-full h-full');
            }
            if (collection.thumbnail_urls.length === 2) {
                images[0].addClass('w-1/2 h-full');
                images[1].addClass('w-1/2 h-full');
            }
            if (collection.thumbnail_urls.length === 3) {
                images[0].addClass('w-full h-1/2');
                images[1].addClass('w-1/2 h-1/2');
                images[2].addClass('w-1/2 h-1/2');
            }
            if (collection.thumbnail_urls.length === 4) {
                images[0].addClass('w-1/2 h-1/2');
                images[1].addClass('w-1/2 h-1/2');
                images[2].addClass('w-1/2 h-1/2');
                images[3].addClass('w-1/2 h-1/2');
            }

            // Title
            const title = $('<span/>');
            title.addClass('absolute flex items-center justify-center left-0 top-0 w-full h-full p-5 text-2xl text-white text-center uppercase overflow-hidden pointer-events-none z-10');
            title.text(collection.title);
            title.appendTo(divContent);

            // Creator
            const creator = $('<span/>');
            creator.addClass('absolute block left-0 bottom-0 w-full p-2 text-xs text-white text-right italic truncate pointer-events-none z-10');
            creator.text(`by ${collection.created_by.name}`);
            creator.appendTo(divContent);

            // Load the image in the background
            loadImage(collection.thumbnail_urls, () => {
                spinner.removeClass('opacity-100').addClass('opacity-0');
                divContent.removeClass('opacity-0').addClass('opacity-100');
            });

            // Add collection to the list
            collectionList.append(container);
        });
    }

    function buildResultsViews() {
        for (let i = 0; i < results.data.length; i++) {
            const photo = results.data[i];
            const edit = photo.photograph_edits[0];
            photoList.append(buildImageView(photo, edit));
        }
    }

    function buildImageView(photo, edit) {

        // Container
        const container = $('<a/>');
        container.addClass('relative block w-64 h-64 md:w-72 md:h-72 m-2 bg-black');
        container.attr('href', photo.photograph_url);

        // Loading Spinner
        const spinner = $('<span/>');
        spinner.addClass('absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none transition-all duration-500 ease-in-out opacity-100');
        spinner.html('<svg class="block w-8 h-8 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');
        spinner.appendTo(container);

        // Content
        const divContent = $('<div/>');
        divContent.addClass('absolute block top-0 left-0 w-full h-full transition-all duration-500 ease-in-out opacity-0 transform hover:scale-105');
        divContent.appendTo(container);

        // Image
        const image = $('<span/>');
        image.addClass('absolute block left-0 top-0 w-full h-full bg-transparent bg-center bg-no-repeat bg-cover shadow-lg z-0 opacity-75 hover:opacity-100 transition-all duration-300 ease-in-out');
        image.css('background-image', 'url("' + edit.image_url + '")');
        image.appendTo(divContent);

        // Title
        const title = $('<span/>');
        title.addClass('absolute block left-0 bottom-0 w-full p-2 text-sm text-white font-bold bg-black bg-opacity-25 truncate pointer-events-none z-10');
        title.text(photo.name);
        title.appendTo(divContent);

        // Load the image in the background
        loadImage(edit.image_url, () => {
            spinner.removeClass('opacity-100').addClass('opacity-0');
            divContent.removeClass('opacity-0').addClass('opacity-100');
        });

        // Return the container
        return container;
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
        const loadedImages = [];
        for (let i = 0; i < urls.length; i++) {
            const img = $('<img/>');
            img.on('load', () => {
                img.off('load');
                loadedImages[i] = true;
                if (checkAllImagesLoaded(loadedImages)) {
                    callback();
                }
            });
            img.attr('src', urls[i]);
            loadedImages[i] = false;
        }
    }

    /**
     * Checks if all images in the array have successfully loaded.
     *
     * @param loadedImages
     * @returns {boolean}
     */
    function checkAllImagesLoaded(loadedImages) {
        for (let j = 0; j < loadedImages.length; j++) {
            if (!loadedImages[j]) {
                return false;
            }
        }
        return true;
    }
});
