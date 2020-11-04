$(document).ready(() => {

    // Collection Filter
    const collectionSelector = $('#collection-selector');
    collectionSelector.on('change', () => {
        window.location = collectionSelector.val();
    });

    // Page Selector
    const pageSelector = $('#page-selector');
    pageSelector.on('change', () => {
        window.location = pageSelector.val();
    });

});
