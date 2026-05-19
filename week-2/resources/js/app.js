document.addEventListener('click', (event) => {
    const openButton = event.target.closest('[data-dialog-open]');
    const closeButton = event.target.closest('[data-dialog-close]');

    if (openButton) {
        document.getElementById(openButton.dataset.dialogOpen)?.showModal();
    }

    if (closeButton) {
        document.getElementById(closeButton.dataset.dialogClose)?.close();
    }
});

document.addEventListener('click', (event) => {
    if (event.target instanceof HTMLDialogElement) {
        event.target.close();
    }
});
