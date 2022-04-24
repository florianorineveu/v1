import $ from 'jquery';
import 'bootstrap';

import 'datatables.net-bs4';
import 'datatables.net-fixedcolumns-bs4';
import 'datatables.net-fixedheader-bs4';
import 'datatables.net-responsive-bs4';

import 'jquery-fullscreen-plugin/jquery.fullscreen-min';

import 'select2';
import 'pickadate/lib/compressed/picker';
import 'pickadate/lib/compressed/picker.date';
import 'pickadate/lib/compressed/picker.time';
import 'pickadate/lib/compressed/legacy';

import 'jquery.json-viewer/json-viewer/jquery.json-viewer'

import './admin/styles/admin.scss';

window.$      = $;
window.jQuery = $;

$(function() {
    console.log('Init Lune JS');

    $('.select2').select2();
    $('.pickadate input').pickadate({
        format: 'dd/mm/yyyy',
    });

    $('.dataTable').dataTable({
        //responsive: true,
        language: {
            //url: './vendor/i18n/datatable.fr_fr.json'
            "emptyTable": "Aucune donnée disponible dans le tableau",
            "info": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "infoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "infoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "infoThousands": ",",
            "lengthMenu": "Afficher _MENU_ éléments",
            "loadingRecords": "Chargement...",
            "processing": "Traitement...",
            "search": "Rechercher :",
            "zeroRecords": "Aucun élément correspondant trouvé",
            "paginate": {
                "first": "Premier",
                "last": "Dernier",
                "next": "Suivant",
                "previous": "Précédent"
            },
            "aria": {
                "sortAscending": ": activer pour trier la colonne par ordre croissant",
                "sortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                },
                "_": "%d lignes selectionées"
            },
            "autoFill": {
                "cancel": "Annuler",
                "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                "fillHorizontal": "Remplir les cellules horizontalement",
                "fillVertical": "Remplir les cellules verticalement",
                "info": "Exemple de remplissage automatique"
            },
            "searchBuilder": {
                "conditions": {
                    "date": {
                        "after": "Après le",
                        "before": "Avant le",
                        "between": "Entre",
                        "empty": "Vide",
                        "equals": "Egal à",
                        "not": "Différent de",
                        "notBetween": "Pas entre",
                        "notEmpty": "Non vide"
                    },
                    "moment": {
                        "after": "Après le",
                        "before": "Avant le",
                        "between": "Entre",
                        "empty": "Vide",
                        "equals": "Egal à",
                        "not": "Différent de",
                        "notBetween": "Pas entre",
                        "notEmpty": "Non vide"
                    },
                    "number": {
                        "between": "Entre",
                        "empty": "Vide",
                        "equals": "Egal à",
                        "gt": "Supérieur à",
                        "gte": "Supérieur ou égal à",
                        "lt": "Inférieur à",
                        "lte": "Inférieur ou égal à",
                        "not": "Différent de",
                        "notBetween": "Pas entre",
                        "notEmpty": "Non vide"
                    },
                    "string": {
                        "contains": "Contient",
                        "empty": "Vide",
                        "endsWith": "Se termine par",
                        "equals": "Egal à",
                        "not": "Différent de",
                        "notEmpty": "Non vide",
                        "startsWith": "Commence par"
                    }
                }
            },
            "searchPanes": {
                "clearMessage": "Effacer tout",
                "count": "{total}",
                "emptyPanes": "Pas de recherche",
                "loadMessage": "Chargement de la recherche",
                "title": "Filtres actifs - %d"
            },
            "searchPlaceholder": "Exemple de recherche",
            "buttons": {
                "copy": "copier",
                "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                "copySuccess": {
                    "1": "une ligne copiée dans le presse-papier"
                },
                "copyTitle": "copier dans le presse-papier",
                "csv": "csv",
                "excel": "excel",
                "pdf": "pdf",
                "print": "imprimer"
            }
        }
    });

    $('form.js-location-select').on('change', 'select', function () {
        console.log('test');
        $(this).closest('form.js-location-select').submit();
    })

    $('form.js-location-checkbox').on('change', ':checkbox', function () {
        console.log('test');
        $(this).closest('form.js-location-checkbox').submit();
    })

    $('body').on('click', '.js-collapse-json', function () {
        let $this = $(this);

        if (!$this.hasClass('collapsed')) {
            $this.html('+');
            return;
        }

        $this.html('-');

        let $jsonViewerParent = $($this.data('target'));

        if ($jsonViewerParent.length) {
            let $jsonViewer = $jsonViewerParent.find('.js-json-viewer');

            $jsonViewer.jsonViewer($jsonViewer.data('json'), {
                collapsed: true,
                rootCollapsable: false,
                withQuotes: false,
                withLinks: true
            });
        }
    });

    $('.js-json-viewer').each(function () {
        let $this = $(this);

        if ($this.is(":visible")) {
            $this.jsonViewer($this.data('json'), {
                collapsed: true,
                rootCollapsable: false,
                withQuotes: false,
                withLinks: true
            });
        }
    })

    $('#fullscreenToggler').on('click', function (e) {
        $(document).toggleFullScreen();
        $(this).children('.fas').toggleClass('fa-expand fa-compress');
    });
});


