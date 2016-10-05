/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2016 (original work) Open Assessment Technologies SA ;
 */
/**
 * @author Jean-Sébastien Conan <jean-sebastien.conan@vesperiagroup.com>
 */
define([
    'jquery',
    'lodash',
    'core/promise',
    'ui/documentViewer/providers/pdfViewer/pdfjs/pageView'
], function ($, _, Promise, pageViewFactory) {
    'use strict';

    /**
     * Creates a pages manager that will handle the PDF views
     * @param {jQuery} $container
     * @param {Object} config
     * @param {Object} config.PDFJS - The PDFJS entry point
     * @param {Number} [config.pageCount] - The number of pages views to manage (default: 1)
     * @param {Boolean} [config.fitToWidth] - Fit the page to the available width, a scroll bar may appear (default: false)
     * @returns {Object}
     */
    function pagesManagerFactory($container, config) {
        var activeView = null;
        var PDFJS = null;
        var views = null;
        var pageCount;

        var pagesManager = {
            /**
             * The number of managed pages views
             * @type {Number}
             */
            get pageCount() {
                return pageCount;
            },

            /**
             * Gets the pages container
             * @returns {jQuery}
             */
            getContainer: function getContainer() {
                return $container;
            },

            /**
             * Gets the view related to a particular page
             * @param {Number} pageNum
             * @returns {Object}
             */
            getView: function getView(pageNum) {
                var index, view;

                pageNum = Math.min(Math.max(1, parseInt(pageNum, 10) || 1), pageCount);
                index = pageNum - 1;

                view = views[index];
                if (!view) {
                    views[index] = view = pageViewFactory($container, {
                        pageNum: pageNum,
                        PDFJS: PDFJS
                    });
                }

                return view;
            },

            /**
             * Gets the active page view
             * @returns {Object}
             */
            getActiveView: function getActiveView() {
                return activeView;
            },

            /**
             * Sets the active page view
             * @param {Number} page
             */
            setActiveView: function setActiveView(page) {
                var oldActiveView = activeView;

                activeView = pagesManager.getView(page);

                if (oldActiveView && oldActiveView !== activeView) {
                    oldActiveView.hide();
                }

                if (activeView) {
                    activeView.show();
                }
            },

            /**
             * Renders a page into the active view
             * @param {Object} page
             * @returns {Promise}
             */
            renderPage: function renderPage(page) {
                if (activeView) {
                    return activeView.render(page, config.fitToWidth);
                }
                return Promise.resolve();
            },

            /**
             * Destroys the pages manager
             */
            destroy: function destroy() {
                _.forEach(views, function (view) {
                    if (view) {
                        view.destroy();
                    }
                });

                $container = null;
                activeView = null;
                views = null;
                config = null;
                PDFJS = null;
            }
        };

        config = config || {};
        PDFJS = config.PDFJS;

        if ('object' !== typeof PDFJS) {
            throw new TypeError('You must provide the entry point to the PDS.js library! [config.PDFJS is missing]');
        }

        pageCount = Math.max(1, parseInt(config.pageCount, 10) || 1);
        views = new Array(pageCount);

        return pagesManager;
    }

    return pagesManagerFactory;
});
