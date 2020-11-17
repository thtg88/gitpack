import { $$ } from "./helpers/dom";

var createConfirm = require('./components/confirm').createConfirm;

$$("[data-confirm]").forEach(createConfirm);

// require('alpinejs');
