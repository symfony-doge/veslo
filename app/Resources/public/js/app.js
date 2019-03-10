
/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

require('../scss/app.scss');

//const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif)$/);
//imagesContext.keys().forEach(imagesContext);

const $ = require('jquery');

let module1 = require('../../../../src/AppBundle/Resources/public/js/module1.js');

$(document).ready(function () {
    //$('body').prepend('<h1>' + module1.showName('john') + '</h1>');
});
