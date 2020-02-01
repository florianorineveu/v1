require('../css/main.scss');

let email = 'hello@fnev.eu';

document.getElementsByClassName('js-mailto').forEach(function (htmlElement) {
    htmlElement.setAttribute('href', 'mailto:' + email);
});

document.getElementsByClassName('js-mail').forEach(function (htmlElement) {
    htmlElement.innerHTML = email;
});