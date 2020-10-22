$(document).ready(() => {

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

window.addNewPhotoTag = addNewPhotoTag;
