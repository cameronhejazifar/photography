$(document).ready(() => {

    const flickrForm = $('#flickr-post-form');
    const submitMethod = flickrForm.attr('method');
    const submitURL = flickrForm.attr('action');
    const formError = $('#flickr-form-errors');
    const formSubmit = $('#flickr-form-submit');

    flickrForm.on('submit', (e) => {
        e.preventDefault();
        formError.text('');
        formSubmit.prop('disabled', true);
        formSubmit.find('.animate-spin').removeClass('hidden');

        $.ajax({
            url: submitURL,
            timeout: 60000,
            cache: false,
            type: submitMethod,
            data: {
                // csrf
                _token: flickrForm.find('[name=_token]').val(),
                // form data
                title: flickrForm.find('[name=title]').val(),
                location: flickrForm.find('[name=location]').val(),
                description: flickrForm.find('[name=description]').val(),
                tags: flickrForm.find('[name=tags]').val().split(','),
                is_public: flickrForm.find('[name=is_public]').is(':checked') ? 1 : 0,
                is_friend: flickrForm.find('[name=is_friend]').is(':checked') ? 1 : 0,
                is_family: flickrForm.find('[name=is_family]').is(':checked') ? 1 : 0,
            },
            complete() {
                formSubmit.prop('disabled', false);
                formSubmit.find('.animate-spin').addClass('hidden');
            },
            error(xhr, status, error) {
                if (parseInt(xhr.status) === 422) {
                    let err = xhr.responseJSON.message;
                    for (let field in xhr.responseJSON.errors) {
                        if (xhr.responseJSON.errors.hasOwnProperty(field)) {
                            err += ' ' + field + ': ' + xhr.responseJSON.errors[field].join(' ');
                        }
                    }
                    formError.text(err);
                } else {
                    formError.text(xhr.responseText);
                }
            },
            success(result) {
                window.opener.onFlickrPostComplete(window, { success: false, post: result });
            },
        });
        return false;
    });

});
