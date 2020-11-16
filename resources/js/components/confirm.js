const createConfirm = function($el) {
    const message = $el.getAttribute("data-confirm");

    $el.addEventListener("click", event => {
        if (!window.confirm(message)) {
            event.preventDefault();
        }
    });
};

export { createConfirm };
