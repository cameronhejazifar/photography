Dropzone.autoDiscover = false;

$(document).ready(() => {

    const photoDropzone = new Dropzone('#my-awesome-dropzone', {
        timeout: null,
        maxFilesize: null,
        maxFiles: null,
        dictDefaultMessage: 'Drag & Drop Image (JPG, PNG, etc.)',
        paramName: $('#my-awesome-dropzone input[type=file]').attr('name'),
    });
    photoDropzone.on('sending', () => {
        photoDropzone.removeAllFiles();
    });
    photoDropzone.on('success', () => {
        window.location.reload();
    });

});
