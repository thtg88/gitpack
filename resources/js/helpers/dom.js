const $ = (selector, $parent = document) => {
    return $parent.querySelector(selector);
};

const $$ = (selector, $parent = document) => {
    return Array.from($parent.querySelectorAll(selector));
};

const $ready = (selector, callback) => {
    const $el = $(selector);

    if ($el) {
        callback($el);
    }
};

const toggleClass = ($element, className) => {
    if ($element.classList.value.indexOf(className) > -1) {
        $element.classList.remove(className);
    } else {
        $element.classList.add(className);
    }
};

export { $, $$, $ready, toggleClass };
