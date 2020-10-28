$(document).ready(() => {

    // Select the text
    $('#instagram-description').on('click focus', () => {
        $('#instagram-description').select();
    }).focus().trigger('focus');

    // Close Window
    $('#close-instagram-post').on('click', (e) => {
        e.preventDefault();
        window.close();
    });

    // Copy Text
    $('#copy-text-button').on('click', (e) => {
        e.preventDefault();
        $('#instagram-description').focus().select();
        document.execCommand('copy');
    });

});
