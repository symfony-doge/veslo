require('../scss/app.scss');

const $ = require('jquery');

let module1 = require('../../../../src/AppBundle/Resources/public/js/module1.js');

$(document).ready(function () {
    //$('body').prepend('<h1>' + module1.showName('john') + '</h1>');
    $('body').prepend('Under development.');
});
